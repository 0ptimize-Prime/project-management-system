<?php require_once __DIR__ . '/../../utils.php' ?>



<?php
includeWithVariables(
    __DIR__ . "/../templates/header.php",
    array('title' => 'Login', 'isLoggedIn' => false));
$data = $data ?? [];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo BASE_URL?>css/login.css "/>
</head>
<body>
<?php display_flash_message("login") ?>
<div class="login-form">
    <form action="login" method="post">
        <h2 class="text-center">Login</h2>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="username" id="username" placeholder="Username" required="required">
            <label for="username">Username</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required="required">
            <label for="password">Password</label>
        </div>
        <div class="form-group d-grid m-2">
            <button type="submit" class="btn btn-primary">Log in</button>
        </div>
    </form>
</div>

</body>
</html>