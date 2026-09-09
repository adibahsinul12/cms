<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Tags</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- SIDEBAR -->
            <nav class="col-md-2 d-md-block bg-dark sidebar" style="min-height:100vh;">
                <div class="position-sticky pt-3">
                    <h5 class="text-white text-center py-3">📝 CMS Admin</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= base_url('admin/dashboard') ?>">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= base_url('admin/posts') ?>">
                                <i class="fas fa-file-alt"></i> Posts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= base_url('admin/categories') ?>">
                                <i class="fas fa-tags"></i> Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="<?= base_url('admin/tags') ?>">
                                <i class="fas fa-tag"></i> Tags
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- MAIN CONTENT -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">🔖 Tags</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tagModal">
                        <i class="fas fa-plus"></i> Add Tag
                    </button>
                </div>

                <!-- Alert -->
                <div id="alert-message"></div>

                <!-- Table Tags -->
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="tags-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tags-body">
                                <tr><td colspan="4" class="text-center">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL FORM TAG -->
    <div class="modal fade" id="tagModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="tag-form">
                        <input type="hidden" id="tag-id">
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" id="tag-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug *</label>
                            <input type="text" class="form-control" id="tag-slug" required>
                            <small class="text-muted">URL-friendly version (e.g: artificial-intelligence)</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-tag">Save Tag</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = '<?= base_url("api/tag") ?>';
        let modal = new bootstrap.Modal(document.getElementById('tagModal'));

        $(document).ready(function() {
            loadTags();

            // Auto generate slug
            $('#tag-name').on('keyup', function() {
                let slug = $(this).val()
                    .toLowerCase()
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '-');
                $('#tag-slug').val(slug);
            });

            // Save tag
            $('#save-tag').on('click', function() {
                const id = $('#tag-id').val();
                const data = {
                    name: $('#tag-name').val(),
                    slug: $('#tag-slug').val()
                };

                if (!data.name || !data.slug) {
                    showAlert('Name and Slug are required!', 'danger');
                    return;
                }

                const url = id ? `${API_URL}/${id}` : API_URL;
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(res) {
                        showAlert(id ? 'Tag updated!' : 'Tag created!', 'success');
                        modal.hide();
                        loadTags();
                        resetForm();
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON;
                        showAlert('Error: ' + JSON.stringify(err.errors || err.message), 'danger');
                    }
                });
            });

            // Edit tag
            $(document).on('click', '.edit-tag', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: `${API_URL}/${id}`,
                    method: 'GET',
                    success: function(res) {
                        const t = res.data;
                        $('#tag-id').val(t.id);
                        $('#tag-name').val(t.name);
                        $('#tag-slug').val(t.slug);
                        $('#modalTitle').text('Edit Tag');
                        modal.show();
                    }
                });
            });

            // Delete tag
            $(document).on('click', '.delete-tag', function() {
                const id = $(this).data('id');
                if (confirm('Yakin hapus tag ini?')) {
                    $.ajax({
                        url: `${API_URL}/${id}`,
                        method: 'DELETE',
                        success: function() {
                            showAlert('Tag deleted!', 'success');
                            loadTags();
                        },
                        error: function() {
                            showAlert('Error deleting tag', 'danger');
                        }
                    });
                }
            });

            // Reset modal when hidden
            $('#tagModal').on('hidden.bs.modal', function() {
                resetForm();
            });
        });

        function loadTags() {
            $.ajax({
                url: API_URL,
                method: 'GET',
                success: function(res) {
                    let rows = '';
                    if (res.data.length > 0) {
                        res.data.forEach(function(t) {
                            rows += `<tr>
                                <td>${t.id}</td>
                                <td><span class="badge bg-info">${t.name}</span></td>
                                <td><code>${t.slug}</code></td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-tag" data-id="${t.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-tag" data-id="${t.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        rows = `<tr><td colspan="4" class="text-center">No tags found</td></tr>`;
                    }
                    $('#tags-body').html(rows);
                },
                error: function() {
                    $('#tags-body').html('<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>');
                }
            });
        }

        function resetForm() {
            $('#tag-id').val('');
            $('#tag-name').val('');
            $('#tag-slug').val('');
            $('#modalTitle').text('Add New Tag');
        }

        function showAlert(message, type) {
            const alert = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            $('#alert-message').html(alert);
            setTimeout(() => $('#alert-message').html(''), 5000);
        }
    </script>
</body>
</html>