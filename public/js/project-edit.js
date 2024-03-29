const heading = document.querySelector("main h1");

const searchForm = document.getElementById("search-form");
const searchFormFields = searchForm.querySelectorAll("input");

let sortOrder = null;
const table = document.getElementById("project-table");

let project = null;
const updateForm = document.getElementById("update-form");
const updateFormFields = updateForm.querySelectorAll("input,select,textarea");
const cancelButton = document.getElementById("cancel-button");
const removeButton = document.getElementById("remove-button");
const goToButton = document.getElementById("go-to-button");

const resetUpdateForm = () => {
    updateForm.reset();
    project = null;
    goToButton.hidden = true;
}

const showAlert = (message, style) => {
    const div = document.createElement("div");
    div.classList.add("alert", "alert-" + style, "mx-4");
    div.textContent = message;
    div.id = "update-project-message";
    heading.after(div);
    setTimeout(deleteAlert, 3000);
}

const deleteAlert = () => {
    const alertDiv = document.getElementById("update-project-message");
    if (alertDiv)
        alertDiv.remove();
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
            response.forEach(project => {
                const tr = document.createElement("tr");
                tr.dataset.id = project.id;
                tr.dataset.description = project.description ?? '';
                const titleTd = document.createElement("td");
                titleTd.textContent = project.title;
                tr.appendChild(titleTd);
                const managerTd = document.createElement("td");
                managerTd.textContent = project.managerName;
                managerTd.dataset.username = project.manager;
                tr.appendChild(managerTd);
                const createdAtTd = document.createElement("td");
                createdAtTd.textContent = project.created_at;
                tr.appendChild(createdAtTd);
                const deadlineTd = document.createElement("td");
                deadlineTd.textContent = project.deadline;
                tr.appendChild(deadlineTd);
                const statusTd = document.createElement("td");
                statusTd.textContent = project.status;
                tr.appendChild(statusTd);
                const editTd = document.createElement("td");
                editTd.innerHTML = "<button type='button' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i></button>"
                tr.appendChild(editTd);
                tbody.appendChild(tr);
            });
        }
    }
    xhttp.open("GET", BASE_URL + "project/search" + query, true);
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

const editProject = (row) => {
    resetUpdateForm();
    const {id, description} = row.dataset;
    const [
        {textContent: title},
        {textContent: managerName, dataset: {username: manager}},
        {textContent: createdAt},
        {textContent: deadline},
        {textContent: status}
    ] = row.children;

    updateFormFields[0].value = id;
    updateFormFields[1].value = title;
    updateFormFields[2].value = manager;
    updateFormFields[3].value = description;
    updateFormFields[4].value = deadline;
    goToButton.hidden = false;
    project = {id, title, manager, managerName: managerName.trim(), description, createdAt, deadline, status};
};

const goToProject = (row) => {
    const id = row.dataset.id;
    window.location.assign(BASE_URL + "project/view/" + id);
};

table.querySelector("tbody").addEventListener("click", e => {
    const row = e.target.closest("tr");
    if (e.target.nodeName === "I" || e.target.nodeName === "BUTTON")
        editProject(row);
    else
        goToProject(row);
});

updateForm.addEventListener("submit", e => {
    e.preventDefault();

    if (!project)
        return;

    if (project.title === updateFormFields[1].value
        && project.manager === updateFormFields[2].value
        && project.description === updateFormFields[3].value
        && project.deadline === updateFormFields[4].value) {
        return;
    }

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                const response = JSON.parse(this.response);
                const row = Array.from(table.querySelectorAll("tbody tr")).find(row => row.dataset.id === response.id);
                if (row) {
                    row.children[0].textContent = response.title;
                    row.children[1].textContent = response.managerName;
                    row.children[1].dataset.username = response.manager;
                    row.children[3].textContent = response.deadline;
                    row.children[4].textContent = response.status;
                    row.dataset.description = response.description;
                }
                resetUpdateForm();
                showAlert("Project successfully updated", "success");
            } else {
                showAlert("Project update failed", "danger");
            }
        }
    };
    xhttp.open("POST", BASE_URL + "project/edit", true);
    xhttp.send(new FormData(updateForm));
});

cancelButton.addEventListener("click", resetUpdateForm);

removeButton.addEventListener("click", () => {
    if (!project)
        return;

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const row = Array.from(table.querySelectorAll("tbody tr")).find(row => row.dataset.id === project.id);
            if (row) {
                row.remove();
            }
            resetUpdateForm()
        }
    };
    xhttp.open("DELETE", BASE_URL + "project/edit/" + encodeURIComponent(project.id), true);
    xhttp.send();
});

goToButton.addEventListener("click", () => {
    if (project) {
        window.location.assign(BASE_URL + "project/view/" + project.id);
    }
});
