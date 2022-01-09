const projectId = document.head.querySelector("[name=project_id][content]").content;
const table = document.getElementById("task-table");
const newMilestoneModal = document.getElementById("newMilestoneModal");

const statusBadgeColorMap = new Map();
statusBadgeColorMap.set("ASSIGNED", "secondary");
statusBadgeColorMap.set("PENDING", "info");
statusBadgeColorMap.set("COMPLETE", "success");
const statusBadgeColor = (status) => {
    if (statusBadgeColorMap.has(status))
        return statusBadgeColorMap.get(status);
    else
        return "primary";
};

const getItems = (rows) => {
    let i = 1;
    return rows.map(row => {
        return {
            id: row.dataset.id,
            ind: i++,
            type: row.classList.contains("task-row") ? "task" : "milestone"
        };
    });
};

const swap = (arr, a, b) => {
    const temp = arr[a];
    arr[a] = arr[b];
    arr[b] = temp;
};

const shiftRow = (e) => {
    const rows = Array.from(table.querySelectorAll("tbody tr"));

    const shiftUp = e.target.classList.contains("shift-up");
    const row = e.target.closest("tr");
    const index = rows.indexOf(row);

    // No shifting possible
    if (shiftUp && index === 0)
        return;
    if (!shiftUp && index === rows.length - 1)
        return;

    const items = getItems(Array.from(rows));
    const swapIndex = shiftUp ? index - 1 : index + 1;
    items[index].ind = swapIndex + 1;
    items[swapIndex].ind = index + 1;
    swap(items, index, swapIndex);

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                const toSwap = shiftUp ? row.previousElementSibling : row.nextElementSibling;
                row.remove();
                if (shiftUp)
                    toSwap.insertAdjacentElement("beforebegin", row);
                else
                    toSwap.insertAdjacentElement("afterend", row);
            }
        }
    };
    xhttp.open("POST", BASE_URL + "project/reorder", true);
    const formData = new FormData();
    formData.set("id", projectId);
    formData.set("items", JSON.stringify(items));
    xhttp.send(formData);
};

const addMilestone = (milestone) => {
    const tr = document.createElement("tr");
    tr.classList.add("table-info", "milestone-row");
    tr.dataset.id = milestone.id;
    const titleTd = document.createElement("td");
    titleTd.textContent = milestone.title;
    tr.appendChild(titleTd);
    const emptyTd1 = document.createElement("td");
    tr.appendChild(emptyTd1);
    const emptyTd2 = document.createElement("td");
    tr.appendChild(emptyTd2);
    const statusTd = document.createElement("td");
    const statusSpan = document.createElement("span");
    statusSpan.classList.add("badge", "rounded-pill", "bg-" + statusBadgeColor(milestone.status));
    statusSpan.textContent = milestone.status;
    statusTd.appendChild(statusSpan);
    tr.appendChild(statusTd);
    const emptyTd3 = document.createElement("td");
    tr.appendChild(emptyTd3);
    const shiftTd = document.createElement("td");
    shiftTd.innerHTML = "<i class=\"shift-up fas fa-chevron-up\"></i> <i class=\"shift-down fas fa-chevron-down\"></i>";
    tr.appendChild(shiftTd);
    table.querySelector("tbody").appendChild(tr);
};

table.addEventListener("click", e => {
    const target = e.target;
    if (target.nodeName === "I" && (target.classList.contains("shift-up") || target.classList.contains("shift-down"))) {
        shiftRow(e);
    }
});

newMilestoneModal.querySelector("#submit-new-milestone").addEventListener("click", () => {
    const title = newMilestoneModal.querySelector("input").value;

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                const response = JSON.parse(this.response);
                addMilestone({id: response.id, title, status: "CREATED"});
                newMilestoneModal.querySelector("input").value = "";
            }
        }
    }
    xhttp.open("POST", BASE_URL + "milestone/create", true);
    const formData = new FormData();
    formData.set("id", projectId);
    formData.set("title", title);
    xhttp.send(formData);
});
