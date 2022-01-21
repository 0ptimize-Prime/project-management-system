<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create Task</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
</head>

<body>

<?php
showNavbar($data);
showSidebar($data);
?>

<main style="margin-top: 80px">
    <h1 class="text-center">Create Task</h1>
    <h2 class="text-center">Project: <?php echo htmlspecialchars($data["projectTitle"]); ?></h2>

    <?php FlashMessage::display_flash_message("create-task") ?>

    <div class="d-flex justify-content-center mt-5">
        <form action="<?php echo htmlspecialchars(BASE_URL . 'task/create/' . $data['projectId']) ?>" method="post"
              enctype="multipart/form-data">
            <input id="projectId" name="projectId" value="<?php echo htmlspecialchars($data["projectId"]); ?>" hidden>

            <div class="form-group row mb-3">
                <label for="title" class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-9">
                    <input class="form-control" id="title" name="title" required>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="description" class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-9">
                    <textarea class="form-control" id="description" name="description" rows="5" cols="33"></textarea>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="username" class="col-sm-3 col-form-label">Assignee</label>
                <div class="col-sm-6">
                    <select name="username" id="username" class="form-select">
                        <option value=""></option>
                        <?php foreach ($data['employees'] as $employee) { ?>
                            <option value="<?php echo htmlspecialchars($employee['username']) ?>">
                                <?php echo htmlspecialchars($employee['name']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label" for="file">File</label>
                <div class="col-sm-9">
                    <input type="file" id="file" name="file" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="deadline" class="col-sm-3 col-form-label">Deadline</label>
                <div class="col-sm-4">
                    <input class="form-control" type="date" id="deadline" name="deadline">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="effort" class="col-sm-3 col-form-label">Effort</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" id="effort" name="effort" min="0" max="11" required>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-5">
                <button type="reset" name="reset" class="btn btn-secondary col-sm-2">Clear</button>
                <button type="submit" name="submit" class="btn btn-primary col-sm-2">Submit</button>
            </div>
        </form>
    </div>
</main>

</body>

</html>
