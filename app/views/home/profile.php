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
includeWithVariables(
    __DIR__ . "/../templates/navbar.php", array("isLoggedIn" => true, "user" => $data["user"])
);
?>

<?php
includeWithVariables(__DIR__ . "/../templates/sidebar.php", array("isAdmin" => $data["user"]["userType"] == "ADMIN"));
?>

<main style="margin-top: 78px">
    <h1 class="text-center">Update Profile</h1>

    <div class="container">
        <div class="row mb-4">
            <h3>Change display name</h3>
        </div>
        <form class="row mb-5" id="display-name-form">
            <label for="name" class="col-2 col-form-label">Display name</label>
            <div class="col-3" style="margin-left: -50px;">
                <input type="text" name="name" id="name" class="form-control"
                       value="<?php echo htmlspecialchars($data["user"]["name"]) ?>">
            </div>
            <div class="col-3 offset-1">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>

        <div class="row mb-4">
            <h3>Change password</h3>
        </div>
        <form class="row mb-5" id="password-form">
            <div class="row mb-3">
                <label for="currentPassword" class="col-2 col-form-label">Current password</label>
                <div class="col-3">
                    <input type="password" name="currentPassword" id="currentPassword" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <label for="newPassword" class="col-2 col-form-label">New password</label>
                <div class="col-3">
                    <input type="password" name="newPassword" id="newPassword" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <label for="confirmPassword" class="col-2 col-form-label">Confirm password</label>
                <div class="col-3">
                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control">
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
        <form class="row mb-5" id="profile-picture-form">
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
