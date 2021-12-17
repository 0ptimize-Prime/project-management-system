<?php require_once __DIR__ . '/../../utils.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo htmlspecialchars("User | " . $data["user"]["name"]) ?></title>
    <?php include __DIR__ . "/../templates/head.php" ?>
</head>

<body>

<?php
includeWithVariables(__DIR__ . "/../templates/navbar.php", array("isLoggedIn" => true, "user" => $data["user"]));
?>

<h5 class="card-title"><?php echo htmlspecialchars($data['user']['name']) ?></h5>

</body>

</html>
