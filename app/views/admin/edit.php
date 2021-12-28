<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search/Update User</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
    <style>
        #preview {
            object-fit: cover;
            width: 300px;
            height: 300px;
        }

        #user-table tbody {
            cursor: pointer;
        }
    </style>
</head>

<body>

<?php
includeWithVariables(__DIR__ . "/../templates/navbar.php", array("isLoggedIn" => true, "user" => $data["user"]));
?>

<?php
includeWithVariables(__DIR__ . "/../templates/sidebar.php", array("isAdmin" => $data["user"]["userType"] == "ADMIN"));
?>

<main style="margin-top: 80px;">
    <h1 class="text-center">Search/Update User</h1>

    <div class="d-flex justify-content-center mt-4">
        <div class="container">
            <form class="row mb-4 gx-1" id="search-form">
                <div class="col-9">
                    <div class="row">
                        <div class="col-4">
                            <div class="row">
                                <label for="username" class="col-5 col-form-label">By username</label>
                                <div class="col-7">
                                    <input class="form-control" id="username" name="username">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <label for="name" class="col-4 offset-1 col-form-label">By name</label>
                                <div class="col-7">
                                    <input class="form-control" id="name" name="name">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <label for="userType" class="col-4 offset-1 col-form-label">By type</label>
                                <div class="col-6" style="margin-left: -15px;">
                                    <select name="userType" id="userType" class="form-select">
                                        <option value="" selected>Any</option>
                                        <option value="ADMIN">Admin</option>
                                        <option value="MANAGER">Manager</option>
                                        <option value="EMPLOYEE">Employee</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-1 offset-1">
                    <button class="btn btn-primary" type="submit" name="search">Search</button>
                </div>
            </form>

            <div class="row mb-4">
                <table class="table table-hover" id="user-table">
                    <thead>
                    <tr>
                        <th scope="col">Username <span><i class='fas fa-solid fa-sort'></i></span></th>
                        <th scope="col">Name <span><i class='fas fa-solid fa-sort'></i></span></th>
                        <th scope="col">User type <span><i class='fas fa-solid fa-sort'></i></span></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <form class="container" id="update-form">
                    <div class="row mb-4">
                        <div class="col-4">
                            <div class="form-group row mb-3">
                                <label for="username" class="col-sm-4 col-form-label">Username</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="username" name="username" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="name" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="name" name="name">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="userType" class="col-sm-4 col-form-label">User Type</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="userType" name="userType">
                                        <option value="ADMIN">Admin</option>
                                        <option value="MANAGER">Manager</option>
                                        <option value="EMPLOYEE" selected>Employee</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 offset-1">
                            <img src="https://via.placeholder.com/300x300.png" id="preview" alt="..."
                                 class="img-circle">
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3">
                            <div class="row">
                                <div class="col-2">
                                    <button type="button" id="cancel-button" name="cancel" class="btn btn-secondary">
                                        Cancel
                                    </button>
                                </div>
                                <div class="col-2">
                                    <button type="button" id="remove-button" name="remove" class="btn btn-danger">
                                        Remove
                                    </button>
                                </div>
                                <div class="col-2">
                                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="<?php echo htmlspecialchars(BASE_URL . 'js/admin-edit.js') ?>"></script>

</body>

</html>
