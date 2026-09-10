<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { min-height: 100vh; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- SIDEBAR -->
            <nav class="col-md-2 d-md-block bg-dark sidebar">
                <div class="position-sticky pt-3">
                    <h5 class="text-white text-center py-3">📝 CMS Admin</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/posts') ?>"><i class="fas fa-file-alt"></i> Posts</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/categories') ?>"><i class="fas fa-tags"></i> Categories</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/tags') ?>"><i class="fas fa-tag"></i> Tags</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/media') ?>"><i class="fas fa-images"></i> Media</a></li>
                        <li class="nav-item"><a class="nav-link text-white active" href="<?= base_url('admin/users') ?>"><i class="fas fa-users"></i> Users</a></li>
                    </ul>
                </div>
            </nav>

            <!-- MAIN CONTENT -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">👥 Manajemen User</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">
                        <i class="fas fa-plus"></i> Tambah User
                    </button>
                </div>

                <div id="alert-message"></div>

                <!-- Info Box -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6 class="card-title">Total Users</h6>
                                <h3 id="total-users">0</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Users -->
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="users-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Lengkap</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="users-body">
                                <tr><td colspan="7" class="text-center">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL FORM USER -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="user-form">
                        <input type="hidden" id="user-id">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" id="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username *</label>
                            <input type="text" class="form-control" id="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" id="password" placeholder="Min. 6 karakter">
                            <small class="text-muted" id="password-hint" style="display:none;">Kosongkan jika tidak ingin ganti password</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role *</label>
                            <select class="form-select" id="role_id" required>
                                <option value="">Pilih Role</option>
                                <option value="1">Admin</option>
                                <option value="2">Editor</option>
                                <option value="3">Author</option>
                                <option value="4">Subscriber</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="banned">Banned</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-user">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
        let modaconst API_URL = '<?= base_url("api/user") ?>';

        $(document).ready(function() {
            loadUsers();

            $('#save-user').on('click', function() {
                const id = $('#user-id').val();
                const data = {
                    full_name: $('#full_name').val(),
                    username: $('#username').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    role_id: $('#role_id').val(),
                    status: $('#status').val()
                };

                if (!data.full_name || !data.username || !data.email || !data.role_id) {
                    showAlert('Harap isi semua field yang wajib!', 'danger');
                    return;
                }

                if (!id && !data.password) {
                    showAlert('Password wajib diisi untuk user baru!', 'danger');
                    return;
                }

                const url = id ? `${API_URL}/${id}` : API_URL;
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function() {
                        showAlert(id ? 'User berhasil diupdate!' : 'User berhasil ditambahkan!', 'success');
                        modal.hide();
                        loadUsers();
                        resetForm();
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON;
                        showAlert('Error: ' + JSON.stringify(err.errors || err.message), 'danger');
                    }
                });
            });

            $(document).on('click', '.edit-user', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: `${API_URL}/${id}`,
                    method: 'GET',
                    success: function(res) {
                        const u = res.data;
                        $('#user-id').val(u.id);
                        $('#full_name').val(u.full_name);
                        $('#username').val(u.username);
                        $('#email').val(u.email);
                        $('#role_id').val(u.role_id);
                        $('#status').val(u.status);
                        $('#password').val('');
                        $('#password-hint').show();
                        $('#modalTitle').text('Edit User');
                        modal.show();
                    }
                });
            });

            $(document).on('click', '.delete-user', function() {
                const id = $(this).data('id');
                if (confirm('Yakin hapus user ini?')) {
                    $.ajax({
                        url: `${API_URL}/${id}`,
                        method: 'DELETE',
                        success: function() {
                            showAlert('User berhasil dihapus!', 'success');
                            loadUsers();
                        },
                        error: function() {
                            showAlert('Error menghapus user', 'danger');
                        }
                    });
                }
            });

            $('#userModal').on('hidden.bs.modal', function() {
                resetForm();
            });
        });

        function loadUsers() {
            $.ajax({
                url: API_URL,
                method: 'GET',
                success: function(res) {
                    let rows = '';
                    if (res.data && res.data.length > 0) {
                        $('#total-users').text(res.data.length);
                        res.data.forEach(function(u) {
                            const roleBadge = {
                                1: '<span class="badge bg-danger">Admin</span>',
                                2: '<span class="badge bg-warning text-dark">Editor</span>',
                                3: '<span class="badge bg-info text-dark">Author</span>',
                                4: '<span class="badge bg-secondary">Subscriber</span>'
                            }[u.role_id] || '<span class="badge bg-secondary">Unknown</span>';

                            const statusBadge = {
                                'active': '<span class="badge bg-success">Active</span>',
                                'inactive': '<span class="badge bg-secondary">Inactive</span>',
                                'banned': '<span class="badge bg-danger">Banned</span>'
                            }[u.status] || '';

                            rows += `<tr>
                                <td>${u.id}</td>
                                <td>${u.full_name}</td>
                                <td>${u.username}</td>
                                <td>${u.email}</td>
                                <td>${roleBadge}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-user" data-id="${u.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger delete-user" data-id="${u.id}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        rows = `<tr><td colspan="7" class="text-center">Belum ada user</td></tr>`;
                    }
                    $('#users-body').html(rows);
                },
                error: function() {
                    $('#users-body').html('<tr><td colspan="7" class="text-center text-danger">API Users belum tersedia</td></tr>');
                }
            });
        }

        function resetForm() {
            $('#user-id').val('');
            $('#full_name').val('');
            $('#username').val('');
            $('#email').val('');
            $('#password').val('');
            $('#role_id').val('');
            $('#status').val('active');
            $('#modalTitle').text('Tambah User Baru');
            $('#password-hint').hide();
        }

        function showAlert(message, type) {
            const alert = `<div class="alert alert-${type} alert-dismissible fade show">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            $('#alert-message').html(alert);
            setTimeout(() => $('#alert-message').html(''), 5000);
        }
    </script>
</body>
</html>