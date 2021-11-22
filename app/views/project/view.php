<?php require_once __DIR__ . "/../../utils.php" ?>

<html>

<?php includeWithVariables(__DIR__ . "/../templates/header.php",
    array('title' => 'Project',
        'isLoggedIn' => $_SESSION["user"])) ?>

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

<style>
    .card-text {
        white-space: pre-wrap;
    }

    .table {
        margin: auto;
        width: 50%;
    }
</style>

</body>

</html>
