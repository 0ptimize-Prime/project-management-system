<?php

require_once __DIR__ . "/AbstractManager.php";
require_once __DIR__ . "/../utils.php";

class TaskManager extends AbstractManager
{
    public function addTask(string $projectId, string $title, string $description, string $username, string $deadline, int $effort): string|false
    {
        $id = bin2hex(random_bytes(10));
        $deadlineTimestamp = convertDateToTimestamp($deadline);
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
        $stmt = $this->db->prepare("SELECT * FROM task WHERE id=?;");
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
        $stmt = $this->db->prepare("SELECT * FROM task WHERE project_id=?;");
        $stmt->execute([$projectId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
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

    public function deleteTask(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM task WHERE id = ?;");
        return $stmt->execute([$id]);
    }

    public function getNextIndex(string $projectId): int
    {
        $stmt = $this->db->prepare("SELECT GetNextIndex(?);");
        $stmt->execute([$projectId]);
        return $stmt->fetchColumn();
    }
}
