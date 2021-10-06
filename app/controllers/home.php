<?php

class Home extends Controller
{
    public function dashboard()
    {
        $this->checkAuth("home/dashboard", function () {
            $user = $this->userManager->getUserDetails($_SESSION['user']['username']);

            return ['name' => $user['name']];
        });
    }
}
