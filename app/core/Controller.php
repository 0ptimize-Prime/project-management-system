<?php

abstract class Controller
{
    protected UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function model(string $model)
    {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }

    public function view(string $view, array $data = [])
    {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    public function checkAuth(string $view, callable $cb, array $args = [])
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] < 3600) {
            $_SESSION['last_activity'] = time();
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                $data = call_user_func_array($cb, $args);
                $this->view($view, $data);
            }
        } else {
            header("Location: " . BASE_URL . "auth/logout");
        }
    }
}
