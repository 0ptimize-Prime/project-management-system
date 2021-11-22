<?php

require_once __DIR__ . "/../utils.php";
require_once __DIR__ . "/../models/UserManager.php";
require_once __DIR__ . "/../models/FileManager.php";
require_once __DIR__ . "/../models/ProjectManager.php";

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
                return ["name" => $_SESSION["user"]["username"]];
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

    private function validate_create_project(string $title): bool
    {
        $args = func_get_args();
        if ($args) {
            foreach ($args as $arg) {
                if (strlen($arg) < 1) {
                    create_flash_message("create-project", "All the fields are required.", FLASH_ERROR);
                    return false;
                }
            }
        }
        return true;
    }
}



