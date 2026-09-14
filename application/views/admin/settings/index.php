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
            border-radius: 12px;
            padding: 18px;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
            background: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .theme-card:hover {
            border-color: #0d6efd;
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(13, 110, 253, 0.12);
        }
        .theme-card.active {
            border-color: #0d6efd;
            background: #f0f7ff;
            box-shadow: 0 0 0 2px #0d6efd;
        }
        .theme-badge-active {
            display: none;
            position: absolute;
            top: 14px;
            right: 14px;
            background: #0d6efd;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 50px;
        }
        .theme-card.active .theme-badge-active { display: inline-block; }
        .theme-icon-box {
            height: 100px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin-bottom: 14px;
        }
        .theme-dinas { background: linear-gradient(135deg, #0F2A47, #1B4570); color: #E7A33E; }
        .theme-katalog { background: linear-gradient(135deg, #064E3B, #10B981); color: #fff; }
        .theme-company { background: linear-gradient(135deg, #1E3A8A, #3B82F6); color: #fff; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
<?php
    $active_menu = 'settings'; // sesuaikan: 'posts', 'categories', 'tags', 'media', 'users', 'settings', 'seo', 'comments', 'logs'
    $this->load->view('admin/partials/sidebar', ['active_menu' => $active_menu]);
?>

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

                        <!-- TAB TEMA / TEMPLATE -->
                        <div class="tab-pane fade" id="theme">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-1">🎨 Katalog Template Website</h5>
                                            <p class="text-muted small mb-0">Pilih template website yang ingin kamu gunakan. Tata letak akan otomatis berganti secara instan (seperti tema WordPress).</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- TEMPLATE 1: DINAS -->
                                        <div class="col-md-4 mb-3">
                                            <div class="theme-card" data-theme="dinas">
                                                <span class="theme-badge-active"><i class="fas fa-check-circle"></i> Sedang Aktif</span>
                                                <div class="theme-icon-box theme-dinas">
                                                    <i class="fas fa-landmark"></i>
                                                </div>
                                                <span class="badge bg-secondary mb-2" style="width: fit-content;">Pemerintahan & Berita</span>
                                                <h5 class="fw-bold mb-1">Portal Dinas / Berita</h5>
                                                <p class="text-muted small mb-3 flex-grow-1">Format formal untuk portal instansi pemerintah daerah, kantor kecamatan/desa, dan media publikasi berita resmi.</p>
                                                <button type="button" class="btn btn-sm btn-outline-primary w-100 select-theme-btn">Pilih Template</button>
                                            </div>
                                        </div>

                                        <!-- TEMPLATE 2: KATALOG -->
                                        <div class="col-md-4 mb-3">
                                            <div class="theme-card" data-theme="katalog">
                                                <span class="theme-badge-active"><i class="fas fa-check-circle"></i> Sedang Aktif</span>
                                                <div class="theme-icon-box theme-katalog">
                                                    <i class="fas fa-shopping-bag"></i>
                                                </div>
                                                <span class="badge bg-success mb-2" style="width: fit-content;">Toko Online / Bisnis</span>
                                                <h5 class="fw-bold mb-1">Katalog Toko & UMKM</h5>
                                                <p class="text-muted small mb-3 flex-grow-1">Format etalase produk, galeri foto barang dagangan, deskripsi produk, dan tombol chat pemesanan langsung via WhatsApp.</p>
                                                <button type="button" class="btn btn-sm btn-outline-success w-100 select-theme-btn">Pilih Template</button>
                                            </div>
                                        </div>

                                        <!-- TEMPLATE 3: COMPANY PROFILE -->
                                        <div class="col-md-4 mb-3">
                                            <div class="theme-card" data-theme="company">
                                                <span class="theme-badge-active"><i class="fas fa-check-circle"></i> Sedang Aktif</span>
                                                <div class="theme-icon-box theme-company">
                                                    <i class="fas fa-briefcase"></i>
                                                </div>
                                                <span class="badge bg-primary mb-2" style="width: fit-content;">Profil & Lembaga</span>
                                                <h5 class="fw-bold mb-1">Company Profile</h5>
                                                <p class="text-muted small mb-3 flex-grow-1">Landing page profesional untuk profil perusahaan, visi-misi, pilar layanan unggulan, artikel publikasi, dan kontak bisnis.</p>
                                                <button type="button" class="btn btn-sm btn-outline-primary w-100 select-theme-btn">Pilih Template</button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="active_theme" value="dinas">
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

            $(document).on('click', '.theme-card, .select-theme-btn', function(e) {
                const card = $(this).closest('.theme-card');
                const theme = card.data('theme');
                $('.theme-card').removeClass('active');
                card.addClass('active');
                $('#active_theme').val(theme);
            });

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
                        showAlert('Gagal menyimpan pengaturan (API belum tersedia)', 'warning');
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
                            const active = (d.active_theme === 'default') ? 'dinas' : d.active_theme;
                            $('#active_theme').val(active);
                            $(`.theme-card[data-theme="${active}"]`).addClass('active');
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
