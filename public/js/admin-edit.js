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
