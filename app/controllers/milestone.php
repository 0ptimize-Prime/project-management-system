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
            $this->checkAuth("milestone/create", function (){});
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
        if (!$result)
            http_response_code(400);
    }

    public function remove()
    {
    }
}