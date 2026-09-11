<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php
                $active_menu = 'categories'; // atau 'categories' / 'tags' sesuai halamannya
                $this->load->view('admin/partials/sidebar', ['active_menu' => $active_menu]);
            ?>
            <!-- MAIN CONTENT -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">🏷️ Categories</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                        <i class="fas fa-plus"></i> Add Category
                    </button>
                </div>

                <!-- Alert -->
                <div id="alert-message"></div>

                <!-- Table Categories -->
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="categories-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Parent</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="categories-body">
                                <tr><td colspan="5" class="text-center">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL FORM CATEGORY -->
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="category-form">
                        <input type="hidden" id="category-id">
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" id="category-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug *</label>
                            <input type="text" class="form-control" id="category-slug" required>
                            <small class="text-muted">URL-friendly version (e.g: teknologi-web)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Parent Category</label>
                            <select class="form-control" id="category-parent">
                                <option value="">No Parent</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-category">Save Category</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = '<?= base_url("api/category") ?>';
        let modal = new bootstrap.Modal(document.getElementById('categoryModal'));
        let isEdit = false;

        $(document).ready(function() {
            loadCategories();

            // Auto generate slug
            $('#category-name').on('keyup', function() {
                let slug = $(this).val()
                    .toLowerCase()
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '-');
                $('#category-slug').val(slug);
            });

            // Save category
            $('#save-category').on('click', function() {
                const id = $('#category-id').val();
                const data = {
                    name: $('#category-name').val(),
                    slug: $('#category-slug').val(),
                    parent_id: $('#category-parent').val() || null
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
                        showAlert(id ? 'Category updated!' : 'Category created!', 'success');
                        modal.hide();
                        loadCategories();
                        resetForm();
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON;
                        showAlert('Error: ' + JSON.stringify(err.errors || err.message), 'danger');
                    }
                });
            });

            // Edit category
            $(document).on('click', '.edit-category', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: `${API_URL}/${id}`,
                    method: 'GET',
                    success: function(res) {
                        const c = res.data;
                        $('#category-id').val(c.id);
                        $('#category-name').val(c.name);
                        $('#category-slug').val(c.slug);
                        $('#category-parent').val(c.parent_id || '');
                        $('#modalTitle').text('Edit Category');
                        isEdit = true;
                        modal.show();
                    }
                });
            });

            // Delete category
            $(document).on('click', '.delete-category', function() {
                const id = $(this).data('id');
                if (confirm('Yakin hapus kategori ini?')) {
                    $.ajax({
                        url: `${API_URL}/${id}`,
                        method: 'DELETE',
                        success: function() {
                            showAlert('Category deleted!', 'success');
                            loadCategories();
                        },
                        error: function() {
                            showAlert('Error deleting category', 'danger');
                        }
                    });
                }
            });

            // Reset modal when hidden
            $('#categoryModal').on('hidden.bs.modal', function() {
                resetForm();
            });
        });

        function loadCategories() {
            $.ajax({
                url: API_URL,
                method: 'GET',
                success: function(res) {
                    let rows = '';
                    if (res.data.length > 0) {
                        res.data.forEach(function(c) {
                            rows += `<tr>
                                <td>${c.id}</td>
                                <td>${c.name}</td>
                                <td><code>${c.slug}</code></td>
                                <td>${c.parent_id || '-'}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-category" data-id="${c.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-category" data-id="${c.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        rows = `<tr><td colspan="5" class="text-center">No categories found</td></tr>`;
                    }
                    $('#categories-body').html(rows);

                    // Load parent options
                    let options = '<option value="">No Parent</option>';
                    res.data.forEach(function(c) {
                        options += `<option value="${c.id}">${c.name}</option>`;
                    });
                    $('#category-parent').html(options);
                },
                error: function() {
                    $('#categories-body').html('<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>');
                }
            });
        }

        function resetForm() {
            $('#category-id').val('');
            $('#category-name').val('');
            $('#category-slug').val('');
            $('#category-parent').val('');
            $('#modalTitle').text('Add New Category');
            isEdit = false;
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
