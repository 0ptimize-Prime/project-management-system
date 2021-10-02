<?php

require_once __DIR__ . '/AbstractManager.php';

class UserManager extends AbstractManager
{
    public function registerUser($username, $name, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        if (!$password) {
            die();
        }

        $stmt = $this->db->prepare("INSERT INTO user(username, name, password) VALUES(?, ?, ?);");
        $result = $stmt->execute([$username, $name, $password]);

        if ($result) {
            return true;
        } else {
            die();
        }
    }

    public function getUserDetails($username): array|false
    {
        $user = $this->getUser($username);
        if ($user) {
            return ['username' => $user['username'], 'name' => $user['name']];
        } else {
            return false;
        }
    }

    public function checkCredentials($username, $password): bool
    {
        $user = $this->getUser($username);
        return $user && password_verify($password, $user['password']);
    }

    private function getUser($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE username=?;");
        $stmt->execute([$username]);
        $result = $stmt->fetch();
        if ($result) {
            return [
                'username' => $result['username'],
                'name' => $result['name'],
                'password' => $result['password'],
            ];
        }
    }
}
