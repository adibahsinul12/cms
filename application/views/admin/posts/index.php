<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar sama seperti dashboard -->
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
                    <h1 class="h2">📄 Posts</h1>
                    <a href="<?= base_url('admin/posts/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Post
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="posts-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="posts-body">
                                <tr><td colspan="6" class="text-center">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            loadPosts();

            function loadPosts() {
                $.ajax({
                    url: '<?= base_url("api/post") ?>',
                    method: 'GET',
                    success: function(res) {
                        let rows = '';
                        if (res.data.length > 0) {
                            res.data.forEach(function(p) {
                                rows += `<tr>
                                    <td>${p.id}</td>
                                    <td>${p.title}</td>
                                    <td>${p.category_name || '-'}</td>
                                    <td><span class="badge bg-${p.status === 'published' ? 'success' : 'warning'}">${p.status}</span></td>
                                    <td>${new Date(p.created_at).toLocaleDateString()}</td>
                                    <td>
                                        <a href="<?= base_url('admin/posts/edit/') ?>${p.id}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger delete-post" data-id="${p.id}"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>`;
                            });
                        } else {
                            rows = `<tr><td colspan="6" class="text-center">No posts found</td></tr>`;
                        }
                        $('#posts-body').html(rows);
                    },
                    error: function() {
                        $('#posts-body').html('<tr><td colspan="6" class="text-center text-danger">Error loading data</td></tr>');
                    }
                });
            }

            // Delete post
            $(document).on('click', '.delete-post', function() {
                const id = $(this).data('id');
                aif (confirm('Yakin hapus post ini?')) {
                    $.ajax({
                        url: '<?= base_url("api/post/") ?>' + id,
                        method: 'DELETE',
                        success: function() {
                            alert('Post deleted!');
                            loadPosts();
                        },
                        error: function() {
                            alert('Error deleting post');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>