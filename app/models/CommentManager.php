<?php

require_once __DIR__ . '/AbstractManager.php';

class CommentManager extends AbstractManager
{
    public function addComment(string $taskId, string $username, string $body): string|false
    {
        $id = bin2hex(random_bytes(10));
        $stmt = $this->db->prepare(
            "INSERT INTO comment(id, task_id, username, body)
            VALUES(?, ?, ?, ?);");
        $result = $stmt->execute([$id, $taskId, $username, $body]);

        if ($result) {
            return $id;
        } else {
            return false;
        }
    }

    public function getComment(string $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM comment WHERE id=?;");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function getComments(string $taskId): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM comment WHERE task_id=?;");
        $stmt->execute([$taskId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (gettype($result) == "array") {
            return $result;
        } else {
            return false;
        }
    }
}
