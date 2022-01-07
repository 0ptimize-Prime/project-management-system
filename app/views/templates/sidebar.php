<?php
$userType = $userType ?? "EMPLOYEE";
?>

<nav id="sidebarMenu" class="d-lg-block sidebar collapse bg-white">
    <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <?php if ($userType == "ADMIN") { ?>
                <div class="list-group-item list-group-item-action py-2 ripple active">
                    <i class="fas fa-user fa-fw me-3"></i><span>Users</span>
                </div>
                <a href="<?php echo htmlspecialchars(BASE_URL . 'admin/create') ?>"
                   class="list-group-item list-group-item-action py-2 ripple">
                    <i class="fas fa-user-plus fa-fw me-3"></i><span>Create User</span>
                </a>
                <a href="<?php echo htmlspecialchars(BASE_URL . 'admin/edit') ?>"
                   class="list-group-item list-group-item-action py-2 mb-5 ripple">
                    <i class="fas fa-search fa-fw me-3"></i><span>Search/Update User</span>
                </a>
            <?php } ?>

            <div class="list-group-item list-group-item-action py-2 ripple active">
                <i class="fas fa-project-diagram fa-fw me-3"></i><span>Projects</span>
            </div>
            <a href="<?php echo htmlspecialchars(BASE_URL . 'project/create') ?>"
               class="list-group-item list-group-item-action py-2 ripple">
                <i class="fas fa-plus fa-fw me-3"></i><span>Create Project</span>
            </a>
            <a href="<?php echo htmlspecialchars(BASE_URL . 'project/edit') ?>"
               class="list-group-item list-group-item-action py-2 mb-5 ripple">
                <i class="fas fa-search fa-fw me-3"></i><span>Search/Update Projects</span>
            </a>

            <div class="list-group-item list-group-item-action py-2 ripple active">
                <i class="fas fa-tasks fa-fw me-3"></i><span>Tasks</span>
            </div>
            <a href="<?php echo htmlspecialchars(BASE_URL . 'task/edit') ?>"
               class="list-group-item list-group-item-action py-2 ripple">
                <i class="fas fa-search fa-fw me-3"></i><span>Search/Update Tasks</span>
            </a>
        </div>
    </div>
</nav>
