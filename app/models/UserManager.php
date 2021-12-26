<?php

require_once __DIR__ . '/AbstractManager.php';

class UserManager extends AbstractManager
{
    public function registerUser($username, $name, $password, $type): bool
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        if (!$password) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO user(username, name, password, user_type) VALUES(?, ?, ?, ?);");
        return $stmt->execute([$username, $name, $password, $type]);
    }

    public function getUserDetails($username): array|false
    {
        $user = $this->getUser($username);
        if ($user) {
            return ['username' => $user['username'], 'name' => $user['name'], 'userType' => $user['userType']];
        } else {
            return false;
        }
    }

    public function checkCredentials($username, $password): bool
    {
        $user = $this->getUser($username);
        return $user && password_verify($password, $user['password']);
    }

    public function getUsersBy($username = '', $name = '', $type = ''): array|false
    {
        $query = "SELECT username, name, user_type, profile_picture FROM user WHERE username LIKE ? AND name LIKE ? ";
        $params = ['%' . $username . '%', '%' . $name . '%'];
        if (!empty($type)) {
            $query .= "AND user_type = ? ";
            $params[] = $type;
        }
        $query .= ";";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (gettype($result) === "array") {
            return $result;
        } else {
            return false;
        }
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
                'userType' => $result["user_type"]
            ];
        } else {
            return false;
        }
    }
}
