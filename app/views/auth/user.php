<?php require_once __DIR__ . '/../../utils.php' ?>

<html>

<?php includeWithVariables(__DIR__ . "/../templates/header.php", array('title' => "User | " . $data['user']['name'])) ?>

<h5 class="card-title"><?php echo htmlspecialchars($data['user']['name']) ?></h5>

</body>

</html>