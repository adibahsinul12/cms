<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-md-block bg-dark sidebar">
                <div class="position-sticky pt-3">
                    <h5 class="text-white text-center py-3">📝 CMS Admin</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link text-white active" href="<?= base_url('admin/posts') ?>"><i class="fas fa-file-alt"></i> Posts</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/categories') ?>"><i class="fas fa-tags"></i> Categories</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/tags') ?>"><i class="fas fa-tag"></i> Tags</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/media') ?>"><i class="fas fa-images"></i> Media</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/users') ?>"><i class="fas fa-users"></i> Users</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/settings') ?>"><i class="fas fa-cog"></i> Settings</a></li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">✏️ Edit Post</h1>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#revisionModal">
                        <i class="fas fa-history"></i> Revisions
                    </button>
                </div>

                <div id="alert-message"></div>

                <div class="card">
                    <div class="card-body">
                        <form id="post-form">
                            <input type="hidden" id="post-id" value="<?= $post_id ?>">
                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" class="form-control" id="title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slug *</label>
                                <input type="text" class="form-control" id="slug" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea class="form-control" id="content" rows="10"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Excerpt</label>
                                <textarea class="form-control" id="excerpt" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control" id="status">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                            <option value="private">Private</option>
                                            <option value="scheduled">Scheduled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-control" id="category_id">
                                            <option value="">Select Category</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tag</label>
                                        <select class="form-control" id="tag_id">
                                            <option value="">Select Tag</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- CUSTOM FIELDS -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-cog"></i> Custom Fields</h5>
                                </div>
                                <div class="card-body">
                                    <div id="custom-fields-container"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-custom-field">
                                        <i class="fas fa-plus"></i> Tambah Custom Field
                                    </button>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Post</button>
                                <a href="<?= base_url('admin/posts') ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL REVISIONS -->
    <div class="modal fade" id="revisionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-history"></i> Post Revisions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="revision-list"><p class="text-muted">Loading...</p></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>
    <script>
        const POST_ID = <?= $post_id ?>;
        const API_URL = '<?= base_url("api/post") ?>';
        let customFieldIndex = 0;

        $(document).ready(function() {
            $('#content').summernote({ placeholder: 'Write your post...', height: 300 });

            loadPost();
            loadCategories();
            loadTags();
            loadCustomFields();

            $('#revisionModal').on('shown.bs.modal', function() {
                loadRevisions();
            });

            // Add custom field
            $(document).on('click', '#add-custom-field', function() {
                const html = `
                    <div class="row mb-2 custom-field-row">
                        <div class="col-md-4"><input type="text" class="form-control form-control-sm custom-key" placeholder="Meta Key"></div>
                        <div class="col-md-7"><input type="text" class="form-control form-control-sm custom-value" placeholder="Meta Value"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-sm btn-danger remove-custom-field"><i class="fas fa-times"></i></button></div>
                    </div>
                `;
                $('#custom-fields-container').append(html);
            });

            $(document).on('click', '.remove-custom-field', function() {
                $(this).closest('.custom-field-row').remove();
            });

            // Submit
            $('#post-form').on('submit', function(e) {
                e.preventDefault();
                const data = {
                    title: $('#title').val(),
                    slug: $('#slug').val(),
                    content: $('#content').summernote('code'),
                    excerpt: $('#excerpt').val(),
                    status: $('#status').val(),
                    category_id: $('#category_id').val() || null,
                    tag_id: $('#tag_id').val() || null
                };

                $.ajax({
                    url: `${API_URL}/update/${POST_ID}`,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function() {
                        // Save custom fields
                        const fields = getCustomFields();
                        let saved = 0;
                        if (fields.length > 0) {
                            fields.forEach(function(f) {
                                $.ajax({
                                    url: '<?= base_url("api/post/meta") ?>',
                                    method: 'POST',
                                    contentType: 'application/json',
                                    data: JSON.stringify({ post_id: POST_ID, meta_key: f.key, meta_value: f.value }),
                                    complete: function() {
                                        saved++;
                                        if (saved === fields.length) {
                                            alert('Post updated!');
                                        }
                                    }
                                });
                            });
                        } else {
                            alert('Post updated!');
                        }
                    },
                    error: function(xhr) {
                        alert('Error: ' + JSON.stringify(xhr.responseJSON));
                    }
                });
            });
        });

        function loadPost() {
            $.ajax({
                url: `${API_URL}/detail/${POST_ID}`,
                method: 'GET',
                success: function(res) {
                    const p = res.data;
                    $('#title').val(p.title);
                    $('#slug').val(p.slug);
                    $('#content').summernote('code', p.content || '');
                    $('#excerpt').val(p.excerpt);
                    $('#status').val(p.status);
                }
            });
        }

        function loadCategories() {
            $.ajax({
                url: '<?= base_url("api/category") ?>',
                method: 'GET',
                success: function(res) {
                    let opts = '<option value="">Select Category</option>';
                    res.data.forEach(c => opts += `<option value="${c.id}">${c.name}</option>`);
                    $('#category_id').html(opts);
                }
            });
        }

        function loadTags() {
            $.ajax({
                url: '<?= base_url("api/tag") ?>',
                method: 'GET',
                success: function(res) {
                    let opts = '<option value="">Select Tag</option>';
                    res.data.forEach(t => opts += `<option value="${t.id}">${t.name}</option>`);
                    $('#tag_id').html(opts);
                }
            });
        }

        function loadCustomFields() {
            $.ajax({
                url: '<?= base_url("api/post/meta/") ?>' + POST_ID,
                method: 'GET',
                success: function(res) {
                    if (res.data && res.data.length > 0) {
                        res.data.forEach(function(f) {
                            const html = `
                                <div class="row mb-2 custom-field-row">
                                    <div class="col-md-4"><input type="text" class="form-control form-control-sm custom-key" value="${f.meta_key}"></div>
                                    <div class="col-md-7"><input type="text" class="form-control form-control-sm custom-value" value="${f.meta_value || ''}"></div>
                                    <div class="col-md-1"><button type="button" class="btn btn-sm btn-danger remove-custom-field"><i class="fas fa-times"></i></button></div>
                                </div>
                            `;
                            $('#custom-fields-container').append(html);
                        });
                    }
                }
            });
        }

        function getCustomFields() {
            const fields = [];
            $('.custom-field-row').each(function() {
                const key = $(this).find('.custom-key').val();
                const value = $(this).find('.custom-value').val();
                if (key) fields.push({ key: key, value: value });
            });
            return fields;
        }

        function loadRevisions() {
            $.ajax({
                url: `${API_URL}/revisions/${POST_ID}`,
                method: 'GET',
                success: function(res) {
                    let html = '';
                    if (res.data.length > 0) {
                        html = '<div class="list-group">';
                        res.data.forEach(function(r) {
                            html += `<div class="list-group-item">
                                <h6>${r.title}</h6>
                                <small>${new Date(r.created_at).toLocaleString()}</small>
                            </div>`;
                        });
                        html += '</div>';
                    } else {
                        html = '<p class="text-muted">No revisions</p>';
                    }
                    $('#revision-list').html(html);
                }
            });
        }
    </script>
</body>
</html>