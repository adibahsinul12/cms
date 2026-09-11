<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Komentar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { min-height: 100vh; }
        .comment-content {
            max-width: 400px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .reply-indent {
            margin-left: 30px;
            border-left: 3px solid #dee2e6;
            padding-left: 15px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- SIDEBAR -->
<?php
    $active_menu = 'comments'; // sesuaikan: 'posts', 'categories', 'tags', 'media', 'users', 'settings', 'seo', 'comments', 'logs'
    $this->load->view('admin/partials/sidebar', ['active_menu' => $active_menu]);
?>

            <!-- MAIN CONTENT -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">💬 Moderasi Komentar</h1>
                </div>

                <div id="alert-message"></div>

                <!-- Filter Status -->
                <div class="mb-3">
                    <div class="btn-group" role="group">
                        <button class="btn btn-outline-primary filter-btn active" data-status="all">Semua</button>
                        <button class="btn btn-outline-success filter-btn" data-status="approved">Disetujui</button>
                        <button class="btn btn-outline-warning filter-btn" data-status="pending">Pending</button>
                        <button class="btn btn-outline-danger filter-btn" data-status="spam">Spam</button>
                    </div>
                </div>

                <!-- Table Komentar -->
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="comments-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Penulis</th>
                                    <th>Komentar</th>
                                    <th>Post</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="comments-body">
                                <tr><td colspan="7" class="text-center">Loading...</td></tr>
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
        const API_URL = '<?= base_url("api/comments") ?>';
        let currentFilter = 'all';

        $(document).ready(function() {
            loadComments();

            // Filter
            $('.filter-btn').on('click', function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');
                currentFilter = $(this).data('status');
                loadComments();
            });

            // Approve
            $(document).on('click', '.approve-comment', function() {
                const id = $(this).data('id');
                updateStatus(id, 'approved');
            });

            // Spam
            $(document).on('click', '.spam-comment', function() {
                const id = $(this).data('id');
                updateStatus(id, 'spam');
            });

            // Delete
            $(document).on('click', '.delete-comment', function() {
                const id = $(this).data('id');
                if (confirm('Yakin hapus komentar ini?')) {
                    $.ajax({
                        url: `${API_URL}/${id}`,
                        method: 'DELETE',
                        success: function() {
                            showAlert('Komentar berhasil dihapus!', 'success');
                            loadComments();
                        },
                        error: function() {
                            showAlert('Gagal menghapus komentar (API belum tersedia)', 'warning');
                        }
                    });
                }
            });
        });

        function loadComments() {
            let url = API_URL;
            if (currentFilter !== 'all') {
                url += `?status=${currentFilter}`;
            }

            $.ajax({
                url: url,
                method: 'GET',
                success: function(res) {
                    let rows = '';
                    if (res.data && res.data.length > 0) {
                        res.data.forEach(function(c) {
                            const statusBadge = {
                                'approved': '<span class="badge bg-success">Disetujui</span>',
                                'pending': '<span class="badge bg-warning text-dark">Pending</span>',
                                'spam': '<span class="badge bg-danger">Spam</span>'
                            }[c.status] || '';

                            rows += `<tr>
                                <td>${c.id}</td>
                                <td>${c.author_name || c.username || 'Anonim'}</td>
                                <td><div class="comment-content">${c.content}</div></td>
                                <td>${c.post_title || 'Post #' + c.post_id}</td>
                                <td>${statusBadge}</td>
                                <td>${new Date(c.created_at).toLocaleDateString()}</td>
                                <td>
                                    <button class="btn btn-sm btn-success approve-comment" data-id="${c.id}" title="Setujui"><i class="fas fa-check"></i></button>
                                    <button class="btn btn-sm btn-warning spam-comment" data-id="${c.id}" title="Tandai Spam"><i class="fas fa-ban"></i></button>
                                    <button class="btn btn-sm btn-danger delete-comment" data-id="${c.id}" title="Hapus"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        rows = `<tr><td colspan="7" class="text-center">Belum ada komentar</td></tr>`;
                    }
                    $('#comments-body').html(rows);
                },
                error: function() {
                    $('#comments-body').html('<tr><td colspan="7" class="text-center text-danger">API Komentar belum tersedia</td></tr>');
                }
            });
        }

        function updateStatus(id, status) {
            $.ajax({
                url: `${API_URL}/${id}`,
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify({ status: status }),
                success: function() {
                    showAlert('Status komentar berhasil diupdate!', 'success');
                    loadComments();
                },
                error: function() {
                    showAlert('Gagal update status (API belum tersedia)', 'warning');
                }
            });
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
