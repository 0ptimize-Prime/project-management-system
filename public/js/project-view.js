const projectId = document.head.querySelector("[name=project_id][content]").content;
const table = document.getElementById("task-table");

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

    const shiftUp = e.target.classList.contains("shift-up")
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

table.addEventListener("click", e => {
    const target = e.target;
    if (target.nodeName === "I" && (target.classList.contains("shift-up") || target.classList.contains("shift-down"))) {
        shiftRow(e);
    }
});
