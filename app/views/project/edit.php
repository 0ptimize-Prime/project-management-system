<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search/Update Project</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
    <style>
        #project-table tbody {
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
    <h1 class="text-center">Search/Update Project</h1>

    <div class="d-flex justify-content-center mt-4">
        <div class="container">
            <form class="row mb-4 gx-1" id="search-form">
                <div class="col-3">
                    <div class="row">
                        <label for="title" class="col-3 col-form-label">By title</label>
                        <div class="col-8">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="row">
                        <label for="manager" class="col-3 offset-1 col-form-label">By manager</label>
                        <div class="col-5">
                            <input type="text" class="form-control" id="manager" name="manager">
                        </div>
                    </div>
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-primary" name="search">Search</button>
                </div>
            </form>

            <div class="row mb-4">
                <table class="table table-hover" id="project-table">
                    <thead>
                    <tr>
                        <th scope="col">Title <span><i class="fas fa-solid fa-sort"></i></span></th>
                        <th scope="col">Manager <span><i class="fas fa-solid fa-sort"></i></span></th>
                        <th scope="col">Created at <span><i class="fas fa-solid fa-sort"></i></span></th>
                        <th scope="col">Deadline <span><i class="fas fa-solid fa-sort"></i></span></th>
                        <th scope="col">Status <span><i class="fas fa-solid fa-sort"></i></span></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <form class="col-11" id="update-form">
                    <div class="row mb-3">
                        <label for="title" class="col-sm-1 col-form-label">Title</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="manager" class="col-sm-1 col-form-label">Manager</label>
                        <div class="col-sm-3">
                            <select name="manager" id="manager" class="form-select">
                                <option value=""></option>
                                <?php foreach ($data['managers'] as $manager) { ?>
                                    <option value="<?php echo htmlspecialchars($manager['username']) ?>">
                                        <?php echo htmlspecialchars($manager['name']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="description" class="col-sm-1 col-form-label">Description</label>
                        <div class="col-sm-4">
                            <textarea name="description" id="description" cols="33" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="deadline" class="col-sm-1 col-form-label">Deadline</label>
                        <div class="col-sm-2">
                            <input type="date" class="form-control" name="deadline" id="deadline">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-4">
                            <div class="row mt-4">
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
                <div class="col-1">
                    <button class="btn btn-info" style="margin-left: -350px;" id="go-to-button" hidden>
                        Go to project page
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="<?php echo htmlspecialchars(BASE_URL . 'js/project-edit.js') ?>"></script>

</body>

</html>
