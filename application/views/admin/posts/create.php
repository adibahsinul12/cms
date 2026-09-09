<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Summernote WYSIWYG -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block bg-dark sidebar" style="min-height:100vh;">
                <div class="position-sticky pt-3">
                    <h5 class="text-white text-center py-3">📝 CMS Admin</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/posts') ?>"><i class="fas fa-file-alt"></i> Posts</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/categories') ?>"><i class="fas fa-tags"></i> Categories</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/tags') ?>"><i class="fas fa-tag"></i> Tags</a></li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">✏️ Create New Post</h1>
                </div>

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
                                <small class="text-muted">URL-friendly version of the title</small>
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
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Post</button>
                            <a href="<?= base_url('admin/posts') ?>" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Summernote -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Init Summernote WYSIWYG
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

            // Auto generate slug from title
            $('#title').on('keyup', function() {
                let slug = $(this).val()
                    .toLowerCase()
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '-');
                $('#slug').val(slug);
            });

            // Submit form
            $('#post-form').on('submit', function(e) {
                e.preventDefault();
                
                const data = {
                    author_id: 1, // Temporary, nanti diganti dengan session
                    title: $('#title').val(),
                    slug: $('#slug').val(),
                    content: $('#content').summernote('code'),
                    excerpt: $('#excerpt').val(),
                    status: $('#status').val(),
                    category_id: $('#category_id').val() || null,
                    tag_id: $('#tag_id').val() || null
                };

                $.ajax({
                    url: '<?= base_url("api/post") ?>',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(res) {
                        alert('Post created successfully!');
                        window.location.href = '<?= base_url("admin/posts") ?>';
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON;
                        alert('Error: ' + JSON.stringify(error.errors || error.message));
                    }
                });
            });
        });
    </script>
</body>
</html>