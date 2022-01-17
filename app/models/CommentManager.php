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
        $stmt = $this->db->prepare(
            "SELECT comment.*, user.name, user.profile_picture FROM comment
            LEFT JOIN user on comment.username = user.username
            WHERE id=?;");
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
        $stmt = $this->db->prepare(
            "SELECT comment.*, user.name, user.profile_picture FROM comment
            LEFT JOIN user on comment.username = user.username
            WHERE task_id=?;");
        $stmt->execute([$taskId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (gettype($result) == "array") {
            return $result;
        } else {
            return false;
        }
    }

    public function deleteCommentsByTaskId(string $taskId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM comment WHERE task_id = ?;");
        return $stmt->execute([$taskId]);
    }

    public function getCommentsWithFiles(string $taskId): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT comment.*, file.id as file_id, file.name as file_name, user.name, user.profile_picture
            FROM comment
            LEFT JOIN file
            ON comment.id=file.item_id
            LEFT JOIN user 
            on comment.username = user.username
            WHERE task_id=?
            ORDER BY created_at;");
        $stmt->execute([$taskId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (gettype($result) == "array") {
            return $result;
        } else {
            return false;
        }
    }
}
