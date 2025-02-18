// Restrict the Employee ID to exactly 7 digits only
document.getElementById('employeeIdInput').addEventListener('input', function(event) {
    const input = event.target;
    // Remove non-digit characters
    input.value = input.value.replace(/\D/g, '');

    // Limit input length to exactly 7 digits
    if (input.value.length > 7) {
        input.value = input.value.slice(0, 7);
    }
});

// Form validation before submission
document.getElementById('editForm').addEventListener('submit', function(event) {
    const employeeIdInput = document.getElementById('employeeIdInput');

    if (employeeIdInput.value.length !== 7) {
        alert('Employee ID must be exactly 7 digits.');
        event.preventDefault(); // Prevent form submission
    }
});

// Show the delete confirmation modal
function showDeleteModal(event, formId) {
    event.preventDefault(); // Prevent form submission
    document.getElementById("deleteModal").style.display = "block";
    document.getElementById("confirmDeleteButton").setAttribute("data-form-id", formId);
}

// Hide the modal
function closeModal() {
    document.getElementById("deleteModal").style.display = "none";
}

// Confirm the delete action
function confirmDelete() {
    const formId = document.getElementById("confirmDeleteButton").getAttribute("data-form-id");
    document.getElementById(formId).submit(); // Submit the form
    closeModal(); // Close the modal
}

// Open the edit modal and set up data
function openEditModal(employeeId, firstName, lastName, account_type) {
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('editForm').action = '/user/edit/' + employeeId;

    document.querySelector('.modal h2').innerText = 'Edit Information for ' + firstName + ' ' + lastName;

    let role = 'Not Available';
    if (account_type === 'technical_support') {
        role = 'Technical Support';
    } else if (account_type === 'administrator') {
        role = 'Administrator';
    } else if (account_type === 'employee') {
        role = 'Employee';
    }

    document.getElementById('userInfo').innerHTML = `
        <p><strong>Name:</strong> ${firstName} ${lastName}</p>
        <p><strong>Employee ID:</strong> ${employeeId}</p>
        <p><strong>Role:</strong> ${role}</p>
    `;

    document.getElementById('editOption').value = 'employee_id';
    toggleEditFields('employee_id');

    const employeeIdField = document.getElementById('employeeIdField');
    const passwordField = document.getElementById('passwordField');

    if (document.getElementById('editOption').value === 'employee_id') {
        employeeIdField.querySelector('input').setAttribute('required', true);
        passwordField.querySelector('input').removeAttribute('required');
    } else if (document.getElementById('editOption').value === 'password') {
        passwordField.querySelector('input').setAttribute('required', true);
        employeeIdField.querySelector('input').removeAttribute('required');
    }
}

// Toggle fields based on the selected edit option
// Ensure the correct input field is required based on edit option
function toggleEditFields(option) {
    const employeeIdField = document.getElementById('employeeIdField');
    const passwordField = document.getElementById('passwordField');

    if (option === 'employee_id') {
        employeeIdField.style.display = 'block';
        passwordField.style.display = 'none';
        passwordField.querySelector('input').removeAttribute('required');
        employeeIdField.querySelector('input').setAttribute('required', true);
    } else if (option === 'password') {
        employeeIdField.style.display = 'none';
        passwordField.style.display = 'block';
        passwordField.querySelector('input').setAttribute('required', true);
        employeeIdField.querySelector('input').removeAttribute('required');
    }
}


// Close the edit modal
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Handle edit option changes
document.getElementById('editOption').addEventListener('change', function() {
    toggleEditFields(this.value);

    const employeeIdField = document.getElementById('employeeIdField');
    const passwordField = document.getElementById('passwordField');

    if (this.value === 'employee_id') {
        employeeIdField.querySelector('input').setAttribute('required', true);
        passwordField.querySelector('input').removeAttribute('required');
    } else if (this.value === 'password') {
        passwordField.querySelector('input').setAttribute('required', true);
        employeeIdField.querySelector('input').removeAttribute('required');
    }
});

// Handle password change and session logout
document.getElementById('editForm').addEventListener('submit', function(event) {
    const editOption = document.getElementById('editOption').value;

    if (editOption === 'password') {
        const confirmChange = confirm("Are you sure you want to change your password? You will be logged out after this.");
        if (!confirmChange) {
            event.preventDefault();
            return;
        }

        fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({})
        }).then(response => {
            event.target.submit();
        }).catch(error => {
            alert('Logout failed. Please try again.');
            event.preventDefault();
        });
    }
});

// Show the disable confirmation modal
function showDisableModal(event, formId) {
    event.preventDefault();
    const disableForm = document.getElementById(formId);
    const confirmButton = document.getElementById("confirmDisableButton");

    confirmButton.onclick = function() {
        disableForm.submit();
    };

    document.getElementById("disableModal").style.display = "block";
}

// Close the disable modal
function closeDisableModal() {
    document.getElementById("disableModal").style.display = "none";
}

// Toggle user status
function toggleStatus(event, userId, currentStatus) {
    const button = event.target.closest('button');
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    button.querySelector('span').textContent = newStatus === 'active' ? 'check_circle' : 'do_not_disturb_alt';
    button.textContent = newStatus === 'active' ? 'Enable User' : 'Disable User';
    const form = button.closest('form');
    form.action = `/user/toggle-status/${userId}`;
    form.submit();
}
