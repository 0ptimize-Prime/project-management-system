<?php

require_once __DIR__ . "/../models/UserManager.php";

class Home extends Controller
{
    public function dashboard()
    {
        $this->checkAuth("home/dashboard", function () {
            return ['user' => $_SESSION["user"], 'tasks' => []];
        });
    }
}
