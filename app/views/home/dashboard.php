<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
</head>

<body>

<?php
includeWithVariables(__DIR__ . "/../templates/navbar.php", array("isLoggedIn" => true));
?>
</body>

</html>
