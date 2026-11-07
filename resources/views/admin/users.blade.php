<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Management - DogTranslation.com Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #58A509;
            --dark-bg: #1f2937;
            --sidebar-bg: #111827;
            --card-border: #e5e7eb;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f9fafb;
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--dark-bg);
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        .content-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Include the sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-users text-primary me-2"></i>
                        User Management
                    </h1>
                    <p class="page-subtitle">Manage and monitor all registered users</p>
                </div>
                <button class="btn btn-primary" onclick="createUser()">
                    <i class="fas fa-plus me-1"></i>
                    Add New User
                </button>
            </div>
        </div>

        <div class="content-card">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search users..." id="userSearch">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Users</option>
                            <option value="verified">Verified Users</option>
                            <option value="unverified">Unverified Users</option>
                        </select>
                        <button class="btn btn-primary">
                            <i class="fas fa-download me-1"></i>
                            Export
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Audio Files</th>
                            <th>Last Activity</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @if(count($users) > 0)
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $user->name }}</div>
                                        <small class="text-muted">@{{ $user->username }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->email_verified ? 'bg-success' : 'bg-warning' }}">
                                    {{ $user->email_verified ? 'Verified' : 'Unverified' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $user->audio_files_count ?? 0 }}</span>
                            </td>
                            <td>
                                {{ $user->last_activity ? \Carbon\Carbon::parse($user->last_activity)->format('M d, Y') : 'Never' }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.user-details', ['id' => $user->id]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-outline-warning" data-user-id="{{ $user->id }}" onclick="editUser(this.dataset.userId)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" data-user-id="{{ $user->id }}" onclick="deleteUser(this.dataset.userId)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-users text-muted" style="font-size: 2rem;"></i>
                                <p class="mt-2 text-muted">No users found</p>
                                <small class="text-muted">Users will appear here when they register through your app</small>
                                <div class="mt-3">
                                    <a href="{{ route('admin.generate-sample-data') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-magic me-1"></i>
                                        Generate Sample Users
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <nav aria-label="Users pagination" class="mt-4">
                <ul class="pagination justify-content-center" id="usersPagination">
                    @if($users->lastPage() > 1)
                    <li class="page-item {{ $users->currentPage() === 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $users->previousPageUrl() }}">Previous</a>
                    </li>

                    @for($i = 1; $i <= $users->lastPage(); $i++)
                        <li class="page-item {{ $users->currentPage() === $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                        </li>
                        @endfor

                        <li class="page-item {{ $users->currentPage() === $users->lastPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
                        </li>
                        @endif
                </ul>

                <div class="text-center mt-2">
                    <small class="text-muted">
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                    </small>
                </div>
            </nav>
        </div>
    </main>

    <!-- User Modal (for both Create and Edit) -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="userForm">
                    <div class="modal-body">
                        <input type="hidden" id="userId" name="id">
                        <div class="mb-3">
                            <label for="userName" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="userName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="userUsername" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="userUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="userEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Password <span id="passwordRequired" class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="userPassword" name="password" placeholder="Leave blank to keep current password">
                            <small class="form-text text-muted" id="passwordHelp">Leave blank when editing to keep current password</small>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="userEmailVerified" name="email_verified" value="1">
                            <label class="form-check-label" for="userEmailVerified">
                                Email Verified
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveUserBtn">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Create new user
        function createUser() {
            // Clear form
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('userPassword').required = true;
            document.getElementById('passwordRequired').style.display = 'inline';
            document.getElementById('passwordHelp').textContent = 'Password is required for new users';
            document.getElementById('userModalLabel').textContent = 'Create New User';
            document.getElementById('saveUserBtn').textContent = 'Create User';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('userModal'));
            modal.show();
        }

        // Edit existing user
        async function editUser(userId) {
            try {
                // Fetch user data
                const response = await fetch(`/admin/users/${userId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.status && result.data) {
                    // Populate form
                    document.getElementById('userId').value = result.data.id;
                    document.getElementById('userName').value = result.data.name;
                    document.getElementById('userUsername').value = result.data.username;
                    document.getElementById('userEmail').value = result.data.email;
                    document.getElementById('userEmailVerified').checked = result.data.email_verified || false;
                    document.getElementById('userPassword').value = '';
                    document.getElementById('userPassword').required = false;
                    document.getElementById('passwordRequired').style.display = 'none';
                    document.getElementById('passwordHelp').textContent = 'Leave blank to keep current password';
                    document.getElementById('userModalLabel').textContent = 'Edit User';
                    document.getElementById('saveUserBtn').textContent = 'Update User';

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('userModal'));
                    modal.show();
                } else {
                    alert('Failed to load user data: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error loading user:', error);
                alert('Failed to load user data. Please try again.');
            }
        }

        async function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone and will also delete all associated audio files.')) {
                return;
            }

            try {
                const response = await fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                const result = await response.json();

                if (result.status) {
                    alert('User deleted successfully!');
                    location.reload();
                } else {
                    alert('Failed to delete user: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error deleting user:', error);
                alert('Failed to delete user. Please try again.');
            }
        }

        // Handle form submission (for both create and edit)
        document.getElementById('userForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const userId = document.getElementById('userId').value;
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            // Convert email_verified to boolean
            data.email_verified = document.getElementById('userEmailVerified').checked;

            // Remove password if empty (for edit)
            if (!data.password && userId) {
                delete data.password;
            }

            // Determine URL and method
            const url = userId ? `/admin/users/${userId}` : '/admin/users';
            const method = userId ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.status) {
                    alert('User ' + (userId ? 'updated' : 'created') + ' successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                    location.reload();
                } else {
                    let errorMsg = result.message || 'Unknown error';
                    if (result.errors) {
                        errorMsg += '\n\n' + Object.values(result.errors).flat().join('\n');
                    }
                    alert('Failed to ' + (userId ? 'update' : 'create') + ' user: ' + errorMsg);
                }
            } catch (error) {
                console.error('Error saving user:', error);
                alert('Failed to save user. Please try again.');
            }
        });
    </script>
</body>

</html>