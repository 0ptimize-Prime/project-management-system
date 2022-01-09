<?php
$isLoggedIn = $isLoggedIn ?? false;
$notifications = $notifications ?? [];

$notifTypeToMessageMap = [
    "TASK_ASSIGNMENT" => "Task has been assigned",
    "TASK_PENDING_APPROVAL" => "Task is pending approval",
    "TASK_COMPLETED" => "Task has been approved"
];
$notifTypeToLinkMap = [
    "TASK_ASSIGNMENT" => "task/view/",
    "TASK_PENDING_APPROVAL" => "task/view/",
    "TASK_COMPLETED" => "task/view/"
];

$notifications = array_map(function ($notification) use ($notifTypeToMessageMap, $notifTypeToLinkMap) {
    return array_merge(
        [
            'message' => $notifTypeToMessageMap[$notification['type']],
            'link' => BASE_URL . $notifTypeToLinkMap[$notification['type']] . $notification['item_id']
        ],
        $notification
    );
}, $notifications);
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
                    <li class="nav-item dropdown me-3">
                        <span class="text-white nav-link"
                              id="navbarNotificationDropdown" role="button" data-bs-toggle="dropdown"
                              aria-expanded="false">
                            <i class="fas fa-bell"></i> Notifications
                            <?php if (count($notifications) > 0) { ?>
                                <span class="position-absolute top-0 start-100 p-2 bg-danger border border-light rounded-circle"
                                      style="transform: translate(-80%, -20%);">
                                </span>
                            <?php } ?>
                        </span>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarNotificationDropdown"
                            id="notifications">
                            <li id="mark-as-read">
                                <a href="#" class="dropdown-item">
                                    Mark as read <i class="fas fa-check position-absolute"></i>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <?php foreach ($notifications as $notification) { ?>
                                <li data-id="<?php echo htmlspecialchars($notification['id']) ?>">
                                    <a href="<?php echo htmlspecialchars($notification['link']) ?>"
                                       class="dropdown-item">
                                        <?php if ($notification['is_read']) { ?>
                                            <i class="fas fa-envelope-open"></i>
                                        <?php } else { ?>
                                            <i class="fas fa-envelope"></i>
                                        <?php } ?>
                                        <?php echo htmlspecialchars($notification['message']) ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (empty($notifications)) { ?>
                                <li id="no-notifications"><span class="dropdown-item">No notifications</span></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <span class="text-white dropdown-toggle" id="navbarProfileDropdown" role="button"
                              data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($user["name"]) ?>
                            <img src="<?php echo htmlspecialchars($user['profile_picture'] ? BASE_URL . "uploads/" . $user["profile_picture"] : 'https://via.placeholder.com/40x40.png') ?>"
                                 alt="avatar" class="img-fluid img-circle m-1">
                        </span>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarProfileDropdown">
                            <li>
                                <a class="dropdown-item"
                                   href="<?php echo htmlspecialchars(BASE_URL . 'home/profile') ?>">
                                    <i class="fas fa-fw fa-user"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="<?php echo htmlspecialchars(BASE_URL . 'auth/logout') ?>">
                                    <i class="fas fa-fw fa-sign-out-alt"></i> Log out
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
