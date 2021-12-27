const BASE_URL = document.head.querySelector("[name=BASE_URL][content]").content;

const searchForm = document.getElementById("search-form");
const searchFormFields = searchForm.querySelectorAll("input,select");

let sortOrder = null;
const table = document.getElementById("user-table");

let user = null;
const updateForm = document.getElementById("update-form");
const updateFormFields = updateForm.querySelectorAll("input,select");
const cancelButton = document.getElementById("cancel-button");
const removeButton = document.getElementById("remove-button");

const imgInput = document.getElementById("profile_picture");
const preview = document.getElementById("preview");
const removeDpButton = document.getElementById("remove-dp-button");
const removeProfilePicture = document.getElementById("remove_profile_picture");

const placeholderImage = "https://via.placeholder.com/300x300.png";
const resetUpdateForm = () => {
    updateForm.reset();
    preview.src = placeholderImage;
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
            response.forEach(user => {
                const tr = document.createElement("tr");
                const profileTd = document.createElement("td");
                profileTd.textContent = user.profile_picture;
                profileTd.style = "display: none;";
                tr.appendChild(profileTd);
                const usernameTd = document.createElement("td");
                usernameTd.textContent = user.username;
                tr.appendChild(usernameTd);
                const nameTd = document.createElement("td");
                nameTd.textContent = user.name
                tr.appendChild(nameTd);
                const userTypeTd = document.createElement("td");
                userTypeTd.textContent = user.userType;
                tr.appendChild(userTypeTd);
                tbody.appendChild(tr);
            });
        }
    }
    xhttp.open("GET", BASE_URL + "admin/search" + query, true);
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
    const icon = "sort-" + (sortOrder ? "up" : "down");
    span.innerHTML = `<i class="fas fa-solid fa-${icon}"></i>`;
    table.querySelectorAll("th").forEach(x => {
        if (x.cellIndex !== th.cellIndex) { // Remove other icons
            x.querySelector("span").innerHTML = "<i class='fas fa-solid fa-sort'></i>";
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
    resetUpdateForm();
    const row = e.target.parentElement;
    const [{textContent: profilePicture}, {textContent: username}, {textContent: name}, {textContent: userType}] = row.children;
    updateFormFields[0].value = username;
    updateFormFields[1].value = name;
    updateFormFields[2].value = userType;
    preview.src = profilePicture ? BASE_URL + "uploads/" + profilePicture : placeholderImage;
    user = {username, name, userType, profilePicture};
});

updateForm.addEventListener("submit", e => {
    e.preventDefault();

    const profilePictureChanged = (preview.src === BASE_URL + "uploads/" + user.profilePicture)
        || (preview.src === placeholderImage && user.profilePicture === '');

    if (user.name === updateFormFields[1].value
        && user.userType === updateFormFields[2].value
        && !profilePictureChanged) {
        return;
    }

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const response = JSON.parse(this.response);
            table.querySelectorAll("tbody tr").forEach(row => {
                if (row.children[1].textContent === response.username) {
                    row.children[0].textContent = response.profile_picture;
                    row.children[2].textContent = response.name;
                    row.children[3].textContent = response.userType;
                }
            });
            resetUpdateForm();
        }
    };
    xhttp.open("PUT", BASE_URL + "admin/edit", true);
    xhttp.send(new FormData(updateForm));
});

cancelButton.addEventListener("click", () => {
    resetUpdateForm();
});

removeButton.addEventListener("click", () => {
    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            table.querySelectorAll("tbody tr").forEach(row => {
                if (row.children[1].textContent === updateFormFields[0].value) {
                    table.querySelector("tbody").removeChild(row);
                }
            });
            resetUpdateForm();
        }
    };
    xhttp.open("DELETE", BASE_URL + "admin/edit", true);
    xhttp.send(new FormData(updateForm))
});

imgInput.addEventListener("change", () => {
    const [file] = imgInput.files;
    if (file) {
        preview.src = URL.createObjectURL(file);
        removeProfilePicture.value = "";
    }
});

removeDpButton.addEventListener("click", () => {
    imgInput.value = "";
    preview.src = placeholderImage;
    removeProfilePicture.value = "remove";
});
