<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements Management</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/announcements_style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include jQuery for modal functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    @include('components.sidebar')

    <div class="main-content">
        @include('components.navbar')

        <section class="announcements">
            <h1 class="title">Announcements Management</h1>

            <!-- Filters and Create Button -->
            <div class="filters">
                <form method="GET" action="{{ route('announcements.index') }}" class="search-form">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or content" class="search-input">
                    <button type="submit" class="search-button">Search</button>
                    <button type="button" class="create-button" onclick="openCreateModal()">Create New Announcement</button>
                </form>
            </div>

            <!-- Announcements Table -->
            <table class="announcements-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                        <tr>
                            <td>{{ $announcement->title }}</td>
                            <td>{{ Str::limit($announcement->content, 50) }}</td>
                            <td>{{ $announcement->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $announcement->updated_at->format('Y-m-d H:i') }}</td>
                            <td class="actions">
                                <button type="button" class="edit-button" onclick="openEditModal({{ $announcement->id }}, '{{ $announcement->title }}', '{{ $announcement->content }}')">Edit</button>
                                <button type="button" class="delete-button" onclick="openDeleteModal({{ $announcement->id }})">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="no-results">No announcements found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="results-count">
                    @if ($announcements->count() > 0)
                        Showing {{ $announcements->firstItem() }} to {{ $announcements->lastItem() }} of {{ $announcements->total() }} results
                    @else
                        Showing 1 to 0 of 0 results
                    @endif
                </div>

                @if ($announcements->hasPages())
                    <div class="pagination-buttons">
                        <ul>
                            <li class="{{ $announcements->onFirstPage() ? 'disabled' : '' }}">
                                @if ($announcements->onFirstPage())
                                    <span>&lsaquo;</span>
                                @else
                                    <a href="{{ $announcements->previousPageUrl() }}">&lsaquo;</a>
                                @endif
                            </li>
                            @for ($i = max(1, $announcements->currentPage() - 1); $i <= min($announcements->lastPage(), $announcements->currentPage() + 1); $i++)
                                <li class="{{ $i == $announcements->currentPage() ? 'active' : '' }}">
                                    @if ($i == $announcements->currentPage())
                                        <span>{{ $i }}</span>
                                    @else
                                        <a href="{{ $announcements->url($i) }}">{{ $i }}</a>
                                    @endif
                                </li>
                            @endfor
                            <li class="{{ $announcements->hasMorePages() ? '' : 'disabled' }}">
                                @if ($announcements->hasMorePages())
                                    <a href="{{ $announcements->nextPageUrl() }}">&rsaquo;</a>
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

<!-- Create Announcement Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeCreateModal()">&times;</span>
        <h2>Create New Announcement</h2>
        <form id="createForm" action="{{ route('announcements.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-secondary" onclick="closeCreateModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Announcement Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Announcement</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="editTitle">Title</label>
                <input type="text" name="title" id="editTitle" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="editContent">Content</label>
                <textarea name="content" id="editContent" class="form-control" rows="5" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Announcement Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeDeleteModal()">&times;</span>
        <h2>Delete Announcement</h2>
        <p>Are you sure you want to delete this announcement?</p>
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

<script src="{{ asset('js/announcements_script.js') }}"></script>
<script>
    // Modal Functions
    function openCreateModal() {
        document.getElementById('createModal').style.display = 'flex';
    }

    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
    }

    function openEditModal(id, title, content) {
        document.getElementById('editTitle').value = title;
        document.getElementById('editContent').value = content;
        document.getElementById('editForm').action = `/announcements/${id}`;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function openDeleteModal(id) {
        document.getElementById('deleteForm').action = `/announcements/${id}`;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Close modals when clicking outside the modal content
    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    };
</script>
</body>
</html>