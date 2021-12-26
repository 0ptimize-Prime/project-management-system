<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search/Update User</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
</head>

<body>

<?php
includeWithVariables(__DIR__ . "/../templates/navbar.php", array("isLoggedIn" => true, "user" => $data["user"]));
?>

<h1>Search/Update User</h1>
<?php FlashMessage::display_flash_message("create-user") ?>
<form action="<?php echo htmlspecialchars(BASE_URL . 'admin/edit') ?>" method="get">
    <div class="form-group row">
        <label for="username" class="col-sm-2 col-form-label">Username</label>
        <div class="col-sm-10">
            <input
                class="form-control"
                id="username"
                name="username"
                placeholder="Username"
            >
        </div>
    </div>
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Name</label>
        <div class="col-sm-10">
            <input
                class="form-control"
                id="name"
                name="name"
                placeholder="Name"
            >
        </div>
    </div>
    <div class="form-group row">
        <label for="userType" class="col-sm-2 col-form-label">User Type</label>
        <div class="col-sm-10">
            <select
                class="form-control"
                id="userType"
                name="userType"
            >
                <option value="ADMIN">Admin</option>
                <option value="MANAGER">Manager</option>
                <option value="EMPLOYEE">Employee</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-10">
            <button
                type="submit"
                class="btn btn-primary"
                name="search"
            >
                Search
            </button>
        </div>
    </div>
</form>

</body>

</html>
