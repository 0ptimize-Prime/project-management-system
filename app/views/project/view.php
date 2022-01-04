<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Project</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
    <link rel="stylesheet" href="<?php echo BASE_URL ?>css/project-view.css?<?php echo time(); ?>"</>
    <style>
        .card-text {
            white-space: pre-wrap;
        }

        .table {
            margin: auto;
            width: 50%;
        }
        .table th {
            background-color: dodgerblue;
            color: white;
        }

    </style>
</head>

<body>

<?php
includeWithVariables(__DIR__ . "/../templates/navbar.php", array("isLoggedIn" => true, "user" => $data["user"]));
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
                Created at : <?php echo htmlspecialchars($data['project']['created_at']) ?></h6>
            <h6 class="card-subtitle mb-2 text-muted">
                Deadline : <?php echo htmlspecialchars($data['project']['deadline']) ?></h6>
            <h6 class="card-subtitle mb-2 text-muted">
                Status: <?php echo htmlspecialchars($data["project"]["status"]) ?></h6>
            <ul class="list-group list-group-flush">
                <?php foreach ($data["files"] as $file) { ?>
                    <h6 class="card-subtitle mb-2 text-muted">
                        Files:</h6>
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
<div class="row">
    <div class="col-sm-8 offset-sm-4">
        <div class="row mt-4">
            <div class="col-2">
                <form action="<?php echo htmlspecialchars(BASE_URL . 'task/create/' .$data["project"]["id"]) ?>">
                    <button class = "newtask" type="submit">Add Task</button>
                </form>
            </div>
            <div class="col-3">
                <button type="submit" name="newmilestone" class="milestone">Add Milestone</button>
            </div>
            <div class="col-3">
                <button type="submit" name="removemilestone" class="milestone">Remove Milestone</button>
            </div>
            <div class="col-1">
                <button class="top" onclick="topFunction()" id="myBtn" title="Go to top">Up</button>
            </div>
        </div>
    </div>
</div>
<script>
    mybutton = document.getElementById("myBtn");
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>

</body>

</html>
