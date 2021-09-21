<?php

class Auth extends Controller
{
    public function login()
    {
        $this->view("auth/login");
    }

    public function logout()
    {
    }
}
