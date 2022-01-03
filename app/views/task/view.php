<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Task</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
    <meta name="taskId" content="<?php echo htmlspecialchars($data['task']['id']) ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>css/task-view.css "/>
</head>

<body>

<?php
includeWithVariables(__DIR__ . "/../templates/navbar.php", array("isLoggedIn" => true, "user" => $data["user"]));
?>

<h1 class="text-center">Task view</h1>

<div class="d-flex">
    <div class="card mx-auto" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Task: <?php echo htmlspecialchars($data["task"]["title"]) ?></h5>
            <p class="card-text">Description: <?php echo htmlspecialchars($data["task"]["description"]) ?></p>
            <?php if ($data["task"]["username"]) { ?>
                <h6 class="card-subtitle mb-2 text-muted">
                    Assigned By : <?php echo htmlspecialchars($data["task"]["name"]) ?></h6>
            <?php } ?>
            <h6 class="card-subtitle mb-2 text-muted">
                Project :<p><a href="<?php echo htmlspecialchars(BASE_URL . 'project/view/' .$data["project"]["id"])  ?>"><?php echo htmlspecialchars($data["project"]["title"]) ?></a></p>
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
                <label for="status">Status :</label>
                    <select class="form-select" id="status" name="status">
                        <option value="" selected disabled></option>
                        <option value="CREATED">Created</option>
                        <option value="ASSIGNED">Assigned</option>
                        <option value="IN_PROGRESS">In progress</option>
                        <option value="PENDING">Pending</option>
                        <option value="COMPLETE">Complete</option>
                    </select>
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

<script>
    const status=document.getElementById("status");
    const form = document.getElementById("comment-form");
    const commentsDiv = document.getElementById("comments");

    const taskId = document.querySelector("[name=taskId][content]").content;

    const addComment = (newComment) => {
        const mainNode = document.createElement("div");
        mainNode.classList.add("card", "my-3");
        mainNode.id = `comment-${newComment['id']}`;

        const headerNode = document.createElement("h5");
        headerNode.classList.add("card-header");
        headerNode.textContent = newComment.name;
        mainNode.appendChild(headerNode);

        const rowNode = document.createElement("div");
        rowNode.classList.add("row", "g-0");

        const colNode1 = document.createElement("div");
        colNode1.classList.add("col-md-1");
        if (!newComment.profile_picture) newComment.profile_picture = "https://via.placeholder.com/100";
        const imgNode = document.createElement("img");
        imgNode.src = newComment.profile_picture;
        imgNode.alt = newComment.username;
        imgNode.classList.add("img-fluid", "img-circle", "m-1")
        colNode1.appendChild(imgNode);
        rowNode.appendChild(colNode1);

        const colNode2 = document.createElement("div");
        colNode2.classList.add("col-md-11");

        const bodyNode = document.createElement("div");
        bodyNode.classList.add("card-body");

        const textNode = document.createElement("p");
        textNode.classList.add("card-text");
        textNode.textContent = newComment.body;
        bodyNode.appendChild(textNode);

        const dateTimeNode = document.createElement("h6");
        dateTimeNode.classList.add("card-subtitle", "mb-2", "initialism", "text-muted");

        const clockIconNode = document.createElement("i");
        clockIconNode.classList.add("fas", "fa-clock");
        dateTimeNode.appendChild(clockIconNode);

        const dateTimeSpanNode = document.createElement("span");
        dateTimeSpanNode.textContent = newComment.created_at;
        dateTimeNode.appendChild(dateTimeSpanNode);
        bodyNode.appendChild(dateTimeNode);
        colNode2.appendChild(bodyNode);

        rowNode.appendChild(colNode2);
        mainNode.appendChild(rowNode);

        commentsDiv.appendChild(mainNode);
    };

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const xhttp = new XMLHttpRequest();
        xhttp.withCredentials = true;
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                form.reset();
                addComment(JSON.parse(this.response));
            }
        };
        xhttp.open("POST", "../../comment/task/" + taskId, true);
        xhttp.send(new FormData(form));
    });
    status.addEventListener("click", (e) => {
        e.preventDefault();

        const xhttp = new XMLHttpRequest();
        xhttp.withCredentials = true;
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                //form.reset();
            }
        };
        xhttp.open("POST", BASE_URL + "task/edit/" + taskId, true);
        xhttp.send(new FormData(status));
    });
</script>

</body>

</html>
