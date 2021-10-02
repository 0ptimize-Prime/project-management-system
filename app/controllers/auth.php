<?php
require_once __DIR__."/../utils.php";

class Auth extends Controller
{
    public function login()
    {
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (isset($_SESSION["username"]) && isset($_SESSION["last_activity"])) {
                header("Location: " . BASE_URL . "home/dashboard");
            } else {
                $this->view("auth/login");
            }
            die;
        }
        else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["username"], $_POST["password"])) {
                if (strlen($_POST["username"]) < 1 || strlen($_POST["password"]) < 1) {
                    create_flash_message(
                        "login",
                        "Both username and password required.",
                        FLASH_ERROR
                    );
                } else if (!$this->userManager->checkCredentials($_POST["username"], $_POST["password"])) {
                    create_flash_message(
                        "login",
                        "Invalid username or password.",
                        FLASH_ERROR
                    );
                } else {
                    $_SESSION["username"] = $_POST["username"];
                    $_SESSION["last_activity"] = time();
                    header("Location: " . BASE_URL . "home/dashboard");
                    die;
                }
            } else {
                create_flash_message(
                    "login",
                    "Login error occurred.",
                    FLASH_ERROR
                );
            }
            header("Location: " . BASE_URL . "auth/login");
            die;
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: " . BASE_URL . "auth/login");
    }
}
