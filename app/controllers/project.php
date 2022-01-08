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

                if ($_FILES["file"]["tmp_name"]) {
                    $file = $_FILES['file']['name'];
                    $file_loc = $_FILES['file']['tmp_name'];
                    $folder = __DIR__ . '/../../public/uploads/';
                    $final_file = $FileManager->addFile($projectID, $file);
                    if ($final_file)
                        move_uploaded_file($file_loc, $folder . $final_file);
                }

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

    public function edit(...$args)
    {
        session_start();
        $userManager = UserManager::getInstance();
        $projectManager = ProjectManager::getInstance();
        $fileManager = FileManager::getInstance();
        $taskManager = TaskManager::getInstance();
        $managers = $userManager->getUsersBy('', '', 'MANAGER') ?? [];
        $managers = array_merge($managers, $userManager->getUsersBy('', '', 'ADMIN') ?? []);
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if ($_SESSION["user"]["userType"] === "EMPLOYEE") {
                header("Location: " . BASE_URL . "home/dashboard");
                die;
            }

            $this->checkAuth("project/edit", function ($managers) {
                return ["user" => $_SESSION["user"], "managers" => $managers];
            }, [$managers]);
        } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->checkAuth("project/edit", function () {
                return false;
            });

            if ($_SESSION["user"]["userType"] === "ADMIN") {
                if (!isset(
                    $_POST["id"],
                    $_POST["title"],
                    $_POST["manager"],
                    $_POST["description"],
                    $_POST["deadline"]
                )) {
                    http_response_code(400);
                    die;
                }

                if (!$this->validate_update_project(
                    $_POST["id"],
                    $_POST["manager"],
                    $_POST["title"],
                    $_POST["description"],
                    $_POST["deadline"]
                )) {
                    http_response_code(400);
                    die;
                }

                $result = $projectManager->updateProject(
                    $_POST["id"],
                    $_POST["manager"],
                    $_POST["title"],
                    $_POST["description"],
                    $_POST["deadline"]
                );
                if ($result) {
                    $response = $projectManager->getProject($_POST["id"]);
                    echo json_encode($response);
                } else
                    http_response_code(400);
            } else if ($_SESSION["user"]["userType"] === "MANAGER") {
                if (!isset(
                    $_POST["id"],
                    $_POST["title"],
                    $_POST["description"],
                    $_POST["deadline"]
                )) {
                    http_response_code(400);
                    die;
                }

                if (!$this->validate_update_project(
                    $_POST["id"],
                    $_SESSION["user"]["username"],
                    $_POST["title"],
                    $_POST["description"],
                    $_POST["deadline"]
                )) {
                    http_response_code(400);
                    die;
                }

                $project = $projectManager->getProject($_POST["id"]);
                if ($project && $project["manager"] !== $_SESSION["user"]["username"])
                    die;
                $result = $projectManager->updateProject(
                    $_POST["id"],
                    $_SESSION["user"]["username"],
                    $_POST["title"],
                    $_POST["description"],
                    $_POST["deadline"]
                );
                if ($result) {
                    $response = $projectManager->getProject($_POST["id"]);
                    echo json_encode($response);
                } else
                    http_response_code(400);
            }
        } else if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
            $this->checkAuth("project/edit", function () {
                return false;
            });
            if ($_SESSION["user"]["userType"] === "ADMIN") {
                $id = $args[0];
                $result = true;
                // delete file associated with project
                $files = $fileManager->getFiles($id);
                if ($files) {
                    if (file_exists(__DIR__ . '/../../public/uploads/' . $files[0]["id"])) {
                        unlink(__DIR__ . '/../../public/uploads/' . $files[0]["id"]);
                    }
                    $result = $fileManager->deleteFile($files[0]["id"]);
                }

                if (!$result) {
                    http_response_code(400);
                    die;
                }

                // delete files associated with tasks
                $tasks = $taskManager->getTasks($id);
                if ($tasks) {
                    foreach ($tasks as $task) {
                        $files = $fileManager->getFiles($task["id"]);
                        if ($files) {
                            if (file_exists(__DIR__ . '/../../public/uploads/' . $files[0]["id"])) {
                                unlink(__DIR__ . '/../../public/uploads/' . $files[0]["id"]);
                            }
                            $result = $fileManager->deleteFile($files[0]["id"]);
                        }
                    }
                }
                if ($result)
                    $result = $projectManager->deleteProject($id);
                if (!$result)
                    http_response_code(400);
            }
        }
    }

    public function search()
    {
        $projectManager = ProjectManager::getInstance();
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $this->checkAuth("project/edit", function () {
                return false;
            });
            if ($_SESSION["user"]["userType"] === "ADMIN") {
                if (!isset(
                    $_GET["title"],
                    $_GET["manager"]
                )) {
                    http_response_code(400);
                    die;
                }
                $result = $projectManager->getProjectsBy($_GET["title"], $_GET["manager"]);
                if ($result)
                    echo json_encode($result);
            } else if ($_SESSION["user"]["userType"] === "MANAGER") {
                if (!isset(
                    $_GET["title"]
                )) {
                    http_response_code(400);
                    die;
                }
                $result = $projectManager->getProjectsBy($_GET["title"], $_SESSION["user"]["username"]);
                if ($result)
                    echo json_encode($result);
            }
        }
    }

    public function reorder() {
        $projectManager = ProjectManager::getInstance();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->checkAuth("project/reorder", function (){});
            if (!isset(
                $_POST["id"],
                $_POST["items"]
            )) {
                http_response_code(400);
                die;
            }

            $project = $projectManager->getProject($_POST["id"]);
            if (!$project || $project["manager"] !== $_SESSION["user"]["username"]) {
                http_response_code(400);
                die;
            }

            $result = $projectManager->reorder($_POST["items"]);
            if (!$result)
                http_response_code(400);
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

    private function validate_update_project(string $id, string $manager, string $title, string $description, string $deadline): bool
    {
        // check if all the inputs are non-empty
        $args = func_get_args();
        foreach ($args as $arg) {
            if (strlen($arg) < 1) {
                return false;
            }
        }

        // check if the project id is valid
        if (!$this->is_project_valid($id))
            return false;

        // check if the manager is valid
        if (!$this->is_manager_valid($manager))
            return false;

        return true;

    }

    private function is_project_valid($id): bool
    {
        $projectManager = ProjectManager::getInstance();
        $project = $projectManager->getProject($id);
        if (!$project)
            return false;
        return true;
    }

    private function is_manager_valid($manager): bool
    {
        $userManager = UserManager::getInstance();
        $manager = $userManager->getUserDetails($manager);
        if (!$manager || ($manager["userType"] !== "MANAGER" && $manager["userType"] !== "ADMIN"))
            return false;
        return true;
    }
}



