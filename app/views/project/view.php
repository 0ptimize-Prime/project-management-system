<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Project</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
    <style>
        .card-text {
            white-space: pre-wrap;
        }

        .table {
            margin: auto;
            width: 50%;
        }
    </style>
</head>

<body>

<?php
includeWithVariables(__DIR__ . "/../templates/navbar.php", array("isLoggedIn" => true, "user" => $data["user"]));
?>

<?php 
    $graph=[];
    $totalEffort=0;
    foreach ($data["tasks"] as $key=>$task) {
        if ($task["status"]=="COMPLETE") {
            if ($task["deadline"]) {
                $graph[$task["id"]]=array($task["deadline"], $task["effort"]);
            } else {
                $graph[$task["id"]]=array(date("Y-m-d"), $task["effort"]);
            }
        }
        $totalEffort+=$task["effort"];
    }

    function compare_func($a, $b)
    {
        // CONVERT $a AND $b to DATE AND TIME using strtotime() function
        $t1 = strtotime($a[0]);
        $t2 = strtotime($b[0]);

        return ($t1 - $t2);
    }

    usort($graph, "compare_func");
?>

<h1 class="text-center">Project view</h1>

<div class="d-flex">
    <div class="card mx-auto" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Project: <?php echo htmlspecialchars($data["project"]["title"]) ?></h5>
            <p class="card-text">Description: <?php echo htmlspecialchars($data["project"]["description"]) ?></p>
            <h6 class="card-subtitle mb-2 text-muted">
                Manager: <?php echo htmlspecialchars($data["project"]["manager"]) ?></h6>
            <h6 class="card-subtitle mb-2 text-muted">
                Status: <?php echo htmlspecialchars($data["project"]["status"]) ?></h6>
            <ul class="list-group list-group-flush">
                <?php foreach ($data["files"] as $file) { ?>
                    <li class="list-group-item">
                        <a href="<?php echo BASE_URL . "uploads/" . htmlspecialchars($file["id"]) ?>">
                            <?php echo htmlspecialchars($file["name"]) ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<table class="table">
    <thead>
    <tr>
        <th scope="col">Title</th>
        <th scope="col">Deadline</th>
        <th scope="col">Status</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data['tasks'] as $task) { ?>
        <tr>
            <td>
                <a href="<?php echo htmlspecialchars(BASE_URL . 'task/view/' . $task['id']) ?>">
                    <?php echo htmlspecialchars($task['title']) ?>
                </a>
            </td>
            <td><?php echo htmlspecialchars($task['deadline']) ?></td>
            <td><?php echo htmlspecialchars($task['status']) ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-10" id="project-progress" style="height: 27rem;">
            <?php if(isset($graph) && count($graph)>0) { ?>
                <canvas id="project-progress-chart" style="width:100%;"></canvas>
            <?php } else { ?>
                <img src="https://via.placeholder.com/800x500.png?text=Project+Progress+Graph"
                        class="card-img-top"
                        alt="...">
            <?php } ?>
            <div class="card-body text-center">
                <h5 class="card-title">Project Progress</h5>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@next/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script>
    <?php 
        $startingEffort=0;
        $projectTitle = $data['project']['title'];
        $projectStart = $data['project']['created_at'];
    ?>
    new Chart(document.getElementById("project-progress-chart"), {
        type: 'line',
        data: {
            labels: [
                <?php echo "'$projectStart',";
                foreach ($graph as $key=>$elm) {
                    echo "'$elm[0]',";
                } ?>
            ],
            datasets: [{
                data: [
                    <?php echo "'0',";
                    foreach ($graph as $key=>$elm) {
                        $startingEffort+=$elm[1];
                        $effortScale=$startingEffort/$totalEffort;
                        echo "'$effortScale',";
                    } ?>
                ],
                label:  <?php echo "'$projectTitle'"; ?>,
                borderColor: "#7900FF",
                backgroundColor: "#548CFF",
                fill: false
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day' 
                    }
                }
            },
            title: {
            display: true,
            text: 'Project progress scale',
            }
        }
    });
</script>

</body>

</html>
