<?php
require_once __DIR__ . "/../utils.php";
require_once __DIR__."/../models/TaskManager.php";
require_once __DIR__."/../models/UserManager.php";
require_once __DIR__."/../models/ProjectManager.php";

class Task extends Controller
{
    public function create() {
        session_start();
        if ($_SESSION["user"]["userType"] == "EMPLOYEE") {
            header("Location: " . BASE_URL . "home/dashboard");
            die;
        }
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->checkAuth("task/create", function () {
                return ["name" => $_SESSION["user"]["username"]];
            });
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            $this->checkAuth("task/create", function () {
            });
            $check_user=$this->check_user($_POST["username"]);
            $check_project=$this->check_project($_POST["projectId"]);
            if ($check_user && $check_project) {
                $taskManager = TaskManager::getInstance();
                $result=$taskManager->addTask(
                    $_POST["projectId"],
                    $_POST["title"],
                    $_POST["description"],
                    $_POST["username"],
                    $_POST["deadline"],
                    $_POST["effort"],
                );
                $result=true;
                if ($result) {
                    create_flash_message("create-task", "Task `" . $_POST["title"] . "` created successfully.", FLASH_SUCCESS);
                } else {
                    create_flash_message("create-task", "Something went wrong, couldn't create task.", FLASH_ERROR);
                }
            } else {
                create_flash_message("create-task", "Invalid request to create task.", FLASH_ERROR);
            }
            header("Location: " . BASE_URL . "task/create");
            die;
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

    private function check_project(string $projectId):bool 
    {
        $projectManager = ProjectManager::getInstance();
        return (bool)$projectManager->getProject($projectId);
    }
}
