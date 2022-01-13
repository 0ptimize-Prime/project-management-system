<?php

class MilestoneManager extends AbstractManager
{
    public function addMilestone(string $projectId, string $title): string|false
    {
        $id = bin2hex(random_bytes(10));
        $ind = $this->getNextIndex($projectId);
        $stmt = $this->db->prepare(
            "INSERT INTO milestone(id, project_id, title, ind) VALUES(?, ?, ?, ?);"
        );
        $result = $stmt->execute([$id, $projectId, $title, $ind]);

        if ($result) {
            return $id;
        } else {
            return false;
        }
    }

    public function getMilestone(string $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM milestone WHERE id=?;");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function getMilestones(string $projectId): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM milestone WHERE project_id=? ORDER BY ind;");
        $stmt->execute([$projectId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (gettype($result) == "array") {
            return $result;
        } else {
            return false;
        }
    }

    public function updateMilestone(string $id, string $title): bool
    {
        $stmt = $this->db->prepare("UPDATE milestone SET title=? WHERE id=?;");
        return $stmt->execute([$title, $id]);
    }

    public function deleteMilestone(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM milestone WHERE id = ?;");
        return $stmt->execute([$id]);
    }

    public function deleteMilestonesByProjectId(string $projectId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM milestone WHERE project_id = ?");
        return $stmt->execute([$projectId]);
    }

    public function getNextIndex(string $projectId): int
    {
        $stmt = $this->db->prepare("SELECT GetNextIndex(?);");
        $stmt->execute([$projectId]);
        return $stmt->fetchColumn();
    }
}
