<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { min-height: 100vh; }
        .theme-card {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .theme-card:hover {
            border-color: #0d6efd;
            transform: translateY(-3px);
        }
        .theme-card.active {
            border-color: #0d6efd;
            background: #f0f7ff;
            box-shadow: 0 0 15px rgba(13, 110, 253, 0.3);
        }
        .theme-preview {
            width: 100%;
            height: 100px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .theme-1 { background: linear-gradient(135deg, #667eea, #764ba2); }
        .theme-2 { background: linear-gradient(135deg, #11998e, #38ef7d); }
        .theme-3 { background: linear-gradient(135deg, #f12711, #f5af19); }
        .theme-4 { background: linear-gradient(135deg, #232526, #414345); }
        .theme-5 { background: linear-gradient(135deg, #2193b0, #6dd5ed); }
        .theme-6 { background: linear-gradient(135deg, #ee9ca7, #ffdde1); }
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
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/posts') ?>"><i class="fas fa-file-alt"></i> Posts</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/categories') ?>"><i class="fas fa-tags"></i> Categories</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/tags') ?>"><i class="fas fa-tag"></i> Tags</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/media') ?>"><i class="fas fa-images"></i> Media</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/users') ?>"><i class="fas fa-users"></i> Users</a></li>
                        <li class="nav-item"><a class="nav-link text-white active" href="<?= base_url('admin/settings') ?>"><i class="fas fa-cog"></i> Settings</a></li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">⚙️ Pengaturan Umum</h1>
                </div>

                <div id="alert-message"></div>

                <form id="settings-form">
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general" type="button">
                                <i class="fas fa-globe"></i> Umum
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#theme" type="button">
                                <i class="fas fa-palette"></i> Tema
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- TAB UMUM -->
                        <div class="tab-pane fade show active" id="general">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Situs</label>
                                        <input type="text" class="form-control" id="site_name" placeholder="CMS Indonesia">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi Situs</label>
                                        <textarea class="form-control" id="site_description" rows="3" placeholder="Deskripsi singkat situs"></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email Admin</label>
                                                <input type="email" class="form-control" id="admin_email" placeholder="admin@cms.com">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">URL Situs</label>
                                                <input type="text" class="form-control" id="site_url" placeholder="http://localhost/cms">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Footer Text</label>
                                        <input type="text" class="form-control" id="footer_text" placeholder="© 2026 CMS Indonesia">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB TEMA -->
                        <div class="tab-pane fade" id="theme">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-3">Pilih Tema</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="theme-card" data-theme="theme-1">
                                                <div class="theme-preview theme-1"></div>
                                                <h6>Purple</h6>
                                                <small class="text-muted">Elegan & Modern</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="theme-card" data-theme="theme-2">
                                                <div class="theme-preview theme-2"></div>
                                                <h6>Green</h6>
                                                <small class="text-muted">Segar & Natural</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="theme-card" data-theme="theme-3">
                                                <div class="theme-preview theme-3"></div>
                                                <h6>Orange</h6>
                                                <small class="text-muted">Hangat & Ramah</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="theme-card" data-theme="theme-4">
                                                <div class="theme-preview theme-4"></div>
                                                <h6>Dark</h6>
                                                <small class="text-muted">Elegan & Misterius</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="theme-card" data-theme="theme-5">
                                                <div class="theme-preview theme-5"></div>
                                                <h6>Blue</h6>
                                                <small class="text-muted">Profesional</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="theme-card" data-theme="theme-6">
                                                <div class="theme-preview theme-6"></div>
                                                <h6>Pink</h6>
                                                <small class="text-muted">Cantik & Lembut</small>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="active_theme" value="theme-1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = '<?= base_url("api/settings") ?>';

        $(document).ready(function() {
            loadSettings();

            // Pilih tema
            $(document).on('click', '.theme-card', function() {
                $('.theme-card').removeClass('active');
                $(this).addClass('active');
                $('#active_theme').val($(this).data('theme'));
            });

            // Submit
            $('#settings-form').on('submit', function(e) {
                e.preventDefault();
                const data = {
                    site_name: $('#site_name').val(),
                    site_description: $('#site_description').val(),
                    admin_email: $('#admin_email').val(),
                    site_url: $('#site_url').val(),
                    footer_text: $('#footer_text').val(),
                    active_theme: $('#active_theme').val()
                };

                $.ajax({
                    url: API_URL,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function() {
                        showAlert('Pengaturan berhasil disimpan!', 'success');
                    },
                    error: function() {
                        showAlert('Gagal menyimpan pengaturan', 'danger');
                    }
                });
            });
        });

        function loadSettings() {
            $.ajax({
                url: API_URL,
                method: 'GET',
                success: function(res) {
                    if (res.data) {
                        const d = res.data;
                        $('#site_name').val(d.site_name || '');
                        $('#site_description').val(d.site_description || '');
                        $('#admin_email').val(d.admin_email || '');
                        $('#site_url').val(d.site_url || '');
                        $('#footer_text').val(d.footer_text || '');
                        if (d.active_theme) {
                            $('#active_theme').val(d.active_theme);
                            $(`.theme-card[data-theme="${d.active_theme}"]`).addClass('active');
                        }
                    }
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