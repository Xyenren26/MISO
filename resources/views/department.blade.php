<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments Management</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/departments_style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include jQuery for modal functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    @include('components.sidebar')

    <div class="main-content">
        @include('components.navbar')

        <section class="departments">
            <h1 class="title">Departments Management</h1>

            <!-- Filters and Create Button -->
            <div class="filters">
                <form method="GET" action="{{ route('department.index') }}" class="search-form">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or group" class="search-input">
                    <button type="submit" class="search-button">Search</button>
                    <button type="button" class="create-button" onclick="openCreateModal()">Create New Department</button>
                </form>
            </div>

            <!-- Departments Table -->
            <table class="departments-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Group Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $department)
                        <tr>
                            <td>{{ $department->name }}</td>
                            <td>{{ $department->group_name }}</td>
                            <td class="actions">
                                <button type="button" class="edit-button" onclick="openEditModal({{ $department->id }}, '{{ $department->name }}', '{{ $department->group_name }}')">Edit</button>
                                <button type="button" class="delete-button" onclick="openDeleteModal({{ $department->id }})">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="no-results">No departments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="results-count">
                    @if ($departments->count() > 0)
                        Showing {{ $departments->firstItem() }} to {{ $departments->lastItem() }} of {{ $departments->total() }} results
                    @else
                        Showing 1 to 0 of 0 results
                    @endif
                </div>

                @if ($departments->hasPages())
                    <div class="pagination-buttons">
                        <ul>
                            <li class="{{ $departments->onFirstPage() ? 'disabled' : '' }}">
                                @if ($departments->onFirstPage())
                                    <span>&lsaquo;</span>
                                @else
                                    <a href="{{ $departments->previousPageUrl() }}">&lsaquo;</a>
                                @endif
                            </li>
                            @for ($i = max(1, $departments->currentPage() - 1); $i <= min($departments->lastPage(), $departments->currentPage() + 1); $i++)
                                <li class="{{ $i == $departments->currentPage() ? 'active' : '' }}">
                                    @if ($i == $departments->currentPage())
                                        <span>{{ $i }}</span>
                                    @else
                                        <a href="{{ $departments->url($i) }}">{{ $i }}</a>
                                    @endif
                                </li>
                            @endfor
                            <li class="{{ $departments->hasMorePages() ? '' : 'disabled' }}">
                                @if ($departments->hasMorePages())
                                    <a href="{{ $departments->nextPageUrl() }}">&rsaquo;</a>
                                @else
                                    <span>&rsaquo;</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Create Department Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeCreateModal()">&times;</span>
        <h2>Create New Department</h2>
        <form id="createForm" action="{{ route('department.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="group_name">Group Name</label>
                <input type="text" name="group_name" id="group_name" class="form-control">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-secondary" onclick="closeCreateModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Department Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Department</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit_name">Name</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="edit_group_name">Group Name</label>
                <input type="text" name="group_name" id="edit_group_name" class="form-control">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Department Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeDeleteModal()">&times;</span>
        <h2>Delete Department</h2>
        <p>Are you sure you want to delete this department?</p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="form-actions">
                <button type="submit" class="btn btn-danger">Delete</button>
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Open Create Modal
function openCreateModal() {
    document.getElementById('createModal').classList.add('active');
}

// Close Create Modal
function closeCreateModal() {
    document.getElementById('createModal').classList.remove('active');
}

// Open Edit Modal
function openEditModal(id, name, groupName) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_group_name').value = groupName;
    document.getElementById('editForm').action = `/department/${id}`;
    document.getElementById('editModal').classList.add('active');
}

// Close Edit Modal
function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

// Open Delete Modal
function openDeleteModal(id) {
    document.getElementById('deleteForm').action = `/department/${id}`;
    document.getElementById('deleteModal').classList.add('active');
}

// Close Delete Modal
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

// Close modals when clicking outside the modal content
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
};
</script>
</body>
</html>