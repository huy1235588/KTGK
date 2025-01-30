document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".edit-btn");
    const popup = document.getElementById("edit-popup");
    const editInput = document.getElementById("edit-input");
    const confirmBtn = document.getElementById("confirm-btn");
    const cancelBtn = document.getElementById("cancel-btn");
    let currentField;

    editButtons.forEach(button => {
        button.addEventListener("click", (event) => {
            currentField = event.target.previousElementSibling;
            editInput.value = currentField.value;
            popup.style.display = "block";
        });
    });

    confirmBtn.addEventListener("click", () => {
        if (currentField) {
            currentField.value = editInput.value;
        }
        popup.style.display = "none";
    });

    cancelBtn.addEventListener("click", () => {
        popup.style.display = "none";
    });
});
