<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create Task</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
</head>

<body>

<?php
includeWithVariables(__DIR__ . "/../templates/navbar.php", array("isLoggedIn" => true));
?>

<h1>Create Task</h1>
<h2><?php echo htmlspecialchars($data["projectTitle"]);?></h2>
<?php FlashMessage::display_flash_message("create-task") ?>
<form action="../create/<?php echo htmlspecialchars($data["projectId"]); ?>" method="post">
    <input 
        id="projectId" 
        name="projectId"
        value="<?php echo htmlspecialchars($data["projectId"]); ?>"
        hidden
    >
    
    <div class="form-group row">
        <label for="title" class="col-sm-2 col-form-label">Title</label>
        <div class="col-sm-10">
            <input
                    class="form-control"
                    id="title"
                    name="title"
                    placeholder="Title"
                    required
            >
        </div>
    </div>
    <div class="form-group row">
        <label for="description" class="col-sm-2 col-form-label">Description</label>
        <div class="col-sm-10">
            <input
                    class="form-control"
                    type="text"
                    id="description"
                    name="description"
                    placeholder="Description"
            >
        </div>
    </div>
    <div class="form-group row">
        <label for="username" class="col-sm-2 col-form-label">Username</label>
        <div class="col-sm-10">
            <input
                    class="form-control"
                    id="username"
                    name="username"
                    placeholder="chuck66"
            >
        </div>
    </div>
    <div class="form-group row">
        <label for="deadline" class="col-sm-2 col-form-label">Deadline</label>
        <div class="col-sm-10">
            <input
                    class="form-control"
                    type="date"
                    id="deadline"
                    name="deadline"
            >
        </div>
    </div>
    <div class="form-group row">
        <label for="effort" class="col-sm-2 col-form-label">Effort</label>
        <div class="col-sm-10">
            <input
                    type="number"
                    class="form-control"
                    id="effort"
                    name="effort"
                    placeholder="Effort"
                    min="0"
                    max="11"
                    required
            >
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-10">
            <button
                    type="submit"
                    class="btn btn-primary"
                    name="submit"
                    value="submit"
            >
                Submit
            </button>
        </div>
    </div>
</form>

</body>

</html>
