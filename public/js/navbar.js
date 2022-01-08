const BASE_URL = document.head.querySelector("[name=BASE_URL][content]").content;

const notificationUL = document.getElementById("notifications");
const markAsReadLI = document.getElementById("mark-as-read");

notificationUL.addEventListener("click", e => {
    e.preventDefault();

    const li = e.target.nodeName === "A" ?
        e.target.parentElement : e.target.parentElement.parentElement;

    if (li === markAsReadLI || li.nodeName !== "LI")
        return;

    const link = li.children[0].href;
    const id = li.dataset.id;

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            window.location.assign(link);
        }
    };
    xhttp.open("PUT", BASE_URL + "notification/status/" + encodeURIComponent(id), true);
    xhttp.send()
});

markAsReadLI.addEventListener("click", e => {
    e.stopPropagation();

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            Array.from(notificationUL.children).forEach(li => {
                if (li.dataset.id) {
                    const iconElem = li.querySelector("i.fas");
                    iconElem.classList.remove("fa-envelope");
                    iconElem.classList.add("fa-envelope-open");
                }
            });
        }
    }
    xhttp.open("PUT", BASE_URL + "notification/status", true);
    xhttp.send();
});
