<?php

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
            if (isset($_POST["username"], $_POST["password"])
                && strlen($_POST["username"]) > 0
                && strlen($_POST["password"]) > 0
                && $this->userManager->checkCredentials($_POST["username"], $_POST["password"])
            ) {
                $_SESSION["username"] = $_POST["username"];
                $_SESSION["last_activity"] = time();
                header("Location: " . BASE_URL . "home/dashboard");
            } else {
                header("Location: " . BASE_URL . "auth/login");
            }
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
