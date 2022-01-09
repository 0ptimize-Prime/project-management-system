<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
</head>

<body>

<?php
showNavbar($data);
showSidebar($data);
?>


<main style="margin-top: 58px">
    <div class="container py-4">
        <div class="row">
        <?php if ($data["user"]["userType"] != "EMPLOYEE") { ?>
            <div class="col">
                <div class="card mb-3" style="width: 18rem;" id="project-statistics">
                    <?php if(isset($data['projectsGraph']) && count($data['projectsGraph'])>0) { ?>
                    <canvas id="project-bar-chart" height="300"></canvas>
                    <?php } else { ?>
                    <img src="https://via.placeholder.com/800x500.png?text=Project+Statistics+Graph"
                            class="card-img-top"
                            alt="...">
                    <?php } ?>
                    <div class="card-body text-center">
                        <h5 class="card-title">Project Statistics</h5>
                    </div>
                </div>
            </div>
        <?php } ?>

            <div class="col">    
                <div class="card" style="width: 18rem;" id="user-statistics">
                    <?php if(isset($data['tasksGraph']) && count($data['tasksGraph'])>0) { ?>
                        <canvas id="task-pie-chart"></canvas>
                    <?php } else { ?>
                        <img src="https://via.placeholder.com/800x500.png?text=Task+Statistics" class="card-img-top"
                         alt="...">
                    <?php } ?>
                    <div class="card-body text-center">
                        <h5 class="card-title">Task Statistics</h5>
                    </div>
                </div>
            </div>
            <?php if ($data["user"]["userType"] != "EMPLOYEE") { ?>
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Projects</h5>
                        </div>
                        <div class="list-group list-group-flush overflow-auto" style="max-height: 200px;">
                            <?php foreach ($data["projects"] ?? [] as $project) { ?>
                                <a class="list-group-item list-group-item-action"
                                   href="<?php echo htmlspecialchars(BASE_URL . 'project/view/' . $project['id']) ?>">
                                    <?php echo htmlspecialchars($project['title']) ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
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
</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
    let colorPallet=["#84DFFF", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"]

    <?php if ($data["user"]["userType"] != "EMPLOYEE") { ?>
    new Chart(document.getElementById("project-bar-chart"), {
    type: 'bar',
    data: {
      labels: [
        <?php
            foreach ($data['projectsGraph'] as $projectId=>$array) {
                if ($array[2]!=0) {
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
            foreach ($data['projectsGraph'] as $projectId=>$array) {
                if ($array[2]!=0) {
                    $result=$array[1]/$array[2];
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
            callback: function(value, index, values) {
              return `${value*100}%`;
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
            foreach ($data['tasksGraph'] as $taskStatus=>$count) {
                echo "'$taskStatus',";
            }
          ?>
      ],
      datasets: [{
        label: "Task Status",
        backgroundColor: colorPallet,
        data: [
            <?php
            foreach ($data['tasksGraph'] as $taskStatus=>$count) {
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
