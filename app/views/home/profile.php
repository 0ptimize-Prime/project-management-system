<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Profile</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
    <style>
        #profile-picture-form img {
            height: 300px;
            width: 300px;
        }
    </style>
</head>

<body>

<?php
showNavbar($data);
showSidebar($data);
?>

<main style="margin-top: 78px">
    <h1 class="text-center">Update Profile</h1>

    <div class="container">
        <div class="row mb-4">
            <h3>Change display name</h3>
        </div>
        <form class="row mb-5" id="display-name-form" method="post"
              action="<?php echo htmlspecialchars(BASE_URL . 'home/profile/name') ?>">
            <label for="name" class="col-2 col-form-label">Display name</label>
            <div class="col-3" style="margin-left: -50px;">
                <input type="text" name="name" id="name" class="form-control"
                       value="<?php echo htmlspecialchars($data["user"]["name"]) ?>" required>
            </div>
            <div class="col-3 offset-1">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>

        <div class="row mb-4">
            <h3>Change password</h3>
        </div>
        <form class="row mb-5" id="password-form" method="post"
              action="<?php echo htmlspecialchars(BASE_URL . 'home/profile/password') ?>">
            <div class="row mb-3">
                <label for="old_password" class="col-2 col-form-label">Current password</label>
                <div class="col-3">
                    <input type="password" name="old_password" id="old_password" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="new_password" class="col-2 col-form-label">New password</label>
                <div class="col-3">
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="confirm_password" class="col-2 col-form-label">Confirm password</label>
                <div class="col-3">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                    <div class="valid-feedback" id="password-valid-feedback">Passwords match!</div>
                    <div class="invalid-feedback" id="password-invalid-feedback">Passwords don't match!</div>
                </div>
            </div>
            <div class="row mb-3 mt-3">
                <div class="col-3 offset-3">
                    <button type="submit" class="btn btn-primary">Change password</button>
                </div>
        </form>

        <div class="row mb-4 mt-5">
            <h3>Change profile picture</h3>
        </div>
        <form class="row mb-5" id="profile-picture-form" enctype="multipart/form-data"
              action="<?php echo htmlspecialchars(BASE_URL . 'home/profile/profile-picture') ?>" method="post">
            <div class="col-3">
                <img id="preview" alt="profile picture" class="img-circle"
                     src="<?php echo htmlspecialchars($data['user']['profile_picture'] ? BASE_URL . 'uploads/' . $data['user']['profile_picture'] : 'https://via.placeholder.com/300x300.png') ?>">
            </div>
            <div class="col-3 offset-1">
                <div class="row mb-3">
                    <div class="col-12">
                        <input accept="image/*" type="file" name="profile_picture" id="profile_picture"
                               class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-10">
                        <input type="text" name="remove_profile_picture" id="remove_profile_picture" hidden>
                        <button type="button" id="remove-dp-button" class="btn btn-danger">
                            Remove profile picture
                        </button>
                    </div>
                </div>
                <div class="row mb-3 mt-5">
                    <div class="col-3">
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script src="<?php echo htmlspecialchars(BASE_URL . 'js/home-profile.js') ?>"></script>

</body>

</html>
