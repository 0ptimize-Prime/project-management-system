<?php

require_once __DIR__ . "/../models/NotificationManager.php";

abstract class Controller
{
    public function model(string $model)
    {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }

    public function showView(string $view, array $data = [])
    {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    public function checkAuth(string $view, callable $cb, array $args = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] < 3600) {
            $_SESSION['last_activity'] = time();
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                $data = call_user_func_array($cb, $args);
                if ($data !== false)
                {
                    $this->showView($view, $data);
                }
            }
        } else {
            $_SESSION["next"] = $view . "/" . $args[0];
            header("Location: " . BASE_URL . "auth/logout");
            die;
        }
    }

    public function getViewData()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $notifications = NotificationManager::getInstance()->getNotifications($_SESSION["user"]["username"]);
        return [
            "user" => $_SESSION["user"],
            "notifications" => $notifications ?? []
        ];
    }
}
