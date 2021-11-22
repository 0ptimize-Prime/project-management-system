<?php

require_once __DIR__ . "/../utils.php";
require_once __DIR__ . "/../models/UserManager.php";
require_once __DIR__ . "/../models/ProjectManager.php";
require_once __DIR__ . "/../models/TaskManager.php";
require_once __DIR__ . "/../models/FileManager.php";
require_once __DIR__ . "/../models/CommentManager.php";

class Task extends Controller
{
    public function create(string $projectId) {
        session_start();
        $project = $this->check_project($projectId);
        if (!$project || $_SESSION["user"]["username"] != $project["manager"]) {
            header("Location: " . BASE_URL . "home/dashboard");
            die;
        }
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->checkAuth("task/create", function (string $projectId, string $projectTitle) {
                return [
                    "name" => $_SESSION["user"]["username"], 
                    "projectId" => $projectId,
                    "projectTitle" => $projectTitle
                ];
            }, [$projectId, $project["title"]]);
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            $this->checkAuth("task/create", function (string $projectId) {
                return ["name" => $_SESSION["user"]["username"], "projectId" => $projectId];
            }, [$projectId]);
            
            $check_user=$this->check_user($_POST["username"]);
            if ($check_user) {
                $taskManager = TaskManager::getInstance();
                $result=$taskManager->addTask(
                    $_POST["projectId"],
                    $_POST["title"],
                    $_POST["description"],
                    $_POST["username"],
                    $_POST["deadline"],
                    $_POST["effort"],
                );
                if ($result) {
                    create_flash_message("create-task", "Task `" . $_POST["title"] . "` created successfully.", FLASH_SUCCESS);
                } else {
                    create_flash_message("create-task", "Something went wrong, couldn't create task.", FLASH_ERROR);
                }
            } else {
                create_flash_message("create-task", "Invalid request to create task.", FLASH_ERROR);
            }
            header("Location: " . BASE_URL . "task/create/$projectId");
            die;
        }
    }
  
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
      
    private function check_user(string $username):bool 
    {
        if(strlen($username) < 1) {
            return true;
        }
        $userManager = UserManager::getInstance();
        return (bool)$userManager->getUserDetails($username);
    }

    private function check_project(string $projectId): array|false
    {
        $projectManager = ProjectManager::getInstance();
        return $projectManager->getProject($projectId);
    }
}
