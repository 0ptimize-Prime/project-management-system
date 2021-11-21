<?php require_once __DIR__ . "/../../utils.php" ?>
<html>

<?php includeWithVariables(__DIR__ . "/../templates/header.php",
    array('title' => 'Create User',
        'isLoggedIn' => $_SESSION["user"])) ?>
<h1>Create Project</h1>
<?php display_flash_message("create-project") ?>
<div class='container'>
    <div class='row'>

        <div class='col-md-6' >

            <form  action='create_project' method="post"  enctype="multipart/form-data">

                <div class="form-group">
                    <label for="manager">Project Manager:</label>
                    <input type="text" class="form-control" name="manager" id="manager" required="required">
                </div>
                <div class="form-group">
                    <label for="title">Project Title:</label>
                    <input type="text" class="form-control" name="title" id="title" required="required">

                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="5" cols="33"> </textarea>
                </div>
                <div class="form-group">
                    file : <input type="file" name="file"><br><br>
                    <br/>
                </div>
                <div class="form-group">
                    <label for="deadline">Deadline:</label>
                    <input type="date" class="form-control" name="deadline" id="deadline">
                </div>

                <button type="submit" name="Submit" class="btn btn-default">Submit</button>
                <button type="reset"  name="Reset" class="btn btn-default">Clear</button>
            </form>
        </div>

    </div>
</div>
</body>
</html>
