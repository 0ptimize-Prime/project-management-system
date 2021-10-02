<?php require_once __DIR__ . "/../../utils.php" ?>

<html>

<?php includeWithVariables(__DIR__ . "/../templates/header.php",
        array('title' => 'Dashboard',
            'isLoggedIn' => $_SESSION["username"])) ?>

</body>

</html>