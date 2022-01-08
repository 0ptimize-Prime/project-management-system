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

        .table th {
            background-color: dodgerblue;
            color: white;
        }
    </style>
</head>

<body>

<?php
showNavbar($data);
?>

<h1 class="text-center">Project view</h1>

<div class="d-flex pt-3">
    <div class="card mx-auto" style="width: 40rem;">
        <div class="card-body">
            <h5 class="card-title text-center">Project: <?php echo htmlspecialchars($data["project"]["title"]) ?></h5>
            <div class="px-3 pt-2">
                <div class="row mb-3">
                    <div class="col-3">Description:</div>
                    <div class="col-9 card-text"><?php echo htmlspecialchars($data["project"]["description"]) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">Manager:</div>
                    <div class="col-4"><?php echo htmlspecialchars($data["project"]["managerName"]) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">Created at:</div>
                    <div class="col-4"><?php echo htmlspecialchars($data["project"]["created_at"]) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">Deadline:</div>
                    <div class="col-4">
                        <?php echo htmlspecialchars($data["project"]["deadline"] ?? "None") ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">Status:</div>
                    <div class="col-2">
                        <span class="badge rounded-pill bg-primary">
                            <?php echo htmlspecialchars($data["project"]["status"]) ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">Files:</div>
                    <?php if (count($data["files"]) == 0) { ?>
                        <div class="col-9">No files</div>
                    <?php } ?>
                    <ul class="list-group col-9">
                        <?php foreach ($data["files"] as $file) { ?>
                            <li class="list-group-item">
                                <a href="<?php echo htmlspecialchars(BASE_URL . 'uploads/' . $file['id']) ?>">
                                    <?php echo htmlspecialchars($file["name"]) ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
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

<div class="row">
    <div class="col-sm-8 offset-sm-4">
        <div class="row mt-4">
            <div class="col-2">
                <form action="<?php echo htmlspecialchars(BASE_URL . 'task/create/' . $data["project"]["id"]) ?>">
                    <button class="btn btn-primary" type="submit">Add Task</button>
                </form>
            </div>
            <div class="col-3">
                <button type="submit" name="newmilestone" class="btn btn-primary">Add Milestone</button>
            </div>
            <div class="col-3">
                <button type="submit" name="removemilestone" class="btn btn-danger">Remove Milestone</button>
            </div>
        </div>
    </div>
</div>

</body>

</html>
