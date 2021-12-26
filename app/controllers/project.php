<?php

require_once __DIR__ . "/../utils.php";
require_once __DIR__ . "/../models/UserManager.php";
require_once __DIR__ . "/../models/ProjectManager.php";
require_once __DIR__ . "/../models/TaskManager.php";
require_once __DIR__ . "/../models/MilestoneManager.php";
require_once __DIR__ . "/../models/FileManager.php";

class project extends Controller
{
    public function create()
    {
        session_start();
        $ProjectManager = ProjectManager::getInstance();
        $FileManager = FileManager::getInstance();
        if ($_SESSION["user"]["userType"] != "ADMIN" and $_SESSION["user"]["userType"] != "MANAGER") {
            header("Location: " . BASE_URL . "home/dashboard");
            die;
        }
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->checkAuth("project/create", function () {
                return ["user" => $_SESSION["user"]];
            });
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            $this->checkAuth("project/create", function () {
            });

            if (isset($_POST['submit'])) {
                $manager = $_SESSION['user']['username'];
                $title = $_POST['title'];
                $description = $_POST['description'];
                $deadline = $_POST['deadline'];

                if (!$this->validate_create_project($_POST['title'])) {
                    header("Location: " . BASE_URL . "project/create");
                    die;
                }
                $projectID = $ProjectManager->createProject($manager, $title, $description, $deadline);

                $file = $_FILES['file']['name'];
                $file_loc = $_FILES['file']['tmp_name'];
                $folder = __DIR__ . '/../../public/uploads/';
                $final_file = $FileManager->addFile($projectID, $file);
                move_uploaded_file($file_loc, $folder . $final_file);

                header('Location: ' . BASE_URL . "project/view/$projectID");
            }
        }
    }

    public function view(string $projectId)
    {
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->checkAuth("project/view", function (string $projectId) {
                $user = $_SESSION["user"];

                $projectManager = ProjectManager::getInstance();
                $taskManager = TaskManager::getInstance();

                $project = $projectManager->getProject($projectId);
                if ($user["username"] != $project["manager"]
                    && !$taskManager->isEmployeeInProject($user["username"], $project["id"])) {
                    header("Location: " . BASE_URL . "home/dashboard");
                    die;
                }

                $fileManager = FileManager::getInstance();
                $files = $fileManager->getFiles($projectId);

                $tasks = $taskManager->getTasks($projectId);
                $milestoneManager = MilestoneManager::getInstance();
                $milestones = $milestoneManager->getMilestones($projectId);

                return ["user" => $user, "project" => $project, "tasks" => $tasks, "milestones" => $milestones, "files" => $files];
            }, [$projectId]);
        }
    }

    private function validate_create_project(string $title): bool
    {
        $args = func_get_args();
        if ($args) {
            foreach ($args as $arg) {
                if (strlen($arg) < 1) {
                    FlashMessage::create_flash_message(
                        "create-project",
                        "All the fields are required.",
                        new ErrorFlashMessage()
                    );
                    return false;
                }
            }
        }
        return true;
    }
}



