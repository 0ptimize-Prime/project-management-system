<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create Project</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
</head>

<body>

<?php
includeWithVariables(__DIR__ . "/../templates/navbar.php", array("isLoggedIn" => true, "user" => $data["user"]));
?>

<?php
includeWithVariables(__DIR__ . "/../templates/sidebar.php", array("isAdmin" => $data["user"]["userType"] == "ADMIN"));
?>

<main style="margin-top: 80px">
    <h1 class="text-center">Create Project</h1>

    <?php FlashMessage::display_flash_message("create-project") ?>

    <div class='d-flex justify-content-center mt-5'>
        <form action='create' method="post" enctype="multipart/form-data">
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label" for="title">Project Title:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="title" id="title" required="required">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label" for="description">Description:</label>
                <div class="col-sm-9">
                    <textarea id="description" name="description" rows="5" cols="33"></textarea>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label" for="file">File:</label>
                <div class="col-sm-9">
                    <input type="file" name="file" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-3 col-form-label" for="deadline">Deadline</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" name="deadline" id="deadline">
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button type="reset" name="Reset" class="btn btn-secondary col-sm-2">Clear</button>
                <button type="submit" name="submit" class="btn btn-primary col-sm-2">Submit</button>
            </div>
        </form>
    </div>
</main>

</body>

</html>
