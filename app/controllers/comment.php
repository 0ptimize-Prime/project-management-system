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

            $projectManager = ProjectManager::getInstance();
            $project = $projectManager->getProject($task["project_id"]);
            if ($user["username"] != $project["manager"]
                && !$taskManager->isEmployeeInProject($user["username"], $project["id"])) {
                header("Location: " . BASE_URL . "home/dashboard");
                die;
            }

            if (!isset($_POST["body"])) {
                FlashMessage::create_flash_message(
                    "create-comment",
                    "Invalid request",
                    new ErrorFlashMessage()
                );
            } else if (strlen($_POST["body"]) < 1) {
                FlashMessage::create_flash_message(
                    "create-comment",
                    "Body cannot be empty.",
                    new ErrorFlashMessage()
                );
            } else {
                $commentManager = CommentManager::getInstance();
                $id = $commentManager->addComment($taskId, $user["username"], $_POST["body"]);
                $comment = $commentManager->getComment($id);
                echo json_encode($comment);
                die;
            }
            header("Location: " . BASE_URL . "task/view/$taskId");
            die;
        }
    }
}
