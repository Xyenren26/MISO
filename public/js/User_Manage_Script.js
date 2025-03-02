/// Open the edit modal and set up data
function openEditModal(employeeId, firstName, lastName, accountType) {
    const modal = document.getElementById('editModal');
    const userInfo = document.getElementById('userInfo');
    const editForm = document.getElementById('editForm');

    // Map account type to a user-friendly role
    let roleLabel = '';
    if (accountType === 'end_user') {
        roleLabel = 'End User';
    } else if (accountType === 'technical_support') {
        roleLabel = 'Technical Support';
    } else if (accountType === 'administrator') {
        roleLabel = 'Administrator';
    } else {
        roleLabel = 'Unknown Role';
    }

    // Populate user info
    userInfo.innerHTML = `
        <p><strong>Employee ID:</strong> ${employeeId}</p>
        <p><strong>Name:</strong> ${firstName} ${lastName}</p>
        <p><strong>Role:</strong> ${roleLabel}</p>
    `;

    // Set form action
    editForm.action = `/user/update/${employeeId}`;

    // Show modal
    modal.style.display = 'block';
}


// Close the edit modal
function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'none';
}

// Toggle fields based on the selected edit option
function toggleEditFields(option) {
    const employeeIdField = document.getElementById('employeeIdField');
    const passwordField = document.getElementById('passwordField');

    if (option === 'employee_id') {
        employeeIdField.style.display = 'block';
        passwordField.style.display = 'none';
    } else if (option === 'password') {
        employeeIdField.style.display = 'none';
        passwordField.style.display = 'block';
    }
}

// Open the role modal
function openRoleModal(employeeId, currentRole) {
    const modal = document.getElementById('roleModal');
    const roleForm = document.getElementById('roleForm');

    // Set form action
    roleForm.action = `/user/change-role/${employeeId}`;

    // Set current role
    document.getElementById('newRole').value = currentRole;

    // Show modal
    modal.style.display = 'block';
}

// Close the role modal
function closeRoleModal() {
    const modal = document.getElementById('roleModal');
    modal.style.display = 'none';
}

// Toggle user status
function toggleStatus(event, employeeId, currentStatus) {
    event.preventDefault();

    if (confirm(`Are you sure you want to ${currentStatus === 'active' ? 'disable' : 'enable'} this user?`)) {
        document.getElementById(`disableForm${employeeId}`).submit();
    }
}

// Restrict the Employee ID to exactly 7 digits only
document.getElementById('newEmployeeId').addEventListener('input', function(event) {
    const input = event.target;
    // Remove non-digit characters
    input.value = input.value.replace(/\D/g, '');

    // Limit input length to exactly 7 digits
    if (input.value.length > 7) {
        input.value = input.value.slice(0, 7);
    }
});


// Password validation: At least 6 characters, including one letter, one number, or one special character
function validatePassword(password) {
    const regex = /^(?=.*[A-Za-z])(?=.*\d|[^A-Za-z0-9]).{6,}$/;
    return regex.test(password);
}

document.getElementById('editForm').addEventListener('submit', function(event) {
    const editOption = document.getElementById('editOption').value;
    const employeeIdInput = document.getElementById('newEmployeeId');
    const passwordInput = document.getElementById('newPassword');

    // Remove the required attribute from fields not being edited
    if (editOption === 'employee_id') {
        passwordInput.removeAttribute('required');
        employeeIdInput.setAttribute('required', true);
    } else if (editOption === 'password') {
        employeeIdInput.removeAttribute('required');
        passwordInput.setAttribute('required', true);
    }

    // Validate Employee ID if it's being edited
    if (editOption === 'employee_id' && employeeIdInput.value.length !== 7) {
        alert('Employee ID must be exactly 7 digits.');
        event.preventDefault(); // Prevent form submission
    }

    // Validate Password if it's being edited
    if (editOption === 'password' && !validatePassword(passwordInput.value)) {
        alert('Password must be at least 6 characters and include at least one letter, one number, or one special character.');
        event.preventDefault(); // Prevent form submission
    }
});


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