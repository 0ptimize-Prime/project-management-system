<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
</head>

<body>

<?php
includeWithVariables(
    __DIR__ . "/../templates/navbar.php",
    array("isLoggedIn" => true, "user" => $data["user"], "notifications" => $data["notifications"])
);
?>

<?php
includeWithVariables(__DIR__ . "/../templates/sidebar.php", array("isAdmin" => $data["user"]["userType"] == "ADMIN"));
?>

<main style="margin-top: 58px">
    <div class="container py-4">
        <div class="row">
            <?php if ($data["user"]["userType"] != "EMPLOYEE") { ?>
                <div class="col">
                    <div class="card mb-3" style="width: 18rem;" id="project-statistics">
                        <img src="https://via.placeholder.com/800x500.png?text=Project+Statistics+Graph"
                             class="card-img-top"
                             alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Project Statistics</h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="col">
                <div class="card" style="width: 18rem;" id="user-statistics">
                    <img src="https://via.placeholder.com/800x500.png?text=Task+Statistics" class="card-img-top"
                         alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Task Statistics</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Projects</h5>
                    </div>
                    <ul class="list-group list-group-flush overflow-auto" style="max-height: 200px;">
                        <?php foreach ($data["projects"] ?? [] as $project) { ?>
                            <li class="list-group-item">
                                <a href="<?php echo htmlspecialchars(BASE_URL . 'project/view/' . $project['id']) ?>">
                                    <?php echo htmlspecialchars($project['title']) ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
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
</main>

</body>

</html>
