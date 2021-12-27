<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create User</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
</head>

<body>

<?php
includeWithVariables(__DIR__ . "/../templates/navbar.php", array("isLoggedIn" => true, "user" => $data["user"]));
?>

<?php
includeWithVariables(__DIR__ . "/../templates/sidebar.php", array("isAdmin" => $data["user"]["userType"] == "ADMIN"));
?>

<main style="margin-top: 80px;">
    <h1 class="text-center">Create User</h1>

    <?php FlashMessage::display_flash_message("create-user") ?>

    <div class="d-flex justify-content-center mt-5">
        <form action="<?php echo htmlspecialchars(BASE_URL . 'admin/create') ?>" method="post">
            <div class="form-group row mb-3">
                <label for="username" class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                    <input class="form-control" id="username" name="username">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="name" class="col-sm-4 col-form-label">Name</label>
                <div class="col-sm-8">
                    <input class="form-control" id="name" name="name">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="userType" class="col-sm-4 col-form-label">User Type</label>
                <div class="col-sm-8">
                    <select class="form-select" id="userType" name="userType">
                        <option value="ADMIN">Admin</option>
                        <option value="MANAGER">Manager</option>
                        <option value="EMPLOYEE" selected>Employee</option>
                    </select>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="password" class="col-sm-4 col-form-label">Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="password" name="password">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-sm-4" for="file">Profile Picture:</label>
                <div class="col-sm-8">
                    <input type="file" name="file">
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button type="reset" name="reset" class="btn btn-secondary col-sm-3">Clear</button>
                <button type="submit" name="submit" class="btn btn-primary col-sm-3">Submit</button>
            </div>
        </form>
    </div>
</main>

</body>

</html>
