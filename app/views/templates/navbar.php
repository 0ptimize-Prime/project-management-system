<?php
$isLoggedIn = $isLoggedIn ?? false;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo htmlspecialchars(BASE_URL . 'home/dashboard') ?>">
            Project Management System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($isLoggedIn) { ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page"
                           href="<?php echo htmlspecialchars(BASE_URL . 'home/dashboard') ?>">
                            Dashboard
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($isLoggedIn) { ?>
                    <li class="nav-item dropdown">
                        <span class="text-white dropdown-toggle ps-4" id="navbarDropdown" role="button"
                              data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($user["name"]) ?>
                        </span>
                        <img src="<?php echo htmlspecialchars($user['profile_picture'] ? "/public/uploads/" . $user["profile_picture"] : 'https://via.placeholder.com/40x40.png') ?>"
                             alt="avatar" class="img-fluid img-circle m-1">
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <a class="dropdown-item"
                                   href="<?php echo htmlspecialchars(BASE_URL . 'auth/logout') ?>">
                                    Log out
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo htmlspecialchars(BASE_URL . 'auth/login') ?>">
                            Login
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

