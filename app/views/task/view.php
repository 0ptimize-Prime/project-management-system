<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Task</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
    <meta name="taskId" content="<?php echo htmlspecialchars($data['task']['id']) ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>css/task-view.css?<?php echo time(); ?> "/>
</head>

<body>

<?php
showNavbar($data);
?>

<h1 class="text-center">Task view</h1>

<div class="d-flex">
    <div class="card mx-auto" style="width: 18rem;">
        <div class="card-body" >
            <h5 class="card-title">Task: <?php echo htmlspecialchars($data["task"]["title"]) ?></h5>
            <p class="card-text">Description: <?php echo htmlspecialchars($data["task"]["description"]) ?></p>
            <?php if ($data["task"]["username"]) { ?>
                <h6 class="card-subtitle mb-2 text-muted">
                    Assigned By : <?php echo htmlspecialchars($data["task"]["name"]) ?></h6>
            <?php } ?>
            <h6 class="card-subtitle mb-2 text-muted">
                Project: <a href="<?php echo htmlspecialchars(BASE_URL . 'project/view/' .$data["project"]["id"])  ?>"><?php echo htmlspecialchars($data["project"]["title"]) ?></a>
            <h6 class="card-subtitle mb-2 text-muted">
                Created at : <?php echo htmlspecialchars($data['task']['created_at']) ?></h6>
            <h6 class="card-subtitle mb-2 text-muted">
                Deadline : <?php echo htmlspecialchars($data['task']['deadline']) ?></h6>
            <h6 class="card-subtitle mb-2 text-muted">
                Current Status : <?php echo htmlspecialchars($data['task']['status']) ?></h6>

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
                <table class="accept-decline-table">
                    <tr>
                        <form class="updateStatus" id="status-accept">
                            <?php if ($data["user"]["userType"] != "EMPLOYEE") { ?>
                                <label for="up-status">Status: </label>
                                <button type="submit" class="btn btn-primary">Accept</button>
                            <?php } ?>
                        </form>
                    </tr>
                    <tr>
                        <form class="updateStatus" id="status-decline">
                            <?php if ($data["user"]["userType"] != "EMPLOYEE") { ?>
                                <button type="submit" class="btn btn-primary">Decline</button>
                            <?php } ?>
                        </form>
                    </tr>
                </table>

                <form class="updateStatus" id="status-request">
                    <?php if ($data["user"]["userType"] == "EMPLOYEE") { ?>
                        <label for="up-status">Status: </label>
                        <button type="submit" class="btn btn-primary">Submit for Approval</button>
                    <?php } ?>
                </form>
        </div>
    </div>
</div>

<div class="container overflow-scroll" id="comments">
    <div class="header"> <h1>Comments</h1></div>
    <?php foreach ($data["comments"] as $comment) { ?>
        <div class="card my-3" id="comment-<?php echo htmlspecialchars($comment['id']) ?>">
            <h5 class="card-header"><?php echo htmlspecialchars($comment["name"]) ?></h5>
            <div class="row g-0">
                <div class="col-md-1">
                    <?php if (!$comment['profile_picture']) $comment['profile_picture'] = "https://via.placeholder.com/100" ?>
                    <img src="<?php echo htmlspecialchars($comment['profile_picture']) ?>"
                         alt="<?php echo htmlspecialchars($comment['username']) ?>" class="img-fluid img-circle m-1">
                </div>
                <div class="col-md-11">
                    <div class="card-body">
                        <p class="card-text"><?php echo htmlspecialchars($comment["body"]) ?></p>
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

<div class="container">
    <form action="../../comment/task/<?php echo htmlspecialchars($data['task']['id']) ?>" id="comment-form">
        <div class="mb-3">
            <textarea name="body" id="body" cols="30" rows="3" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Comment</button>
    </form>
</div>

<style>
    #comments {
        max-height: 80%;
    }

    .card-text {
        white-space: pre-wrap;
    }

    .img-circle {
        border-radius: 50%;
    }
</style>

<script src="<?php echo htmlspecialchars(BASE_URL . 'js/task-view.js') ?>"></script>

</body>

</html>
