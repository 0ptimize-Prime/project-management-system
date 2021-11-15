<?php
require_once __DIR__ . "/../utils.php";

class admin extends Controller
{
    public function create()
    {
        session_start();
        if ($_SESSION["user"]["userType"] != "ADMIN") {
            header("Location: " . BASE_URL . "home/dashboard");
            die;
        }
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->checkAuth("admin/create", function () {
                return ["name" => $_SESSION["user"]["username"]];
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
                create_flash_message("create-user", "Invalid request to create user.", FLASH_ERROR);
            } else if (
                $this->validate_create_user_data(
                    $_POST["username"],
                    $_POST["name"],
                    $_POST["userType"],
                    $_POST["password"]
                )
            ) {
                $this->userManager->registerUser(
                    $_POST["username"],
                    $_POST["name"],
                    $_POST["password"],
                    $_POST["userType"]
                );
                create_flash_message("create-user", "User `" . $_POST["username"] . "` created successfully.", FLASH_SUCCESS);
            }
            header("Location: " . BASE_URL . "admin/create");
            die;
        }
    }

    public function edit()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->checkAuth("admin/edit", function () {
                return array();
            });
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {

        }
    }

    private function validate_create_user_data(string $username, string $name, string $user_type, string $password): bool
    {
        // check if all the inputs are non-empty
        $args = func_get_args();
        foreach ($args as $arg) {
            if (strlen($arg) < 1) {
                create_flash_message("create-user", "All the fields are required.", FLASH_ERROR);
                return false;
            }
        }
        // check if the username available
        if (!$this->is_username_available($username)) {
            create_flash_message("create-user", "Username `" . $username . "` is not available.", FLASH_ERROR);
            return false;
        } // check if the name is alphabetic
        else if (!ctype_alpha(str_replace(" ", "", $name))) {
            create_flash_message("create-user", "The name should be alphabetic.", FLASH_ERROR);
            return false;
        } // check if the user_type is valid
        else if (!$this->is_valid_user_type($user_type)) {
            create_flash_message("create-user", "User type is not valid.", FLASH_ERROR);
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
        return !(bool)$this->userManager->getUserDetails($username);
    }
}