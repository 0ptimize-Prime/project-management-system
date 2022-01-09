<?php

require_once __DIR__ . "/../models/NotificationManager.php";

class notification extends Controller
{
    public function status($id = null)
    {
        if ($_SERVER["REQUEST_METHOD"] === "PUT") {
            $this->checkAuth("notification/status", function() {});

        }
    }
}