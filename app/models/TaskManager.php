<?php

require_once __DIR__ . "/AbstractManager.php";
require_once __DIR__ . "/../utils.php";

class TaskManager extends AbstractManager
{
    public function addTask(string $projectId, string $title, string $description, string $username, string $deadline, int $effort): string|false
    {
        $id = bin2hex(random_bytes(10));
        $deadlineTimestamp = strlen($deadline) < 1? null: date("Y-m-d", strtotime($deadline));
        $username = strlen($username) < 1? null: $username;
        $status = $username ? "ASSIGNED" : "CREATED";
        $ind = $this->getNextIndex($projectId);
        $stmt = $this->db->prepare(
            "INSERT INTO task(id, project_id, title, description, username, deadline, status, effort, ind)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $result = $stmt->execute([$id, $projectId, $title, $description, $username, $deadlineTimestamp, $status, $effort, $ind]);

        if ($result) {
            return $id;
        } else {
            return false;
        }
    }

    public function getTask(string $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT task.*, user.name, user.profile_picture FROM task
            LEFT JOIN user on task.username = user.username
            WHERE id=?;");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function getTasks(string $projectId): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT task.*, user.name, user.profile_picture FROM task
            LEFT JOIN user on task.username = user.username
            WHERE project_id=?
            ORDER BY task.ind;");
        $stmt->execute([$projectId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (gettype($result) == "array") {
            return $result;
        } else {
            return false;
        }
    }

    public function updateTask(string $id, string $title, string $description, string $username, string $deadline, string $effort): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE task SET title=?, description=?, username=?, deadline=?, effort=? WHERE id=?;"
        );
        $deadlineTimestamp = convertDateToTimestamp($deadline);
        return $stmt->execute([$title, $description, $username, $deadlineTimestamp, $effort, $id]);
    }
    public function updateStatus(string $id,string $status): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE task SET status=? WHERE id=?;"
        );
        $result = $stmt->execute([$status, $id]);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteTask(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM task WHERE id = ?;");
        return $stmt->execute([$id]);
    }

    public function isEmployeeInProject(string $username, string $projectId): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM task WHERE project_id=? AND username=? LIMIT 1;");
        $stmt->execute([$projectId, $username]);
        $result = $stmt->fetchColumn();

        return (bool)$result;
    }

    public function getNextIndex(string $projectId): int
    {
        $stmt = $this->db->prepare("SELECT GetNextIndex(?);");
        $stmt->execute([$projectId]);
        return $stmt->fetchColumn();
    }
}
