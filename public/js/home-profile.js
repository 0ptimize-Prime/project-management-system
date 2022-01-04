const imgInput = document.getElementById("profile_picture");
const preview = document.getElementById("preview");
const removeDpButton = document.getElementById("remove-dp-button");
const removeProfilePicture = document.getElementById("remove_profile_picture");

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
