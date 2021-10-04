<?php require_once __DIR__ . '/../../utils.php' ?>

<html>

<?php
includeWithVariables(
    __DIR__ . "/../templates/header.php",
    array('title' => 'Login', 'isLoggedIn' => false));
$data = $data ?? [];
?>
<h5 class="card-title text-align-center">Login</h5>
<?php display_flash_message("login"); ?>
<form action="login" method="POST">
    <label for="username" class="form-label">Username</label>
    <input
            type="text"
            name="username"
            id="username"
            class="form-control"
            aria-describedby="usernameHelp"
    />
    <label for="password" class="form-label">Password</label>
    <input
            type="password"
            name="password"
            id="password"
            class="form-control"
            aria-describedby="passwordHelp"
    />
    <button type="submit" name="submit" class="btn btn-primary">Login</button>
</form>
</body>

</html>