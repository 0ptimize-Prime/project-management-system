<?php

require_once __DIR__."/../models/UserManager.php";

class Home extends Controller
{
    public function dashboard()
    {
        $this->checkAuth("home/dashboard", function () {
            $userManager = UserManager::getInstance();
            $user = $userManager->getUserDetails($_SESSION['user']['username']);

            return ['user' => $user, 'tasks' => []];
        });
    }
}
