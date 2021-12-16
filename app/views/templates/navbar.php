<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href=<?php echo htmlspecialchars(BASE_URL . "home/dashboard") ?>>Project Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">III</span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($isLoggedIn) : ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page"
                           href=<?php echo htmlspecialchars(BASE_URL . "home/dashboard") ?>>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"
                           href=<?php echo htmlspecialchars(BASE_URL . "auth/logout") ?>>Logout</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"
                           href=<?php echo htmlspecialchars(BASE_URL . "auth/login") ?>>Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
