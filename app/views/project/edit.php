<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search/Update Project</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
</head>

<body>

<?php
showNavbar($data);
showSidebar($data);
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
                <?php if ($data["user"]["userType"] == "ADMIN") { ?>
                    <div class="col-5">
                        <div class="row">
                            <label for="manager" class="col-3 offset-1 col-form-label">By manager</label>
                            <div class="col-5">
                                <input type="text" class="form-control" id="manager" name="manager">
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-1 ms-5">
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
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody style="cursor: pointer;">
                    <?php if ($data["project"]) { ?>
                        <tr data-id="<?php echo htmlspecialchars($data['project']['id']) ?>"
                            data-description="<?php echo htmlspecialchars($data['project']['description']) ?>">
                            <td><?php echo htmlspecialchars($data['project']['title']) ?></td>
                            <td data-username="<?php echo htmlspecialchars($data['project']['manager']) ?>">
                                <?php echo htmlspecialchars($data['project']['managerName']) ?>
                            </td>
                            <td><?php echo htmlspecialchars($data['project']['created_at']) ?></td>
                            <td><?php echo htmlspecialchars($data['project']['deadline']) ?></td>
                            <td><?php echo htmlspecialchars($data['project']['status']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <form class="col-11" id="update-form">
                    <input type="text" name="id" id="id" hidden>
                    <div class="row mb-3">
                        <label for="title" class="col-sm-1 col-form-label">Title</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="manager" class="col-sm-1 col-form-label">Manager</label>
                        <div class="col-sm-3">
                            <select name="manager" id="manager" class="form-select"
                                <?php if ($data["user"]["userType"] != "ADMIN") { ?> disabled <?php } ?>>
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
                        <div class="col-sm-8">
                            <div class="row mt-5">
                                <div class="col-3">
                                    <button type="button" id="cancel-button" name="cancel" class="btn btn-secondary">
                                        Cancel
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" id="remove-button" name="remove" class="btn btn-danger">
                                        Remove
                                    </button>
                                </div>
                                <div class="col-3" style="margin-left: 10px;">
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

<?php if ($data["project"]) { ?>
    <script>
        const row = table.querySelector("tbody tr");
        if (row)
            editProject(row);
    </script>
<?php } ?>

</body>

</html>
