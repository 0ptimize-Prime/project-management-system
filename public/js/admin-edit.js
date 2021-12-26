const BASE_URL = document.head.querySelector("[name=BASE_URL][content]").content;

const searchForm = document.getElementById("search-form");
const searchFormFields = searchForm.querySelectorAll("input,select");

let sortOrder = null;
const table = document.getElementById("user-table");

const updateForm = document.getElementById("update-form");
const updateFormFields = updateForm.querySelectorAll("input,select");
const cancelButton = document.getElementById("cancel-button");
const removeButton = document.getElementById("remove-button");

const imgInput = document.getElementById("profile_picture");
const preview = document.getElementById("preview");
const removeDpButton = document.getElementById("remove-dp-button");

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
