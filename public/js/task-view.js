const statusRequest=document.getElementById("status-request");
const statusAccept=document.getElementById("status-accept");
const statusDecline=document.getElementById("status-decline");
const form = document.getElementById("comment-form");
const commentsDiv = document.getElementById("comments");

const taskId = document.querySelector("[name=taskId][content]").content;

const addComment = (newComment) => {
    const mainNode = document.createElement("div");
    mainNode.classList.add("card", "my-3");
    mainNode.id = `comment-${newComment['id']}`;

    const headerNode = document.createElement("h5");
    headerNode.classList.add("card-header");
    headerNode.textContent = newComment.name;
    mainNode.appendChild(headerNode);

    const rowNode = document.createElement("div");
    rowNode.classList.add("row", "g-0");

    const colNode1 = document.createElement("div");
    colNode1.classList.add("col-md-1");
    if (!newComment.profile_picture) newComment.profile_picture = "https://via.placeholder.com/100";
    const imgNode = document.createElement("img");
    imgNode.src = newComment.profile_picture;
    imgNode.alt = newComment.username;
    imgNode.classList.add("img-fluid", "img-circle", "m-1")
    colNode1.appendChild(imgNode);
    rowNode.appendChild(colNode1);

    const colNode2 = document.createElement("div");
    colNode2.classList.add("col-md-11");

    const bodyNode = document.createElement("div");
    bodyNode.classList.add("card-body");

    const textNode = document.createElement("p");
    textNode.classList.add("card-text");
    textNode.textContent = newComment.body;
    bodyNode.appendChild(textNode);

    const dateTimeNode = document.createElement("h6");
    dateTimeNode.classList.add("card-subtitle", "mb-2", "initialism", "text-muted");

    const clockIconNode = document.createElement("i");
    clockIconNode.classList.add("fas", "fa-clock");
    dateTimeNode.appendChild(clockIconNode);

    const dateTimeSpanNode = document.createElement("span");
    dateTimeSpanNode.textContent = newComment.created_at;
    dateTimeNode.appendChild(dateTimeSpanNode);
    bodyNode.appendChild(dateTimeNode);
    colNode2.appendChild(bodyNode);

    rowNode.appendChild(colNode2);
    mainNode.appendChild(rowNode);

    commentsDiv.appendChild(mainNode);
};

form.addEventListener("submit", (e) => {
    e.preventDefault();

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            form.reset();
            addComment(JSON.parse(this.response));
        }
    };
    xhttp.open("POST", "../../comment/task/" + taskId, true);
    xhttp.send(new FormData(form));
});

statusRequest.addEventListener("submit", (e) => {
    e.preventDefault();

    console.log("event listener added");
    const xhttp = new XMLHttpRequest();
    console.log("XHTML req created");

    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            location.reload();
        } else if (this.status === 0){ console.log(this.status);}
    };
    var formData = new FormData();
    formData.set('status','PENDING');
    xhttp.open("POST", BASE_URL + "task/status/" + taskId, true);
    xhttp.send(formData);
});
statusAccept.addEventListener("submit", (e) => {
    e.preventDefault();

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            location.reload();
        }

    };
    var formData = new FormData();
    formData.set('status','COMPLETE');
    xhttp.open("POST", BASE_URL + "task/status/" + taskId, true);
    xhttp.send(formData);
});
statusDecline.addEventListener("submit", (e) => {
    e.preventDefault();

    const xhttp = new XMLHttpRequest();
    xhttp.withCredentials = true;
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            location.reload();
        }

    };
    var formData = new FormData();
    formData.set('status','IN_PROGRESS');
    xhttp.open("POST", BASE_URL + "task/status/" + taskId, true);
    xhttp.send(formData);
});