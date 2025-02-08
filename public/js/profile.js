document.addEventListener("DOMContentLoaded", function () {
    const triggerBtn = document.getElementById("trigger-modal-btn");
    const modal = document.getElementById("verification-modal");
    const closeModal = document.getElementById("close-modal");
    const verificationForm = document.getElementById("verification-form");

    const editSaveBtn = document.getElementById("edit-save-btn");
    const firstName = document.getElementById("firstname");
    const lastName = document.getElementById("lastname");
    const departmentSelect = document.getElementById("department");
    const currentDepartment = document.getElementById("current-department");
    const phone = document.getElementById("phone_number");
    const form = document.getElementById("profile-form");

    // ✅ Handle Verification Modal
    if (triggerBtn && verificationForm) {
        triggerBtn.addEventListener("click", function () {
            modal.style.display = "block";
        });
    }

    if (closeModal) {
        closeModal.addEventListener("click", function () {
            modal.style.display = "none";
        });
    }

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // ✅ Toggle Edit & Save Mode
    editSaveBtn.addEventListener("click", function () {
        const isEditing = editSaveBtn.getAttribute("data-editing") === "true";

        if (!isEditing) {
            // Enable fields for editing
            firstName.removeAttribute("disabled");
            lastName.removeAttribute("disabled");
            phone.removeAttribute("disabled");

            // Toggle department input
            currentDepartment.style.display = "none";
            departmentSelect.style.display = "block";
            departmentSelect.value = currentDepartment.value;

            editSaveBtn.textContent = "Save";
            editSaveBtn.setAttribute("data-editing", "true");
        } else {
            // Collect form data
            const formData = new FormData(form);

            // Send data using fetch API
            fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content"),
                    "Accept": "application/json" // Ensures Laravel responds with JSON
                }
            })
            .then(response => response.text()) // Convert response to text
            .then(text => {
                console.log("Response Text:", text);
                return JSON.parse(text); // Parse JSON manually
            })
            .then(data => {
                if (data.success) {
                    alert("Profile updated successfully");
                    location.reload();
                } else {
                    alert("Error updating profile: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));

            // Revert back to "Edit" mode
            editSaveBtn.textContent = "Edit";
            editSaveBtn.setAttribute("data-editing", "false");
        }
    });
});
