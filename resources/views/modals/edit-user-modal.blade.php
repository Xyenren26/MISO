<!-- resources/views/modals/edit-user-modal.blade.php -->
<div class="modal" id="editUserModal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Edit User</h2>
        <form action="{{ route('user.update', $user->employee_id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="{{ $user->first_name }}">

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="{{ $user->last_name }}">

            <label for="email">Email:</label>
            <input type="email" name="email" value="{{ $user->email }}">

            <button type="submit">Update</button>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('editUserModal').style.display = 'block';
    }
    
    function closeModal() {
        document.getElementById('editUserModal').style.display = 'none';
    }
</script>
