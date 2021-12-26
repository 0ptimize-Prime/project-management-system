<?php

require_once __DIR__ . "/../models/UserManager.php";
require_once __DIR__ . "/../models/TaskManager.php";

class Home extends Controller
{
    public function dashboard()
    {
        $this->checkAuth("home/dashboard", function () {
            $TaskManager = TaskManager::getInstance();
            $tasksGraph=[];
            $projectsGraph = [];
            
            if ($_SESSION["user"]["userType"] != "EMPLOYEE") {
                $taskStatuses = $TaskManager->getTasksByManager($_SESSION["user"]["username"]);

                if ($taskStatuses) {
                    foreach ($taskStatuses as $key=>$taskStatus) {
                        if (array_key_exists($taskStatus["status"], $tasksGraph)) {
                            $tasksGraph[$taskStatus["status"]]++;
                        } else {
                            $tasksGraph[$taskStatus["status"]]=1;
                        }

                        if (array_key_exists($taskStatus["project_id"], $projectsGraph)) {
                            $taskStatus["status"]=="COMPLETE"?$projectsGraph[$taskStatus["project_id"]][1]++: 0;
                            $projectsGraph[$taskStatus["project_id"]][2]++;
                        } else {
                            // project_id => (title, non complete task count, total task count)
                            $projectsGraph[$taskStatus["project_id"]]=array(
                                $taskStatus["title"], 
                                $taskStatus["status"]=="COMPLETE"?1: 0, 
                                1
                            );
                        }                
                    }
                }
            } else {
                $taskStatuses = $TaskManager->getTaskStatusesByUser($_SESSION["user"]["username"]);
                if ($taskStatuses) {
                    foreach ($taskStatuses as $key=>$taskStatus) {
                        if (array_key_exists($taskStatus["status"], $tasksGraph)) {
                            $tasksGraph[$taskStatus["status"]]++;
                        } else {
                            $tasksGraph[$taskStatus["status"]]=1;
                        }
                    }
                }
            }

            return [
                'user' => $_SESSION["user"], 
                'tasks' => [], 
                'tasksGraph' => $tasksGraph,
                'projectsGraph' => $projectsGraph
            ];
        });
    }
}
