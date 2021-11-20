<?php

require_once __DIR__ . "/../models/TaskManager.php";
require_once __DIR__ . "/../models/ProjectManager.php";
require_once __DIR__ . "/../models/FileManager.php";
require_once __DIR__ . "/../models/CommentManager.php";

class Task extends Controller
{
    public function view(string $taskId)
    {
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->checkAuth("task/view", function (string $taskId) {
                $user = $_SESSION["user"];

                $taskManager = TaskManager::getInstance();
                $task = $taskManager->getTask($taskId);

                $projectManager = ProjectManager::getInstance();
                $project = $projectManager->getProject($task["project_id"]);
                if ($user["username"] != $project["manager"]
                    && !$taskManager->isEmployeeInProject($user["username"], $project["id"])) {
                    header("Location: " . BASE_URL . "home/dashboard");
                    die;
                }

                $fileManager = FileManager::getInstance();
                $files = $fileManager->getFiles($taskId);

                $commentManager = CommentManager::getInstance();
                $comments = $commentManager->getComments($taskId);

                return ["task" => $task, "project" => $project, "files" => $files, "comments" => $comments];
            }, [$taskId]);
        }
    }
}
