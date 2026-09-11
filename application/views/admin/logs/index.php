<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Audit Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { min-height: 100vh; }
        .log-action {
            max-width: 400px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
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
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/users') ?>"><i class="fas fa-users"></i> Users</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/comments') ?>"><i class="fas fa-comments"></i> Komentar</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/settings') ?>"><i class="fas fa-cog"></i> Settings</a></li>
                        <li class="nav-item"><a class="nav-link text-white active" href="<?= base_url('admin/logs') ?>"><i class="fas fa-history"></i> Audit Log</a></li>
                    </ul>
                </div>
            </nav>

            <!-- MAIN CONTENT -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">📊 Audit Log</h1>
                    <button class="btn btn-outline-secondary" onclick="loadLogs()"><i class="fas fa-sync"></i> Refresh</button>
                </div>

                <div id="alert-message"></div>

                <!-- Info Box -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6>Total Logs</h6>
                                <h3 id="total-logs">0</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Logs -->
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="logs-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Aksi</th>
                                    <th>IP Address</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="logs-body">
                                <tr><td colspan="5" class="text-center">Loading...</td></tr>
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
        const API_URL = '<?= base_url("api/logs") ?>';

        $(document).ready(function() {
            loadLogs();
        });

        function loadLogs() {
            $('#logs-body').html('<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');

            $.ajax({
                url: API_URL,
                method: 'GET',
                success: function(res) {
                    let rows = '';
                    if (res.data && res.data.length > 0) {
                        $('#total-logs').text(res.data.length);
                        res.data.forEach(function(log) {
                            const icon = getActionIcon(log.action);
                            rows += `<tr>
                                <td>${log.id}</td>
                                <td>${log.user_name || 'System'}</td>
                                <td><div class="log-action">${icon} ${log.action}</div></td>
                                <td><code>${log.ip_address || '-'}</code></td>
                                <td>${new Date(log.created_at).toLocaleString()}</td>
                            </tr>`;
                        });
                    } else {
                        $('#total-logs').text('0');
                        rows = `<tr><td colspan="5" class="text-center">Belum ada log aktivitas</td></tr>`;
                    }
                    $('#logs-body').html(rows);
                },
                error: function() {
                    $('#logs-body').html('<tr><td colspan="5" class="text-center text-danger">API Logs belum tersedia</td></tr>');
                }
            });
        }

        function getActionIcon(action) {
            if (action.includes('Deleted')) return '<i class="fas fa-trash text-danger"></i>';
            if (action.includes('registered')) return '<i class="fas fa-user-plus text-success"></i>';
            if (action.includes('Updated')) return '<i class="fas fa-edit text-warning"></i>';
            if (action.includes('Created')) return '<i class="fas fa-plus text-primary"></i>';
            return '<i class="fas fa-info-circle text-info"></i>';
        }
    </script>
</body>
</html>