const imgInput = document.getElementById("profile_picture");
const preview = document.getElementById("preview");
const removeDpButton = document.getElementById("remove-dp-button");
const form = document.querySelector("form");

const placeholderImage = "https://via.placeholder.com/300x300.png";

imgInput.addEventListener("change", () => {
    const [file] = imgInput.files;
    if (file) {
        preview.src = URL.createObjectURL(file);
    }
});

removeDpButton.addEventListener("click", () => {
    imgInput.value = "";
    preview.src = placeholderImage;
});

form.addEventListener("reset", () => {
    preview.src = placeholderImage;
});
