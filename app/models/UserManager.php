<?php

require_once __DIR__ . '/AbstractManager.php';

class UserManager extends AbstractManager
{
    public function registerUser($username, $name, $password, $type, $profile_picture): bool
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        if (!$password) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO user(username, name, password, user_type, profile_picture) VALUES(?, ?, ?, ?, ?);");
        return $stmt->execute([$username, $name, $password, $type, $profile_picture]);
    }

    public function updateUser($username, $name, $type): bool
    {
        $stmt = $this->db->prepare("UPDATE user SET name = ?, user_type = ? WHERE username = ?");
        return $stmt->execute([$name, $type, $username]);
    }

    public function removeUser($username): bool
    {
        $stmt = $this->db->prepare("DELETE FROM user WHERE username = ?");
        return $stmt->execute([$username]);
    }

    public function getUserDetails($username): array|false
    {
        $user = $this->getUser($username);
        if ($user) {
            return [
                'username' => $user['username'],
                'name' => $user['name'],
                'userType' => $user['userType'],
                'profile_picture' => !$user["profile_picture"] ? null : "/public/uploads/" . $user['profile_picture']
            ];
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
            $output = array();
            foreach ($result as $row) {
                array_push($output, [
                    "username" => $row["username"],
                    "name" => $row["name"],
                    "userType" => $row["user_type"],
                    "profile_picture" => $row["profile_picture"]
                ]);
            }
            return $output;
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
                'userType' => $result["user_type"],
                'profile_picture' => $result["profile_picture"]
            ];
        } else {
            return false;
        }
    }
}
