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

    // ✅ Handle Verification Modal and Email Resend
    if (triggerBtn && verificationForm) {
        triggerBtn.addEventListener("click", function () {
            // Show modal
            modal.style.display = "block";

            // Send verification request via Fetch API
            fetch(verificationForm.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content"),
                    "Accept": "application/json"
                },
                body: new FormData(verificationForm)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Verification email sent! Check your inbox.");
                } else {
                    alert("Error sending verification email.");
                }
            })
            .catch(error => console.error("Error:", error));
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
                    "Accept": "application/json"
                }
            })
            .then(response => response.text())
            .then(text => {
                console.log("Response Text:", text);
                return JSON.parse(text);
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


document.getElementById("profile-upload").addEventListener("change", function(event) {
    let file = event.target.files[0];
    
    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("profile-picture").src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Auto-submit form after selecting a file
        uploadProfilePicture(file);
    }
});

function uploadProfilePicture(file) {
    let formData = new FormData();
    formData.append("avatar", file);
    formData.append("_token", "{{ csrf_token() }}");

    fetch("{{ route('profile.updateAvatar') }}", {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Profile picture updated successfully!");
        } else {
            alert("Error updating profile picture.");
        }
    })
    .catch(error => console.error("Error:", error));
}

// Function to fetch departments and populate the dropdown
async function populateDepartments() {
    try {
        // Fetch departments from the API
        const response = await fetch('/departments');
        if (!response.ok) throw new Error('Failed to fetch departments');
        const departments = await response.json();

        // Get all select elements with the class 'department-select'
        const selectElements = document.querySelectorAll('.department-select');

        // Loop through each select element
        selectElements.forEach(select => {
            // Clear any existing options
            select.innerHTML = '';

            // Add the default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Select Department';
            select.appendChild(defaultOption);

            // Loop through the grouped departments
            for (const [groupName, groupDepartments] of Object.entries(departments)) {
                // Create an optgroup element
                const optgroup = document.createElement('optgroup');
                optgroup.label = groupName;

                // Loop through the departments in the group
                groupDepartments.forEach(department => {
                    // Create an option element
                    const option = document.createElement('option');
                    option.value = department.name;
                    option.text = department.name;

                    // Preselect the user's department (if applicable)
                    if (department.name === "{{ Auth::user()->department }}") {
                        option.selected = true;
                    }

                    // Append the option to the optgroup
                    optgroup.appendChild(option);
                });

                // Append the optgroup to the select element
                select.appendChild(optgroup);
            }
        });
    } catch (error) {
        console.error('Error fetching departments:', error);
        // Display a user-friendly error message in all select elements
        document.querySelectorAll('.department-select').forEach(select => {
            select.innerHTML = '<option value="">Failed to load departments. Please try again later.</option>';
        });
    }
}

// Call the function to populate the dropdown when the page loads
document.addEventListener('DOMContentLoaded', populateDepartments);