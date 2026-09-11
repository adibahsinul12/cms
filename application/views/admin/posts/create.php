<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- SIDEBAR -->
<?php
    $active_menu = 'posts'; // sesuaikan: 'posts', 'categories', 'tags', 'media', 'users', 'settings', 'seo', 'comments', 'logs'
    $this->load->view('admin/partials/sidebar', ['active_menu' => $active_menu]);
?>

            <!-- MAIN CONTENT -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">✏️ Create New Post</h1>
                </div>

                <div id="alert-message"></div>

                <div class="card">
                    <div class="card-body">
                        <form id="post-form">
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
                                    <small class="text-muted d-block mt-2">Contoh: seo_title, seo_description, og_image, dll.</small>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Post</button>
                                <a href="<?= base_url('admin/posts') ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
    <script>
        let customFieldIndex = 0;

        $(document).ready(function() {
            $('#content').summernote({
                placeholder: 'Write your post here...',
                height: 300
            });

            // Load categories
            $.ajax({
                url: '<?= base_url("api/category") ?>',
                method: 'GET',
                success: function(res) {
                    let options = '<option value="">Select Category</option>';
                    res.data.forEach(function(c) {
                        options += `<option value="${c.id}">${c.name}</option>`;
                    });
                    $('#category_id').html(options);
                }
            });

            // Load tags
            $.ajax({
                url: '<?= base_url("api/tag") ?>',
                method: 'GET',
                success: function(res) {
                    let options = '<option value="">Select Tag</option>';
                    res.data.forEach(function(t) {
                        options += `<option value="${t.id}">${t.name}</option>`;
                    });
                    $('#tag_id').html(options);
                }
            });

            // Auto slug
            $('#title').on('keyup', function() {
                let slug = $(this).val().toLowerCase().replace(/[^a-z0-9\s]/g, '').replace(/\s+/g, '-');
                $('#slug').val(slug);
            });

            // Add custom field
            $(document).on('click', '#add-custom-field', function() {
                const html = `
                    <div class="row mb-2 custom-field-row" data-index="${customFieldIndex}">
                        <div class="col-md-4">
                            <input type="text" class="form-control form-control-sm custom-key" placeholder="Meta Key (contoh: seo_title)">
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm custom-value" placeholder="Meta Value">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-sm btn-danger remove-custom-field"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                `;
                $('#custom-fields-container').append(html);
                customFieldIndex++;
            });

            // Remove custom field
            $(document).on('click', '.remove-custom-field', function() {
                $(this).closest('.custom-field-row').remove();
            });

            // Submit form
            $('#post-form').on('submit', function(e) {
                e.preventDefault();

                const data = {
                    author_id: 1,
                    title: $('#title').val(),
                    slug: $('#slug').val(),
                    content: $('#content').summernote('code'),
                    excerpt: $('#excerpt').val(),
                    status: $('#status').val(),
                    category_id: $('#category_id').val() || null,
                    tag_id: $('#tag_id').val() || null
                };

                $.ajax({
                    url: '<?= base_url("api/post/create") ?>',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(res) {
                        const postId = res.data.post_id;
                        const customFields = getCustomFields();

                        // Simpan custom fields
                        let savedCount = 0;
                        if (customFields.length > 0) {
                            customFields.forEach(function(field) {
                                $.ajax({
                                    url: '<?= base_url("api/post/meta") ?>',
                                    method: 'POST',
                                    contentType: 'application/json',
                                    data: JSON.stringify({
                                        post_id: postId,
                                        meta_key: field.key,
                                        meta_value: field.value
                                    }),
                                    complete: function() {
                                        savedCount++;
                                        if (savedCount === customFields.length) {
                                            alert('Post created with custom fields!');
                                            window.location.href = '<?= base_url("admin/posts") ?>';
                                        }
                                    }
                                });
                            });
                        } else {
                            alert('Post created!');
                            window.location.href = '<?= base_url("admin/posts") ?>';
                        }
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON;
                        alert('Error: ' + JSON.stringify(err.errors || err.message));
                    }
                });
            });
        });

        function getCustomFields() {
            const fields = [];
            $('.custom-field-row').each(function() {
                const key = $(this).find('.custom-key').val();
                const value = $(this).find('.custom-value').val();
                if (key && value) {
                    fields.push({ key: key, value: value });
                }
            });
            return fields;
        }
    </script>
</body>
</html>

