<?php

require_once __DIR__ . "/AbstractManager.php";
require_once __DIR__ . "/../utils.php";

class ProjectManager extends AbstractManager
{
    public function createProject(string $manager, string $title, string $description, string $deadline): string|false
    {
        $id = bin2hex(random_bytes(10));
        $stmt = $this->db->prepare(
            "INSERT INTO project(id, manager, title, description, deadline) VALUES(?, ?, ?, ?, ?);"
        );
//        $deadlineTimestamp = convertDateToTimestamp($deadline);
        $deadline = !empty($deadline) ? $deadline : null;
        $result = $stmt->execute([$id, $manager, $title, $description, $deadline]);

        if ($result) {
            return $id;
        } else {
            return false;
        }
    }

    public function getProject(string $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM project WHERE id=?;");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function getProjectsBy($title = '', $manager = ''): array|false
    {
        $query = "SELECT * FROM project WHERE title LIKE ? AND manager LIKE ?;";
        $params = ['%' . $title . '%', '%' . $manager . '%'];

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (gettype($result) === "array") {
            return $result;
        } else {
            return false;
        }
    }

    public function updateProject(string $id, string $manager, string $title, string $description, string $deadline): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE project SET manager=?, title=?, description=?, deadline=? WHERE id=?;"
        );
        $deadlineTimestamp = convertDateToTimestamp($deadline);
        return $stmt->execute([$manager, $title, $description, $deadlineTimestamp, $id]);
    }

    public function deleteProject(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM project WHERE id = ?;");
        return $stmt->execute([$id]);
    }

    public function reorder(array $items)
    {
        $taskStmt = $this->db->prepare("UPDATE task SET ind=? WHERE id=?;");
        $milestoneStmt = $this->db->prepare("UPDATE milestone SET ind=? WHERE id=?;");
        foreach ($items as $item) {
            if ($item['type'] == 'task') {
                $taskStmt->execute([$item['ind'], $item['id']]);
            } else {
                $milestoneStmt->execute([$item['ind'], $item['id']]);
            }
        }
    }
}
