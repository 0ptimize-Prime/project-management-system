<?php

require_once __DIR__ . "/../utils.php";
require_once __DIR__ . "/../models/TaskManager.php";
require_once __DIR__ . "/../models/ProjectManager.php";
require_once __DIR__ . "/../models/CommentManager.php";

class Comment extends Controller
{
    public function task(string $taskId)
    {
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->checkAuth("", function () {
                return array();
            });

            $user = $_SESSION["user"];

            $taskManager = TaskManager::getInstance();
            $task = $taskManager->getTask($taskId);
            if (!$task) {
                http_response_code(404);
                die;
            }

            $projectManager = ProjectManager::getInstance();
            $project = $projectManager->getProject($task["project_id"]);
            if ($user["username"] != $project["manager"]
                && !$taskManager->isEmployeeInProject($user["username"], $project["id"])) {
                http_response_code(403);
                die;
            }

            if (!isset($_POST["body"])) {
                http_response_code(400);
            } else if (strlen($_POST["body"]) < 1) {
                http_response_code(400);
            } else {
                $commentManager = CommentManager::getInstance();
                $id = $commentManager->addComment($taskId, $user["username"], $_POST["body"]);
                $comment = $commentManager->getComment($id);
                echo json_encode($comment);
            }
        }
    }
}
