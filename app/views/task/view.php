<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Task</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
    <meta name="taskId" content="<?php echo htmlspecialchars($data['task']['id']) ?>">
    <style>
        .container {
            padding-top: 10px;
            margin-top: 20px;
            width: 1000px;
        }

        .col-md-11 .card-subtitle {
            position: absolute;
            right: 1px;
        }

        #comments img {
            object-fit: cover;
            height: 80px !important;
            width: 80px !important;
        }

        #comments {
            max-height: 80vh;
        }

        .card-text {
            white-space: pre-wrap;
        }

        .img-circle {
            border-radius: 50%;
        }

        form {
            display: inline;
        }

        #edit-button {
            top: 15px;
            right: 15px;
        }
    </style>
</head>

<body>

<?php
showNavbar($data);
?>

<h1 class="text-center">Task view</h1>

<div class="d-flex pt-3">
    <div class="card mx-auto" style="width: 40rem;">
        <div class="card-body">
            <h5 class="card-title text-center">Task: <?php echo htmlspecialchars($data["task"]["title"]) ?></h5>
            <?php if ($data["user"]["userType"] != "EMPLOYEE") { ?>
                <a href="<?php echo htmlspecialchars(BASE_URL . 'task/edit/' . $data['task']['id']) ?>"
                   class="btn btn-warning position-absolute" id="edit-button"><i class="fas fa-edit"></i></a>
            <?php } ?>
            <div class="px-3 pt-2">
                <div class="row mb-3">
                    <div class="col-3">Description:</div>
                    <div class="col-9 card-text"><?php echo htmlspecialchars($data["task"]["description"]) ?></div>
                </div>
                <div class="row mb-3">
                    <?php if ($data["task"]["username"]) { ?>
                        <div class="col-3">Assigned To:</div>
                        <div class="col-4"><?php echo htmlspecialchars($data["task"]["employeeName"]) ?></div>
                    <?php } ?>
                </div>
                <div class="row mb-3">
                    <div class="col-3">Project:</div>
                    <div class="col-4">
                        <a href="<?php echo htmlspecialchars(BASE_URL . 'project/view/' . $data["project"]["id"]) ?>">
                            <?php echo htmlspecialchars($data["project"]["title"]) ?>
                        </a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">Created at:</div>
                    <div class="col-4"><?php echo htmlspecialchars($data['task']['created_at']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">Deadline:</div>
                    <div class="col-4"><?php echo htmlspecialchars($data['task']['deadline']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">Current Status:</div>
                    <div class="col-2">
                        <span class="badge rounded-pill bg-<?php echo htmlspecialchars(statusBadgeColor($data['task']['status'])) ?>">
                            <?php echo htmlspecialchars($data["task"]["status"]) ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">Files:</div>
                    <?php if (count($data["files"]) == 0) { ?>
                        <div class="col-4">No files</div>
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
                <?php if ($data["user"]["userType"] != "EMPLOYEE") { ?>
                    <?php if ($data["task"]["status"] != "COMPLETE") { ?>
                        <div class="row mb-3">
                            <div class="col-3">Status:</div>
                            <div class="col-2">
                                <button type="button" class="btn btn-success" id="status-accept">Accept</button>
                            </div>
                            <?php if ($data["task"]["status"] == "PENDING") { ?>
                                <div class="col-2">
                                    <button type="button" class="btn btn-danger" id="status-decline">Decline</button>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } else if ($data["task"]["status"] == "IN_PROGRESS") { ?>
                    <div class="row mb-3">
                        <div class="col-3">Status:</div>
                        <div class="col-4">
                            <button type="button" class="btn btn-primary" id="status-request">Submit for Approval
                            </button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="container overflow-scroll" id="comments">
    <h1>Comments</h1>
    <?php foreach ($data["comments"] as $comment) { ?>
        <div class="card my-3" id="comment-<?php echo htmlspecialchars($comment['id']) ?>">
            <h5 class="card-header"><?php echo htmlspecialchars($comment["name"]) ?></h5>
            <div class="row g-0">
                <div class="col-md-1">
                    <img src="<?php echo htmlspecialchars($comment['profile_picture'] ? BASE_URL . "uploads/" . $comment["profile_picture"] : 'https://via.placeholder.com/40x40.png') ?>"
                         alt="<?php echo htmlspecialchars($comment['username']) ?>" class="img-fluid img-circle m-1">
                </div>
                <div class="col-md-11">
                    <div class="card-body">
                        <p class="card-text"><?php echo htmlspecialchars($comment["body"]) ?></p>
                        <?php if (isset($comment["file_id"]) && strlen($comment["file_id"]) > 0) { ?>
                            <a href="<?php echo htmlspecialchars(BASE_URL . 'uploads/' . $comment["file_id"]) ?>">
                                <?php echo htmlspecialchars($comment["file_name"]) ?>
                            </a>
                        <?php } ?>
                        <h6 class="card-subtitle mb-2 text-muted initialism">
                            <i class="fas fa-clock"></i>
                            <span><?php echo htmlspecialchars($comment["created_at"]) ?></span>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="container mb-5">
    <form action="../../comment/task/<?php echo htmlspecialchars($data['task']['id']) ?>" id="comment-form">
        <div class="row mb-3">
            <textarea name="body" id="body" cols="30" rows="3" class="form-control" required></textarea>
        </div>
        <div class="form-group row mb-3">
            <label class="col-sm-2 col-form-label" for="file">Attachment:</label>
            <div class="col-sm-4">
                <input type="file" name="file" class="form-control">
            </div>
            <div class="col-sm-1 offset-sm-4">
                <button id="submit-comment" type="submit" class="btn btn-primary">Comment</button>
            </div>
        </div>
    </form>
</div>

<script src="<?php echo htmlspecialchars(BASE_URL . 'js/task-view.js') ?>"></script>

</body>

</html>
