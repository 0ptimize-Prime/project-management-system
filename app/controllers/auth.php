<?php
require_once __DIR__ . "/../utils.php";
require_once __DIR__ . "/../models/UserManager.php";

class Auth extends Controller
{
    public function login()
    {
        session_start();
        $userManager = UserManager::getInstance();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (isset($_SESSION["user"]) && isset($_SESSION["last_activity"])) {
                header("Location: " . BASE_URL . "home/dashboard");
            } else {
                if (isset($_GET["next"]))
                    $_SESSION["next"] = $_GET["next"];
                $this->showView("auth/login");
            }
            die;
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["username"], $_POST["password"])) {
                if (strlen($_POST["username"]) < 1 || strlen($_POST["password"]) < 1) {
                    FlashMessage::create_flash_message(
                        "login",
                        "Both username and password required.",
                        new ErrorFlashMessage()
                    );
                } else if (!$userManager->checkCredentials($_POST["username"], $_POST["password"])) {
                    FlashMessage::create_flash_message(
                        "login",
                        "Invalid username or password.",
                        new ErrorFlashMessage()
                    );
                } else {
                    $_SESSION["user"] = $userManager->getUserDetails($_POST["username"]);
                    $_SESSION["last_activity"] = time();
                    if (isset($_SESSION["next"])) {
                        header("Location: " . BASE_URL . $_SESSION["next"]);
                        die;
                    }
                    header("Location: " . BASE_URL . "home/dashboard");
                    die;
                }
            } else {
                FlashMessage::create_flash_message(
                    "login",
                    "Login error occurred.",
                    new ErrorFlashMessage()
                );
            }
            header("Location: " . BASE_URL . "auth/login");
            die;
        }
    }

    public function logout()
    {
        session_start();
        $route = "auth/login";
        if (isset($_SESSION["next"])) {
            $next = $_SESSION["next"];
            if (!empty($next)) {
                if (str_ends_with($next, "search")) {
                    str_replace($next, "edit");
                }
                $route .= "?next=" . $next;
            }
        }
        session_destroy();
        header("Location: " . BASE_URL . $route);
    }
}
