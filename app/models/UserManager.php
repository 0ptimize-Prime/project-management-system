<?php

require_once __DIR__ . '/AbstractManager.php';

class UserManager extends AbstractManager
{
    public function registerUser($username, $name, $password): bool
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        if (!$password) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO user(username, name, password) VALUES(?, ?, ?);");
        return $stmt->execute([$username, $name, $password]);
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

    private function getUser($username): array|false
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
        } else {
            return false;
        }
    }
}
