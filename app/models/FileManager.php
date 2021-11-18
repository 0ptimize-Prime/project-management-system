<?php

require_once __DIR__ . '/AbstractManager.php';

class FileManager extends AbstractManager
{
    public function addFile(string $itemId, string $name): string|false
    {
        $id = bin2hex(random_bytes(20));
        $stmt = $this->db->prepare(
            "INSERT INTO file(id, item_id, name) VALUES(?, ?, ?);"
        );
        $result = $stmt->execute([$id, $itemId, $name]);

        if ($result) {
            return $id;
        } else {
            return false;
        }
    }

    public function getFiles(string $itemId): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM file WHERE item_id=?;");
        $stmt->execute([$itemId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function deleteFile(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM file WHERE id=?;");
        return $stmt->execute([$id]);
    }
}
