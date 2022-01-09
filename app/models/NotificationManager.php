<?php

require_once __DIR__ . '/AbstractManager.php';

class NotificationManager extends AbstractManager
{
    public function getNotifications(string $username): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM notification WHERE username=?;");
        $stmt->execute([$username]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (gettype($result) == "array") {
            return $result;
        } else {
            return false;
        }
    }

    public function markOldAsRead(string $username): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE notification SET is_read = 1
            WHERE username = ? AND created_at < NOW()"
        );
        return $stmt->execute([$username]);
    }

    public function markAsRead(string $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE notification SET is_read = 1
            WHERE id = ?;"
        );
        return $stmt->execute([$id]);
    }

    public function deleteNotifications(string $username): bool
    {
        $stmt = $this->db->prepare("DELETE FROM notification WHERE username = ?;");
        return $stmt->execute([$username]);
    }
}
