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
    <link rel="stylesheet" href="<?php echo BASE_URL?>css/style.css "/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Calisto MT"/>
    <style>
        body {
            font-family: "Calisto MT", sans-serif;

        }
    </style>
    <title>Bootstrap 5 Login form for project</title>
</head>
<body>
<div class="global-container">
    <div class="card login-form">
        <div class="card-body">
            <h1 class="card-title text-center">LOGIN</h1>
            <div class="card-text">
                <form>
                    <div class="form-group">
                        <label for="Username">Username</label>
                        <input
                                type="text"
                                name="username"
                                id="username"
                                class="form-control"
                                aria-describedby="usernameHelp"
                        />
                    </div>

                    <div class="form-group">
                        <label for="Password">Password</label>
                        <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                aria-describedby="passwordHelp"
                        />
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"> Login </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>