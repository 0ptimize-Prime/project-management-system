<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
    <style>
        .placeholder-img {
            text-anchor: middle;
            user-select: none;
        }
    </style>
</head>

<body>

<?php
showNavbar($data);
showSidebar($data);
?>

<main style="margin-top: 58px">
    <div class="container py-4">
        <div class="row mb-5">
            <?php if ($data["user"]["userType"] != "EMPLOYEE") { ?>
                <div class="col offset-1">
                    <div class="card mb-3" style="width: 18rem;" id="project-statistics">
                        <?php if (isset($data['projectsGraph']) && count($data['projectsGraph']) > 0) { ?>
                            <canvas id="project-bar-chart" height="300"></canvas>
                        <?php } else { ?>
                            <svg class="card-img-top placeholder-img" width="100%" height="180" role="img"
                                 aria-label="Placeholder: No projects yet" preserveAspectRatio="xMidYMid slice"
                                 focusable="false">
                                <title>Placeholder</title>
                                <rect width="100%" height="100%" fill="#868e96"></rect>
                                <text x="50%" y="50%" fill="#dee2e6" dy=".3em">No projects yet</text>
                            </svg>
                        <?php } ?>
                        <div class="card-body text-center">
                            <h5 class="card-title">Project Statistics</h5>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="col <?php echo htmlspecialchars($data['user']['userType'] == "EMPLOYEE" ? 'offset-4' : '') ?>">
                <div class="card" style="width: 18rem;" id="user-statistics">
                    <?php if (isset($data['tasksGraph']) && count($data['tasksGraph']) > 0) { ?>
                        <canvas id="task-pie-chart"></canvas>
                    <?php } else { ?>
                        <svg class="card-img-top placeholder-img" width="100%" height="180" role="img"
                             aria-label="Placeholder: No tasks yet" preserveAspectRatio="xMidYMid slice"
                             focusable="false">
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="#868e96"></rect>
                            <text x="50%" y="50%" fill="#dee2e6" dy=".3em">No tasks yet</text>
                        </svg>
                    <?php } ?>
                    <div class="card-body text-center">
                        <h5 class="card-title">Task Statistics</h5>
                    </div>
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <?php if ($data["user"]["userType"] == "EMPLOYEE") { ?>
                    <th scope="col">Title</th>
                    <th scope="col">Project</th>
                    <th scope="col">Deadline</th>
                    <th scope="col">Status</th>
                <?php } else { ?>
                    <th scope="col">Title</th>
                    <th scope="col">Deadline</th>
                    <th scope="col">Status</th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php if ($data["user"]["userType"] == "EMPLOYEE") { ?>
                <?php foreach ($data['tasks'] as $task) { ?>
                    <tr>
                        <td>
                            <a href="<?php echo htmlspecialchars(BASE_URL . 'task/view/' . $task['id']) ?>">
                                <?php echo htmlspecialchars($task['title']) ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo htmlspecialchars(BASE_URL . 'project/view/' . $task['projectId']) ?>">
                                <?php echo htmlspecialchars($task['projectName']) ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($task['deadline']) ?></td>
                        <td>
                            <span class="badge rounded-pill bg-<?php echo htmlspecialchars(statusBadgeColor($task['status'])) ?>">
                                <?php echo htmlspecialchars($task["status"]) ?>
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <?php foreach ($data["projects"] as $project) { ?>
                    <tr>
                        <td>
                            <a href="<?php echo htmlspecialchars(BASE_URL . 'project/view/' . $project['id']) ?>">
                                <?php echo htmlspecialchars($project["title"]) ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($project["deadline"]) ?></td>
                        <td>
                            <span class="badge rounded-pill bg-<?php echo htmlspecialchars(statusBadgeColor($project['status'])) ?>">
                                <?php echo htmlspecialchars($project["status"]) ?>
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
    let colorPallet = ["#84DFFF", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"]

    <?php if ($data["user"]["userType"] != "EMPLOYEE") { ?>
    new Chart(document.getElementById("project-bar-chart"), {
        type: 'bar',
        data: {
            labels: [
                <?php
                foreach ($data['projectsGraph'] as $projectId => $array) {
                    if ($array[2] != 0) {
                        echo "'$array[0]',";
                    }
                }
                ?>
            ],
            datasets: [
                {
                    label: "project statics",
                    backgroundColor: colorPallet,
                    data: [
                        <?php
                        foreach ($data['projectsGraph'] as $projectId => $array) {
                            if ($array[2] != 0) {
                                $result = $array[1] / $array[2];
                                echo "'$result',";
                            }
                        }
                        ?>
                    ]
                }
            ]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            title: {
                display: true,
                text: 'Statistics of all Projects'
            },
            indexAxis: 'y',
            scales: {
                x: {
                    max: 1,
                    ticks: {
                        callback: function (value, index, values) {
                            return `${value * 100}%`;
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                },
            },
        }
    });
    <?php } ?>
    new Chart(document.getElementById("task-pie-chart"), {
        type: 'pie',
        data: {
            labels: [
                <?php
                foreach ($data['tasksGraph'] as $taskStatus => $count) {
                    echo "'$taskStatus',";
                }
                ?>
            ],
            datasets: [{
                label: "Task Status",
                backgroundColor: colorPallet,
                data: [
                    <?php
                    foreach ($data['tasksGraph'] as $taskStatus => $count) {
                        echo "$count,";
                    }
                    ?>
                ]
            }]
        },
        options: {
            title: {
                display: true,
                text: 'task Statics'
            }
        }

    });
</script>

</body>

</html>
