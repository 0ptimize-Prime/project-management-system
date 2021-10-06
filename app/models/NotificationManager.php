<?php

require_once __DIR__ . '/AbstractManager.php';

class NotificationManager extends AbstractManager
{
    public function getNotifications(string $username): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM notification WHERE username=?;");
        $stmt->execute([$username]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
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
}