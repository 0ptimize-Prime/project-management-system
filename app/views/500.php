<!DOCTYPE html>
<html lang="en">

<head>
    <title>500 Internal Server Error</title>
    <?php include __DIR__ . "/templates/head.php" ?>
</head>

<body>

<div style="margin-top: 80px;">
    <h1 class="text-center">500 Internal Server Error</h1>
    <p class="text-center">An internal server error has occurred.<br>Please try again later.</p>

    <div class="text-center mt-5">
        <a href="<?php echo htmlspecialchars(BASE_URL . 'home/dashboard') ?>" class="btn btn-primary">
            Go to the dashboard
        </a>
    </div>
</div>
</body>

</html>
