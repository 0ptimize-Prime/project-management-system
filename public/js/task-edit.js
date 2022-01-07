const searchForm = document.getElementById("search-form");
const searchFormFields = searchForm.querySelectorAll("input");

let sortOrder = null;
const table = document.getElementById("task-table");

let task = null;
const updateForm = document.getElementById("update-form");
const updateFormFields = updateForm.querySelectorAll("input,select,textarea");
const cancelButton = document.getElementById("cancel-button");
const removeButton = document.getElementById("remove-button");
const goToProjectButton = document.getElementById("go-to-project-button");
const goToTaskButton = document.getElementById("go-to-task-button");

const resetUpdateForm = () => {
    updateForm.reset();
    task = null;
    goToProjectButton.hidden = true;
    goToTaskButton.hidden = true;
}

searchForm.addEventListener("submit", e => {
    e.preventDefault();

    const queryParts = Array.from(searchFormFields).map(field => {
        return field.getAttribute("name") + "=" + encodeURIComponent(field.value);
    });
    const query = "?" + queryParts.join("&");

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const response = JSON.parse(this.response);
            const tbody = table.querySelector("tbody");
            tbody.innerHTML = "";
            response.forEach(task => {
                const tr = document.createElement("tr");
                tr.dataset.id = task.id;
                tr.dataset.description = task.description;
                tr.dataset.effort = task.effort;
                const projectTd = document.createElement("td");
                projectTd.textContent = task.projectName;
                projectTd.dataset.id = task.projectId;
                tr.appendChild(projectTd);
                const titleTd = document.createElement("td");
                titleTd.textContent = task.title;
                tr.appendChild(titleTd);
                const createdAtTd = document.createElement("td");
                createdAtTd.textContent = task.created_at;
                tr.appendChild(createdAtTd);
                const deadlineTd = document.createElement("td");
                deadlineTd.textContent = task.deadline;
                tr.appendChild(deadlineTd);
                const statusTd = document.createElement("td");
                statusTd.textContent = task.status;
                tr.appendChild(statusTd);
                const employeeTd = document.createElement("td");
                employeeTd.textContent = task.employeeName;
                employeeTd.dataset.username = task.username;
                tr.appendChild(employeeTd);
                const editTd = document.createElement("td");
                editTd.innerHTML = "<button type='button' class='btn btn-primary btn-sm'><i class='fas fa-edit'></i></button>"
                tr.appendChild(editTd);
                tbody.appendChild(tr);
            });
        }
    }
    xhttp.open("GET", BASE_URL + "task/search" + query, true);
    xhttp.send();
});

const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

const comparer = (idx, asc) => (a, b) => {
    const v1 = getCellValue(asc ? a : b, idx);
    const v2 = getCellValue(asc ? b : a, idx);
    return (v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2)) ? v1 - v2 : v1.toString().localeCompare(v2);
};

document.querySelectorAll('th').forEach(th => th.addEventListener('click', e => {
    // Set icons
    const span = e.currentTarget.querySelector("span");
    if (!span)
        return;
    const icon = "sort-" + (sortOrder ? "up" : "down");
    span.innerHTML = `<i class="fas fa-solid fa-${icon}"></i>`;
    table.querySelectorAll("th").forEach(x => {
        if (x.cellIndex !== th.cellIndex) { // Remove other icons
            const span = x.querySelector("span");
            if (!span)
                return;
            span.innerHTML = "<i class='fas fa-solid fa-sort'></i>";
        }
    });

    // Sort table
    sortOrder = !sortOrder;
    const tbody = table.querySelector("tbody");
    Array.from(tbody.querySelectorAll('tr'))
        .sort(comparer(th.cellIndex, sortOrder))
        .forEach(tr => tbody.appendChild(tr));
}));

table.querySelector("tbody").addEventListener("click", e => {
    let row;
    if (e.target.nodeName === "I")
        row = e.target.parentElement.parentElement.parentElement;
    else if (e.target.nodeName === "BUTTON")
        row = e.target.parentElement.parentElement;
    else
        return;

    resetUpdateForm();
    const {id, description, effort} = row.dataset;
    const [
        {textContent: projectTitle, dataset: {id: projectId}},
        {textContent: title},
        {textContent: createdAt},
        {textContent: deadline},
        {textContent: status},
        {textContent: employeeName, dataset: {username}}
    ] = row.children;

    updateFormFields[0].value = id;
    updateFormFields[1].value = projectTitle;
    updateFormFields[2].value = title;
    updateFormFields[3].value = username;
    updateFormFields[4].value = description;
    updateFormFields[5].value = createdAt;
    updateFormFields[6].value = deadline;
    updateFormFields[7].value = status;
    updateFormFields[8].value = effort;
    goToProjectButton.hidden = false;
    goToTaskButton.hidden = false;
    task = {
        id,
        projectId,
        projectTitle,
        title,
        username,
        employeeName,
        description,
        createdAt,
        deadline,
        status,
        effort
    };
});

updateForm.addEventListener("submit", e => {
    e.preventDefault();

    if (!task)
        return;

    if (task.title === updateFormFields[2].value
        && task.username === updateFormFields[3].value
        && task.description === updateFormFields[4].value
        && task.deadline === updateFormFields[5].value
        && task.status === updateFormFields[6].value
        && task.effort === updateFormFields[7].value) {
        return;
    }

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const response = JSON.parse(this.response);
            table.querySelectorAll("tbody tr").forEach(row => {
                if (row.dataset.id === response.id) {
                    row.children[1].dataset.username = response.title;
                    row.children[3].textContent = response.deadline;
                    row.children[4].textContent = response.status;
                    row.children[5].textContent = response.employeeName;
                    row.children[5].dataset.username = response.username;
                    row.dataset.description = response.description;
                    row.dataset.effort = response.effort;
                }
            });
            resetUpdateForm();
        }
    };
    xhttp.open("POST", BASE_URL + "task/edit", true);
    xhttp.send(new FormData(updateForm));
});

cancelButton.addEventListener("click", resetUpdateForm);

removeButton.addEventListener("click", () => {
    if (!task)
        return;

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            table.querySelectorAll("tbody tr").forEach(row => {
                if (row.dataset.id === task.id) {
                    table.querySelector("tbody").removeChild(row);
                }
            });
            resetUpdateForm()
        }
    };
    xhttp.open("DELETE", BASE_URL + "task/edit", true);
    xhttp.send(new FormData(updateForm))
});

goToProjectButton.addEventListener("click", () => {
    if (task) {
        window.location.assign(BASE_URL + "project/view/" + task.projectId);
    }
});

goToTaskButton.addEventListener("click", () => {
    if (task) {
        window.location.assign(BASE_URL + "task/view/" + task.id);
    }
});
