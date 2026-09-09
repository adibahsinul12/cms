<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Dashboard</title>
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
                            <a class="nav-link text-white" href="<?= base_url('admin/tags') ?>">
                                <i class="fas fa-tag"></i> Tags
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- MAIN -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">📊 Dashboard</h1>
                </div>

                <!-- Statistik -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Posts</h5>
                                <h2 class="card-text" id="total-posts">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Categories</h5>
                                <h2 class="card-text" id="total-categories">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Tags</h5>
                                <h2 class="card-text" id="total-tags">0</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Posts -->
                <div class="card">
                    <div class="card-header">
                        <h5>📄 Recent Posts</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr><th>#</th><th>Title</th><th>Status</th><th>Created</th></tr>
                            </thead>
                            <tbody id="recent-posts">
                                <tr><td colspan="4" class="text-center">Loading...</td></tr>
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
            $.ajax({
                url: '<?= base_url("api/post") ?>',
                method: 'GET',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#total-posts').text(res.pagination.total);
                        let rows = '';
                        if (res.data.length > 0) {
                            res.data.slice(0, 5).forEach(function(p, i) {
                                rows += `<tr>
                                    <td>${i+1}</td>
                                    <td>${p.title}</td>
                                    <td><span class="badge bg-${p.status === 'published' ? 'success' : 'warning'}">${p.status}</span></td>
                                    <td>${new Date(p.created_at).toLocaleDateString()}</td>
                                </tr>`;
                            });
                        } else {
                            rows = `<tr><td colspan="4" class="text-center">No posts yet</td></tr>`;
                        }
                        $('#recent-posts').html(rows);
                    }
                },
                error: function() {
                    $('#recent-posts').html('<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>');
                }
            });
        });
    </script>
</body>
</html>