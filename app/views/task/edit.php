<?php require_once __DIR__ . "/../../utils.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search/Update Task</title>
    <?php include __DIR__ . "/../templates/head.php" ?>
</head>

<body>

<?php
showNavbar($data);
showSidebar($data);
?>

<main style="margin-top: 80px;">
    <h1 class="text-center">Search/Update Task</h1>

    <div class="d-flex justify-content-center mt-4">
        <div class="container">
            <form class="row mb-4 gx-1" id="search-form">
                <div class="col-5">
                    <div class="row">
                        <label for="project-title" class="col-4 col-form-label">By project title</label>
                        <div class="col-6" style="margin-left: -40px;">
                            <input type="text" class="form-control" id="project-title" name="project-title">
                        </div>
                    </div>
                </div>
                <div class="col-5" style="margin-left: -40px;">
                    <div class="row">
                        <label for="task-title" class="col-3 col-form-label">By task title</label>
                        <div class="col-7" style="margin-left: -20px;">
                            <input type="text" class="form-control" id="task-title" name="task-title">
                        </div>
                    </div>
                </div>
                <div class="col-1 offset-1">
                    <button type="submit" class="btn btn-primary" name="search">Search</button>
                </div>
            </form>

            <div class="row mb-4">
                <table class="table" id="task-table">
                    <thead>
                    <tr>
                        <th scope="col">Project <span><i class="fas fa-solid fa-sort"></i></span></th>
                        <th scope="col">Title <span><i class="fas fa-solid fa-sort"></i></span></th>
                        <th scope="col">Created at <span><i class="fas fa-solid fa-sort"></i></span></th>
                        <th scope="col">Deadline <span><i class="fas fa-solid fa-sort"></i></span></th>
                        <th scope="col">Status <span><i class="fas fa-solid fa-sort"></i></span></th>
                        <th scope="col">Assigned to <span><i class="fas fa-solid fa-sort"></i></span></th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <form class="col-11" id="update-form">
                    <input type="text" name="id" id="id" hidden>
                    <div class="row mb-3">
                        <label for="project" class="col-sm-2 col-form-label">Project</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="project" name="project" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="title" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="username" class="col-sm-2 col-form-label">Assigned to</label>
                        <div class="col-sm-3">
                            <select name="username" id="username" class="form-select">
                                <option value=""></option>
                                <?php foreach ($data['employees'] as $employee) { ?>
                                    <option value="<?php echo htmlspecialchars($employee['username']) ?>">
                                        <?php echo htmlspecialchars($employee['name']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="description" class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-4">
                            <textarea name="description" id="description" cols="33" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="created_at" class="col-sm-2 col-form-label">Created at</label>
                        <div class="col-sm-3">
                            <div class="row">
                                <div class="col-11">
                                    <input type="text" class="form-control text-center" name="created_at"
                                           id="created_at" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="deadline" class="col-sm-2 col-form-label">Deadline</label>
                        <div class="col-sm-2">
                            <input type="date" class="form-control" name="deadline" id="deadline">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-2">
                            <select class="form-select" id="status" name="status">
                                <option value="" selected disabled></option>
                                <option value="CREATED">Created</option>
                                <option value="ASSIGNED">Assigned</option>
                                <option value="IN_PROGRESS">In progress</option>
                                <option value="PENDING">Pending</option>
                                <option value="COMPLETE">Complete</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="effort" class="col-sm-2 col-form-label">Effort</label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" name="effort" id="effort">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="row mt-4">
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
                                <div class="col-3" style="margin-left: 15px;">
                                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-1">
                    <button class="btn btn-info" style="margin-left: -350px;" id="go-to-project-button" hidden>
                        Go to project page
                    </button>
                    <button class="btn btn-info" style="margin-left: -350px;" id="go-to-task-button" hidden>
                        Go to task page
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="<?php echo htmlspecialchars(BASE_URL . 'js/task-edit.js') ?>"></script>

</body>

</html>
