const imgInput = document.getElementById("profile_picture");
const preview = document.getElementById("preview");
const removeDpButton = document.getElementById("remove-dp-button");
const removeProfilePicture = document.getElementById("remove_profile_picture");
const newPasswordInput = document.getElementById("new_password");
const confirmPasswordInput = document.getElementById("confirm_password");
const passwordForm = document.getElementById("password-form");
const passwordValidFeedbackDiv = document.getElementById("password-valid-feedback");
const passwordInvalidFeedbackDiv = document.getElementById("password-invalid-feedback");

const placeholderImage = "https://via.placeholder.com/300x300.png";

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

const validateConfirmPassword = () => {
    if (newPasswordInput.value === "") {
        passwordValidFeedbackDiv.style.display = null;
        passwordInvalidFeedbackDiv.style.display = null;
    } else if (newPasswordInput.value === confirmPasswordInput.value) {
        passwordValidFeedbackDiv.style.display = 'block';
        passwordInvalidFeedbackDiv.style.display = null;
    } else {
        passwordValidFeedbackDiv.style.display = null;
        passwordInvalidFeedbackDiv.style.display = 'block';
    }
}

newPasswordInput.addEventListener("blur", validateConfirmPassword);
confirmPasswordInput.addEventListener("blur", validateConfirmPassword);

passwordForm.addEventListener('submit', function (event) {
    if (newPasswordInput.value !== confirmPasswordInput.value) {
        event.preventDefault()
        event.stopPropagation()
    }
}, false)
