<?php
require_once __DIR__ . "/../utils.php";
require_once __DIR__."/../models/UserManager.php";

class admin extends Controller
{
    public function create()
    {
        session_start();
        $userManager = UserManager::getInstance();
        if ($_SESSION["user"]["userType"] != "ADMIN") {
            header("Location: " . BASE_URL . "home/dashboard");
            die;
        }
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->checkAuth("admin/create", function () {
                return ["user" => $_SESSION["user"]];
            });
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
                $userManager->registerUser(
                    $_POST["username"],
                    $_POST["name"],
                    $_POST["password"],
                    $_POST["userType"]
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

    public function edit()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET")
        {
            $this->checkAuth("admin/edit", function () {
                return ["user" => $_SESSION["user"]];
            });
        } else if ($_SERVER["REQUEST_METHOD"] == "POST")
        {

        }
    }

    public function search()
    {
        $userManager = UserManager::getInstance();
        if ($_SERVER["REQUEST_METHOD"] == "GET")
        {
            $this->checkAuth("admin/search", function() {
                return false;
            });
            if (isset(
                $_GET["username"],
                $_GET["name"],
                $_GET["userType"],
            ))
            {
                $result = $userManager->getUsersBy(
                    $_GET["username"],
                    $_GET["name"],
                    $_GET["userType"]
                );
                if ($result)
                    echo json_encode($result);
            }
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