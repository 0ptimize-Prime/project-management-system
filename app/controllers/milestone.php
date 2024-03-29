<?php
require_once __DIR__ . "/../models/MilestoneManager.php";
require_once __DIR__ . "/../models/ProjectManager.php";

class milestone extends Controller
{
    public function create()
    {
        $milestoneManager = MilestoneManager::getInstance();
        $projectManager = ProjectManager::getInstance();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->checkAuth("milestone/create", function () {
            });
        }

        if (!isset(
                $_POST["id"],
                $_POST["title"]
            ) ||
            empty($_POST["id"]) ||
            empty($_POST["title"])
        ) {
            http_response_code(400);
            die;
        }

        $project = $projectManager->getProject($_POST["id"]);
        if (!$project || $project["manager"] !== $_SESSION["user"]["username"]) {
            http_response_code(400);
            die;
        }

        $result = $milestoneManager->addMilestone($_POST["id"], $_POST["title"]);
        if (!$result) {
            http_response_code(400);
        } else {
            echo json_encode($milestoneManager->getMilestone($result));
        }
    }

    public function edit()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->checkAuth("milestone/edit", function () {
            });

            if (!isset($_POST["id"], $_POST["title"]) || empty($_POST["id"]) || empty($_POST["title"])) {
                http_response_code(400);
                die;
            }

            $milestoneManager = MilestoneManager::getInstance();
            $milestone = $milestoneManager->getMilestone($_POST["id"]);
            $projectManager = ProjectManager::getInstance();
            $project = $projectManager->getProject($milestone["project_id"]);
            if (!$project || $project["manager"] !== $_SESSION["user"]["username"]) {
                http_response_code(400);
                die;
            }

            $result = $milestoneManager->updateMilestone($_POST["id"], $_POST["title"]);
            if (!$result) {
                http_response_code(500);
            } else {
                echo json_encode($milestoneManager->getMilestone($_POST["id"]));
            }
        }
    }

    public function remove($id)
    {
        $milestoneManager = MilestoneManager::getInstance();
        $projectManager = ProjectManager::getInstance();
        if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
            $this->checkAuth("milestone/remove", function () {
            });
        }

        if (empty($id)) {
            http_response_code(400);
            die;
        }

        $milestone = $milestoneManager->getMilestone($id);
        if (!$milestone) {
            http_response_code(400);
            die;
        }
        $project = $projectManager->getProject($milestone["project_id"]);
        if (!$project || $project["manager"] !== $_SESSION["user"]["username"]) {
            http_response_code(400);
            die;
        }

        $result = $milestoneManager->deleteMilestone($id);
        if (!$result)
            http_response_code(400);
    }
}