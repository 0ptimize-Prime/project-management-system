<?php require_once __DIR__ . '/../../utils.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
    <style>
        .login-form form {
            background: #f7f7f7;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }
    </style>
</head>

<body>

<?php
showNavbar($data, false);
?>

<div class="login-form d-flex justify-content-center" style="margin-top: 63px;">
    <form action="login" method="post">
        <h2 class="text-center mb-3">Login</h2>
        <?php FlashMessage::display_flash_message("login") ?>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                   required="required">
            <label for="username">Username</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                   required="required">
            <label for="password">Password</label>
        </div>
        <div class="form-group d-grid m-2">
            <button type="submit" class="btn btn-primary fw-bold">Log in</button>
        </div>
    </form>
</div>

</body>
</html>
