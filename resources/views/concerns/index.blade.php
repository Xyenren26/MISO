<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Concern Management</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            margin-left: 250px;
        }

        /* Concern Management Title */
        .concern-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .concern-title {
            color: #003067;
            font-size: 28px;
            margin: 0;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            position: relative;
            display: flex;
            align-items: center;
        }

        .concern-title i {
            margin-right: 10px;
        }

        /* Underline animation */
        .concern-title::after {
            content: '';
            display: block;
            width: 0;
            height: 3px;
            background-color: #003067;
            margin-top: 10px;
            border-radius: 2px;
            animation: slideIn 0.8s ease-out 0.5s forwards;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Slide-in animation */
        @keyframes slideIn {
            from {
                width: 0;
            }
            to {
                width: 60px;
            }
        }

        /* Search Bar */
        .concern-search {
            position: relative;
            margin-bottom: 20px;
            width: 100%;
            max-width: 400px;
        }

        .concern-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #003067;
        }

        .concern-search input {
            width: 100%;
            padding: 10px 10px 10px 40px;
            border: 1px solid #003067;
            border-radius: 6px;
            font-size: 14px;
            color: #003067;
            outline: none;
            transition: all 0.3s ease;
        }

        .concern-search input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background-color: #003067;
            color: white;
        }

        .btn-primary:hover {
            background-color: #002147;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .concern-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        .concern-table thead {
            background-color: #003067;
            color: white;
        }

        .concern-table th,
        .concern-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .concern-table th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .concern-table tbody tr {
            transition: background-color 0.3s ease;
        }

        .concern-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Type Indicators */
        .type-indicator {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-main {
            background-color: #007bff;
            color: white;
        }

        .type-sub {
            background-color: #6c757d;
            color: white;
        }

        /* Priority Indicators */
        .priority-indicator {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .priority-urgent {
            background-color: #dc3545;
            color: white;
        }

        .priority-high {
            background-color: #fd7e14;
            color: white;
        }

        .priority-medium {
            background-color: #ffc107;
            color: #212529;
        }

        .priority-low {
            background-color: #28a745;
            color: white;
        }

        /* Assignment Status */
        .assignment-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .assignment-all {
            background-color: #17a2b8;
            color: white;
        }

        .assignment-specific {
            background-color: #6610f2;
            color: white;
        }

        .assignment-none {
            background-color: #e9ecef;
            color: #495057;
        }

        /* Action Buttons */
        .concern-actions {
            display: flex;
            gap: 8px;
        }

        /* Indentation for sub-concerns */
        .ps-4 {
            padding-left: 40px !important;
            display: flex;
            align-items: center;
        }

        .ps-4 i {
            margin-right: 8px;
            color: #6c757d;
        }

        /* Modal Styles */
        .concern-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-in-out;
        }

        .concern-modal.active {
            display: flex;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            border: 2px solid #003067;
            position: relative;
            animation: slideIn 0.3s ease-in-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            color: #003067;
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .modal-title i {
            margin-right: 10px;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #003067;
            transition: color 0.3s ease;
        }

        .btn-close:hover {
            color: #002147;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            color: #003067;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #003067;
            border-radius: 6px;
            font-size: 14px;
            color: #003067;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        }

        /* Delete Modal Header */
        .modal-header.bg-danger {
            background-color: #dc3545;
            color: white;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 20px -30px;
        }

        /* Toast Notification */
        .concern-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
        }

        .toast {
            display: none;
            min-width: 250px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .toast.active {
            display: block;
            animation: slideInToast 0.3s ease-out;
        }

        @keyframes slideInToast {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .toast-header {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .toast-body {
            padding: 15px;
            background-color: #28a745;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .concern-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .concern-search {
                max-width: 100%;
            }

            .concern-table th,
            .concern-table td {
                padding: 8px;
                font-size: 12px;
            }

            .concern-table th {
                font-size: 13px;
            }

            .concern-actions {
                flex-direction: column;
                gap: 5px;
            }

            .btn-sm {
                width: 100%;
                padding: 6px;
                font-size: 12px;
            }

            .modal-content {
                width: 95%;
                padding: 15px;
            }

            .modal-title {
                font-size: 18px;
            }

            .form-control, .form-select {
                padding: 8px;
                font-size: 12px;
            }

            .modal-footer button {
                padding: 8px 16px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    @include('components.sidebar')

    <div class="main-content">
        @include('components.navbar')

        <div class="concern-container">
            <div class="concern-header">
                <h1 class="concern-title">
                    <i class="fas fa-headset"></i>Concern Management
                </h1>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i>Add Concern
                </button>
            </div>

            <div class="concern-search">
                <i class="fas fa-search"></i>
                <input type="text" class="form-control" placeholder="Search concerns..." id="concernSearch">
            </div>

            <div class="table-responsive">
                <table class="concern-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Parent</th>
                            <th>Priority</th>
                            <th>Assigned To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mainConcerns as $concern)
                            <tr>
                                <td class="fw-semibold">{{ $concern->name }}</td>
                                <td><span class="type-indicator type-main">Main</span></td>
                                <td>-</td>
                                <td>
                                    <span class="priority-indicator priority-{{ $concern->default_priority }}">
                                        {{ ucfirst($concern->default_priority) }}
                                    </span>
                                </td>
                                <td>
                                    @if($concern->assign_to_all_tech)
                                        <span class="assignment-status assignment-all">All Tech</span>
                                    @elseif($concern->assignedUser)
                                        <span class="assignment-status assignment-specific">
                                            {{ $concern->assignedUser->name }}
                                        </span>
                                    @else
                                        <span class="assignment-status assignment-none">Unassigned</span>
                                    @endif
                                </td>
                                <td class="concern-actions">
                                    <button class="btn btn-sm btn-primary" onclick="showEditModal({{ $concern->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="showDeleteModal({{ $concern->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @foreach($concern->children as $child)
                                <tr>
                                    <td class="ps-4"><i class="fas fa-level-down-alt"></i>{{ $child->name }}</td>
                                    <td><span class="type-indicator type-sub">Sub</span></td>
                                    <td>{{ $concern->name }}</td>
                                    <td>
                                        <span class="priority-indicator priority-{{ $child->default_priority }}">
                                            {{ ucfirst($child->default_priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($child->assign_to_all_tech)
                                            <span class="assignment-status assignment-all">All Tech</span>
                                        @elseif($child->assignedUser)
                                            <span class="assignment-status assignment-specific">
                                                {{ $child->assignedUser->name }}
                                            </span>
                                        @else
                                            <span class="assignment-status assignment-none">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="concern-actions">
                                        <button class="btn btn-sm btn-primary" onclick="showEditModal({{ $child->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="showDeleteModal({{ $child->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Concern Modal -->
        <div id="addConcernModal" class="concern-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i>Add New Concern</h5>
                    <button type="button" class="btn-close" onclick="closeAddModal()">&times;</button>
                </div>
                <form action="{{ route('concerns.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-label">Concern Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required onchange="toggleParentField()">
                                <option value="">Select Type</option>
                                <option value="main">Main Concern</option>
                                <option value="sub">Sub-Concern</option>
                            </select>
                        </div>
                        <div class="form-group" id="parentGroup" style="display: none;">
                            <label for="parent_id" class="form-label">Parent Concern</label>
                            <select class="form-select" id="parent_id" name="parent_id">
                                <option value="">Select Parent Concern</option>
                                @foreach($mainConcerns as $main)
                                    <option value="{{ $main->id }}">{{ $main->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="priority" class="form-label">Default Priority</label>
                            <select class="form-select" id="priority" name="default_priority" required>
                                <option value="">Select Priority</option>
                                <option value="urgent">Urgent</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="assigned_user_id" class="form-label">Assign To</label>
                            <select class="form-select" id="assigned_user_id" name="assigned_user_id">
                                <option value="">Select Technician</option>
                                <option value="all">All Technical Staff</option>
                                @foreach($technicalStaff as $tech)
                                    <option value="{{ $tech->employee_id }}">{{ $tech->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Concern</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Concern Modal -->
        <div id="editConcernModal" class="concern-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i>Edit Concern</h5>
                    <button type="button" class="btn-close" onclick="closeEditModal()">&times;</button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">
                        <div class="form-group">
                            <label for="editName" class="form-label">Concern Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editType" class="form-label">Type</label>
                            <select class="form-select" id="editType" name="type" required onchange="toggleEditParentField()">
                                <option value="">Select Type</option>
                                <option value="main">Main Concern</option>
                                <option value="sub">Sub-Concern</option>
                            </select>
                        </div>
                        <div class="form-group" id="editParentGroup" style="display: none;">
                            <label for="editParentId" class="form-label">Parent Concern</label>
                            <select class="form-select" id="editParentId" name="parent_id">
                                <option value="">Select Parent Concern</option>
                                @foreach($mainConcerns as $main)
                                    <option value="{{ $main->id }}">{{ $main->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editPriority" class="form-label">Default Priority</label>
                            <select class="form-select" id="editPriority" name="default_priority" required>
                                <option value="">Select Priority</option>
                                <option value="urgent">Urgent</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editAssignedUserId" class="form-label">Assign To</label>
                            <select class="form-select" id="editAssignedUserId" name="assigned_user_id">
                                <option value="">Select Technician</option>
                                <option value="all">All Technical Staff</option>
                                @foreach($technicalStaff as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Concern</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="concern-modal">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i>Confirm Deletion</h5>
                    <button type="button" class="btn-close" onclick="closeDeleteModal()">&times;</button>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this concern? This action cannot be undone.</p>
                        <input type="hidden" id="deleteId" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success Toast -->
        <div class="concern-toast">
            <div id="toast" class="toast">
                <div class="toast-header">
                    <strong class="me-auto">Success</strong>
                    <button type="button" class="btn-close" onclick="hideToast()">&times;</button>
                </div>
                <div class="toast-body" id="toastMessage">
                    @if(session('success'))
                        {{ session('success') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Modal Functions
    function openAddModal() {
        document.getElementById('addConcernModal').classList.add('active');
    }

    function closeAddModal() {
        document.getElementById('addConcernModal').classList.remove('active');
    }

    function toggleParentField() {
        const typeSelect = document.getElementById('type');
        const parentGroup = document.getElementById('parentGroup');
        parentGroup.style.display = typeSelect.value === 'sub' ? 'block' : 'none';
    }

    function toggleEditParentField() {
        const typeSelect = document.getElementById('editType');
        const parentGroup = document.getElementById('editParentGroup');
        parentGroup.style.display = typeSelect.value === 'sub' ? 'block' : 'none';
    }

    // Show edit modal
    function showEditModal(id) {
        fetch(`/concerns/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(concern => {
            document.getElementById('editId').value = concern.id;
            document.getElementById('editName').value = concern.name;
            document.getElementById('editType').value = concern.type;
            document.getElementById('editPriority').value = concern.default_priority;
            
            // Set assigned user or "all" option
            const assignedSelect = document.getElementById('editAssignedUserId');
            if (concern.assign_to_all_tech) {
                assignedSelect.value = 'all';
            } else {
                assignedSelect.value = concern.assigned_user_id || '';
            }
            
            // Handle parent concern field
            const parentGroup = document.getElementById('editParentGroup');
            if (concern.type === 'sub') {
                document.getElementById('editParentId').value = concern.parent_id;
                parentGroup.style.display = 'block';
            } else {
                parentGroup.style.display = 'none';
            }
            
            // Update form action
            document.getElementById('editForm').action = `/concerns/${concern.id}`;
            
            // Show modal
            document.getElementById('editConcernModal').classList.add('active');
        })
        .catch(error => {
            console.error('Error fetching concern data:', error);
            alert('Error loading concern data. Please check console for details.');
        });
    }

    function closeEditModal() {
        document.getElementById('editConcernModal').classList.remove('active');
    }

    // Show delete modal
    function showDeleteModal(id) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').action = `/concerns/${id}`;
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }

    // Toast functions
    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        toastMessage.textContent = message;
        toast.classList.add('active');
        setTimeout(hideToast, 5000);
    }

    function hideToast() {
        document.getElementById('toast').classList.remove('active');
    }

    // Show toast if there's a success message
    @if(session('success'))
        showToast("{{ session('success') }}");
    @endif

    // Search functionality
    document.getElementById('concernSearch').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.concern-table tbody tr');
        
        rows.forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            const type = row.cells[1].textContent.toLowerCase();
            const parent = row.cells[2].textContent.toLowerCase();
            const priority = row.cells[3].textContent.toLowerCase();
            const assigned = row.cells[4].textContent.toLowerCase();
            
            if (name.includes(searchTerm) || type.includes(searchTerm) || 
                parent.includes(searchTerm) || priority.includes(searchTerm) ||
                assigned.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('concern-modal')) {
            event.target.classList.remove('active');
        }
    };
</script>
</body>
</html>