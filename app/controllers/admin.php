<?php
require_once __DIR__ . "/../utils.php";
require_once __DIR__ . "/../models/UserManager.php";
require_once __DIR__ . "/../models/FileManager.php";

class admin extends Controller
{
    public function create()
    {
        session_start();
        $userManager = UserManager::getInstance();
        $fileManager = FileManager::getInstance();
        if ($_SESSION["user"]["userType"] != "ADMIN") {
            header("Location: " . BASE_URL . "home/dashboard");
            die;
        }
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $data = $this->getViewData();
            $this->checkAuth("admin/create", function ($data) {
                return $data;
            }, [$data]);
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            $this->checkAuth("admin/create", function () {
            });
            if (
                !isset(
                    $_POST["username"],
                    $_POST["name"],
                    $_POST["userType"],
                    $_POST["password"]
                )
            ) {
                FlashMessage::create_flash_message(
                    "create-user",
                    "Invalid request to create user.",
                    new ErrorFlashMessage()
                );
            } else if (
                $this->validate_create_user_data(
                    $_POST["username"],
                    $_POST["name"],
                    $_POST["userType"],
                    $_POST["password"]
                )
            ) {
                $profile_picture = null;
                if ($_FILES["profile_picture"]["tmp_name"]) {
                    $file = $_FILES["profile_picture"]["name"];
                    $file_loc = $_FILES["profile_picture"]["tmp_name"];
                    $folder = __DIR__ . '/../../public/uploads/';
                    $profile_picture = $fileManager->addFile($_POST["username"], $file);
                    if ($profile_picture)
                        move_uploaded_file($file_loc, $folder . $profile_picture);
                }


                $userManager->registerUser(
                    $_POST["username"],
                    $_POST["name"],
                    $_POST["password"],
                    $_POST["userType"],
                    $profile_picture
                );


                FlashMessage::create_flash_message(
                    "create-user",
                    "User `" . $_POST["username"] . "` created successfully.",
                    new SuccessFlashMessage()
                );
            }
            header("Location: " . BASE_URL . "admin/create");
            die;
        }
    }

    public function edit(...$args)
    {
        session_start();
        $userManager = UserManager::getInstance();
        $fileManager = FileManager::getInstance();
        if ($_SESSION["user"]["userType"] != "ADMIN") {
            header("Location: " . BASE_URL . "home/dashboard");
            die;
        }

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $data = $this->getViewData();
            $this->checkAuth("admin/edit", function ($data) {
                return $data;
            }, [$data]);
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->checkAuth("admin/edit", function () {
            });

            if (!isset(
                $_POST["username"],
                $_POST["name"],
                $_POST["userType"]
            )) {
                http_response_code(400);
                die;
            }
            if (!$this->validate_update_user_data($_POST["username"], $_POST["name"], $_POST["userType"])) {
                http_response_code(400);
                die;
            }

            $profile_picture = "";

            // handle profile picture remove
            if (isset($_POST["remove_profile_picture"]) && $_POST["remove_profile_picture"] !== "") {
                $profile_picture = null;
                $user = $userManager->getUserDetails($_POST["username"]);
                if ($user && !empty($user["profile_picture"])) {
                    $fileManager->deleteFile($user["profile_picture"]);
                    if (file_exists(__DIR__ . '/../../public/uploads/' . $user["profile_picture"]))
                        unlink(__DIR__ . '/../../public/uploads/' . $user["profile_picture"]);
                }
            }

            // handle new profile picture upload
            if ($_FILES["profile_picture"]["tmp_name"]) {
                $file = $_FILES["profile_picture"]["name"];
                $file_loc = $_FILES["profile_picture"]["tmp_name"];
                $folder = __DIR__ . '/../../public/uploads/';
                $profile_picture = $fileManager->addFile($_POST["username"], $file);
                if ($profile_picture)
                    move_uploaded_file($file_loc, $folder . $profile_picture);
            }

            $result = $userManager->updateUser($_POST["username"], $_POST["name"], $_POST["userType"], $profile_picture);
            if ($result) {
                $response = $userManager->getUserDetails($_POST["username"]);
                echo json_encode($response);
            } else
                http_response_code(400);
        } else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
            $username = $args[0];
            if (!$this->is_username_available($username)) {
                $user = $userManager->getUserDetails($username);
                $result = true;
                if (!empty($user["profile_picture"]) && file_exists(__DIR__ . '/../../public/uploads/' . $user["profile_picture"])) {
                    unlink(__DIR__ . '/../../public/uploads/' . $user["profile_picture"]);
                    $result = $fileManager->deleteFile($user["profile_picture"]);
                }

                if ($result) {
                    $result = $userManager->removeUser($username);
                } else {
                    http_response_code(400);
                }

            }
            http_response_code(400);
        }
    }

    public function search()
    {
        $userManager = UserManager::getInstance();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->checkAuth("admin/search", function () {
                return false;
            });
            if (!isset(
                $_GET["username"],
                $_GET["name"],
                $_GET["userType"],
            )) {
                http_response_code(400);
                die;
            }
            $result = $userManager->getUsersBy(
                $_GET["username"],
                $_GET["name"],
                $_GET["userType"]
            );
            if ($result)
                echo json_encode($result);
        }
    }

    private function validate_create_user_data(string $username, string $name, string $user_type, string $password): bool
    {
        // check if all the inputs are non-empty
        $args = func_get_args();
        foreach ($args as $arg) {
            if (strlen($arg) < 1) {
                FlashMessage::create_flash_message(
                    "create-user",
                    "All the fields are required.",
                    new ErrorFlashMessage()
                );
                return false;
            }
        }
        // check if the username available
        if (!$this->is_username_available($username)) {
            FlashMessage::create_flash_message(
                "create-user",
                "Username `" . $username . "` is not available.",
                new ErrorFlashMessage()
            );
            return false;
        } // check if the name is alphabetic
        else if (!ctype_alpha(str_replace(" ", "", $name))) {
            FlashMessage::create_flash_message(
                "create-user",
                "The name should be alphabetic.",
                new ErrorFlashMessage()
            );
            return false;
        } // check if the user_type is valid
        else if (!$this->is_valid_user_type($user_type)) {
            FlashMessage::create_flash_message(
                "create-user",
                "User type is not valid.",
                new ErrorFlashMessage()
            );
            return false;
        } else return true;
    }

    private function validate_update_user_data(string $username, string $name, string $user_type): bool
    {
        // check if all the inputs are non-empty
        $args = func_get_args();
        foreach ($args as $arg) {
            if (strlen($arg) < 1) {
                return false;
            }
        }
        // check if the username exists
        if ($this->is_username_available($username)) {
            return false;
        } // check if the name is alphabetic
        else if (!ctype_alpha(str_replace(" ", "", $name))) {
            return false;
        } // check if the user_type is valid
        else if (!$this->is_valid_user_type($user_type)) {
            return false;
        } else return true;
    }

    private function is_valid_user_type(string $user_type): bool
    {
        $user_types = array(
            "ADMIN",
            "MANAGER",
            "EMPLOYEE"
        );
        return in_array($user_type, $user_types);
    }

    private function is_username_available(string $username): bool
    {
        $userManager = UserManager::getInstance();
        return !(bool)$userManager->getUserDetails($username);
    }
}