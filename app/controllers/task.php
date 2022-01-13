<?php

require_once __DIR__ . "/../utils.php";
require_once __DIR__ . "/../models/UserManager.php";
require_once __DIR__ . "/../models/ProjectManager.php";
require_once __DIR__ . "/../models/TaskManager.php";
require_once __DIR__ . "/../models/FileManager.php";
require_once __DIR__ . "/../models/CommentManager.php";

class Task extends Controller
{
    public function create(string $projectId)
    {
        session_start();
        $fileManager = FileManager::getInstance();
        $project = $this->check_project($projectId);
        if (!$project || $_SESSION["user"]["username"] != $project["manager"]) {
            header("Location: " . BASE_URL . "home/dashboard");
            die;
        }
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->checkAuth("task/create", function (string $projectId, string $projectTitle) {
                $data = $this->getViewData();
                $data["projectId"] = $projectId;
                $data["projectTitle"] = $projectTitle;
                return $data;
            }, [$projectId, $project["title"]]);
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            $this->checkAuth("task/create", function () {
            });

            $check_user = $this->check_user($_POST["username"]);
            if ($check_user) {
                $taskManager = TaskManager::getInstance();
                $result = $taskManager->addTask(
                    $_POST["projectId"],
                    $_POST["title"],
                    $_POST["description"],
                    $_POST["username"],
                    $_POST["deadline"],
                    $_POST["effort"],
                );

                if ($result && $_FILES["file"]["tmp_name"]) {
                    $file = $_FILES["file"]["name"];
                    $file_loc = $_FILES["file"]["tmp_name"];
                    $folder = __DIR__ . '/../../public/uploads/';
                    $final_file = $fileManager->addFile($result, $file);
                    if ($final_file)
                        move_uploaded_file($file_loc, $folder . $final_file);
                }

                if ($result) {
                    FlashMessage::create_flash_message(
                        "create-task",
                        "Task `" . $_POST["title"] . "` created successfully.",
                        new SuccessFlashMessage()
                    );
                } else {
                    FlashMessage::create_flash_message(
                        "create-task",
                        "Something went wrong, couldn't create task.",
                        new ErrorFlashMessage()
                    );
                }
            } else {
                FlashMessage::create_flash_message(
                    "create-task",
                    "Invalid request to create task.",
                    new ErrorFlashMessage()
                );
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
                $data = $this->getViewData();

                $taskManager = TaskManager::getInstance();
                $task = $taskManager->getTask($taskId);
                if ($data["user"]["username"] == $task["username"] &&
                    $task["status"] == "ASSIGNED") {
                    $taskManager->updateStatus($taskId, "IN_PROGRESS");
                    $task = $taskManager->getTask($taskId);
                }
                $data["task"] = $task;

                $projectManager = ProjectManager::getInstance();
                $project = $projectManager->getProject($task["project_id"]);
                $data["project"] = $project;
                if ($data["user"]["username"] != $project["manager"]
                    && !$taskManager->isEmployeeInProject($data["user"]["username"], $project["id"])) {
                    header("Location: " . BASE_URL . "home/dashboard");
                    die;
                }

                $fileManager = FileManager::getInstance();
                $files = $fileManager->getFiles($taskId);
                $data["files"] = $files;

                $commentManager = CommentManager::getInstance();
                $comments = $commentManager->getComments($taskId);
                $data["comments"] = $comments;

                return $data;
            }, [$taskId]);
        }
    }

    public function edit(string $id = null)
    {
        $userManager = UserManager::getInstance();
        $taskManager = TaskManager::getInstance();
        $projectManager = ProjectManager::getInstance();
        $fileManager = FileManager::getInstance();
        $commentManager = CommentManager::getInstance();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $employees = $userManager->getUsersBy('', '', 'EMPLOYEE') ?? [];
            $this->checkAuth("task/edit", function ($employees) {
                $data = $this->getViewData();
                $data["employees"] = $employees;
                return $data;
            }, [$employees]);
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->checkAuth("task/edit", function () {
            });

            if (!isset(
                $_POST["id"],
                $_POST["title"],
                $_POST["username"],
                $_POST["description"],
                $_POST["deadline"],
                $_POST["status"],
                $_POST["effort"]
            )) {
                http_response_code(400);
                die;
            }

            if (!$this->validate_update_task_data(
                $_POST["id"],
                $_POST["title"],
                $_POST["username"],
                $_POST["description"],
                $_POST["deadline"],
                $_POST["status"],
                $_POST["effort"]
            )) {
                http_response_code(400);
                die;
            }

            $result = $taskManager->updateTask(
                $_POST["id"],
                $_POST["title"],
                $_POST["description"],
                $_POST["username"],
                $_POST["deadline"],
                $_POST["effort"]
            );

            if ($result) {
                $response = $taskManager->getTask($_POST["id"]);
                echo json_encode($response);
            } else
                http_response_code(400);
        } else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
            $this->checkAuth("task/edit", function () {});

            if (empty($id)) {
                http_response_code(400);
                die;
            }

            $task = $taskManager->getTask($id);
            if ($task &&
                $_SESSION["user"]["username"] === $projectManager->getProject($task["project_id"])["manager"]) {
                $db = DbConnectionManager::getConnection();
                $db->beginTransaction();

                try {
                    // Delete file associated with the task
                    $files = $fileManager->getFiles($task["id"]);
                    if ($files && file_exists(__DIR__ . '/../../public/uploads/' . $files[0]["id"])) {
                        unlink(__DIR__ . '/../../public/uploads/' . $files[0]["id"]);
                        $fileManager->deleteFile($files[0]["id"]);
                    }

                    // Delete comments associated with the task
                    $comments = $commentManager->getComments($task["id"]);
                    if ($comments) {
                        foreach($comments as $comment) {
                            $files = $fileManager->getFiles($comment["id"]);
                            if ($files && file_exists(__DIR__ . '/../../public/uploads/' . $files[0]["id"])) {
                                unlink(__DIR__ . '/../../public/uploads/' . $files[0]["id"]);
                                $fileManager->deleteFile($files[0]["id"]);
                            }
                        }
                    }
                    $commentManager->deleteCommentsByTaskId($task["id"]);


                    $result = $taskManager->deleteTask($id);
                    if (!$result) {
                        http_response_code(400);
                    }
                    } catch (Exception $e) {
                        if ($db->inTransaction())
                            $db->rollBack();
                        http_response_code(400);
                        die;
                    }
                    $db->commit();
            } else
                http_response_code(400);
        }

    }

    public function search()
    {
        $taskManager = TaskManager::getInstance();
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $this->checkAuth("task/edit", function () {
                return false;
            });
            if (!isset($_GET["project-title"], $_GET["task-title"])) {
                http_response_code(400);
                die;
            }
            $result = $taskManager->getTasksBy($_SESSION["user"]["username"], $_GET["project-title"], $_GET["task-title"]);
            if ($result)
                echo json_encode($result);
        }
    }

    private function check_user(string $username): bool
    {
        if (strlen($username) < 1) {
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

    private function validate_update_task_data($id, $title, $username, $description, $deadline, $status, $effort): bool
    {
        $userManager = UserManager::getInstance();
        $args = func_get_args();
        foreach ($args as $arg) {
            if (strlen($arg) < 1)
                return false;
        }

        // Check if the task id valid
        if (!$this->is_task_valid($id))
            return false;

        // Check if the username is valid and is an employee
        $user = $userManager->getUserDetails($username);
        if (!$user || $user["userType"] !== "EMPLOYEE")
            return false;

        // Check if the status is valid
        if (!$this->is_valid_status($status))
            return false;

        return true;
    }

    private function is_task_valid($id): bool
    {
        $taskManager = TaskManager::getInstance();
        $result = $taskManager->getTask($id);
        if (!$result)
            return false;
        return true;
    }

    private function is_valid_status(string $status): bool
    {
        $status_types = array(
            'CREATED',
            'ASSIGNED',
            'IN_PROGRESS',
            'PENDING',
            'COMPLETE'
        );
        return in_array($status, $status_types);
    }

    public function status(string $taskID)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->checkAuth("task/status", function () {
            });
            $check_user = $this->check_user($_SESSION["user"]["username"]);

            if ($check_user) {
                $taskManager = TaskManager::getInstance();
                $result = $taskManager->updateStatus(
                    $taskID, $_POST["status"]
                );
                if ($result) {
                    FlashMessage::create_flash_message(
                        "update-status",
                        "Status `" . $_POST["status"] . "` updated successfully.",
                        new SuccessFlashMessage()
                    );
                } else {
                    FlashMessage::create_flash_message(
                        "update-status",
                        "Something went wrong, couldn't update status.",
                        new ErrorFlashMessage()
                    );
                }

            } else {
                FlashMessage::create_flash_message(
                    "update-status",
                    "Invalid request to update task.",
                    new ErrorFlashMessage()
                );
            }
            die;
        }
    }


}
