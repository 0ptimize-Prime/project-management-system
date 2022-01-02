<?php

require_once __DIR__ . "/../models/UserManager.php";
require_once __DIR__ . "/../models/NotificationManager.php";

class Home extends Controller
{
    public function dashboard()
    {
        $this->checkAuth("home/dashboard", function () {
            $notificationManager = NotificationManager::getInstance();
            return [
                'user' => $_SESSION["user"],
                'tasks' => [],
                'notifications' => $notificationManager->getNotifications($_SESSION["user"]["username"])
            ];
        });
    }
}
