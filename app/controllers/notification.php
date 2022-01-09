<?php

require_once __DIR__ . "/../models/NotificationManager.php";

class notification extends Controller
{
    public function status(string $id = null)
    {
        if ($_SERVER["REQUEST_METHOD"] === "PUT") {
            $this->checkAuth("notification/status", function () {
            });

            $notificationManager = NotificationManager::getInstance();
            if (!empty($id)) {
                $result = $notificationManager->markAsRead($id);
            } else {
                $result = $notificationManager->markOldAsRead($_SESSION["user"]["username"]);
            }
            if (!$result)
                http_response_code(400);
        }
    }
}