<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Saya - CMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { min-height: 100vh; }

        /* Template Selector Cards */
        .tpl-card {
            border: 2px solid #dee2e6;
            border-radius: 14px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
            background: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .tpl-card:hover {
            border-color: #0d6efd;
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(13,110,253,0.12);
        }
        .tpl-card.active {
            border-color: #0d6efd;
            background: #f0f7ff;
            box-shadow: 0 0 0 2px #0d6efd;
        }
        .tpl-badge-active {
            display: none;
            position: absolute;
            top: 14px; right: 14px;
            background: #0d6efd; color: #fff;
            font-size: 11px; font-weight: 700;
            padding: 3px 10px; border-radius: 50px;
        }
        .tpl-card.active .tpl-badge-active { display: inline-block; }
        .tpl-icon {
            height: 90px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin-bottom: 14px;
        }
        .tpl-dinas   { background: linear-gradient(135deg, #0F2A47, #1B4570); color: #E7A33E; }
        .tpl-katalog { background: linear-gradient(135deg, #064E3B, #10B981); color: #fff; }
        .tpl-company { background: linear-gradient(135deg, #1E3A8A, #3B82F6); color: #fff; }

        .site-url-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            font-family: monospace;
            font-size: 14px;
            color: #1e3a8a;
            font-weight: 600;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
<?php
    $active_menu = 'my_site';
    $this->load->view('admin/partials/sidebar', ['active_menu' => $active_menu]);
?>

            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">🌐 Website Saya</h1>
                    <a id="btn-preview-site" href="#" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-external-link-alt"></i> Lihat Website Saya
                    </a>
                </div>

                <div id="alert-message"></div>

                <!-- URL Website Pribadi -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2"><i class="fas fa-link text-primary"></i> Alamat Website Kamu</h6>
                        <div class="site-url-box" id="site-url-display">Memuat...</div>
                        <small class="text-muted mt-2 d-block">Bagikan link ini kepada siapa saja agar mereka bisa mengunjungi website kamu!</small>
                    </div>
                </div>

                <div class="row">
                    <!-- KOLOM KIRI: Info & Nomor WA -->
                    <div class="col-md-5 mb-4">
                        <div class="card h-100">
                            <div class="card-header fw-bold"><i class="fas fa-info-circle text-primary"></i> Info Website</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama / Judul Website</label>
                                    <input type="text" class="form-control" id="site_title" placeholder="Contoh: Toko Berkah, Portal Desa Melati, PT Nusantara...">
                                    <small class="text-muted">Tampil sebagai nama di navbar website kamu.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Slogan / Deskripsi Singkat</label>
                                    <textarea class="form-control" id="site_bio" rows="3" placeholder="Contoh: Jualan murah meriah, gratis ongkir se-kota!"></textarea>
                                    <small class="text-muted">Tampil sebagai headline di bagian hero website kamu.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold"><i class="fab fa-whatsapp text-success"></i> Nomor WhatsApp (Tanpa +62)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+62</span>
                                        <input type="text" class="form-control" id="phone_wa" placeholder="81234567890">
                                    </div>
                                    <small class="text-muted">Dipakai untuk tombol "Pesan via WA" di template Toko.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Pilih Template -->
                    <div class="col-md-7 mb-4">
                        <div class="card h-100">
                            <div class="card-header fw-bold"><i class="fas fa-palette text-primary"></i> Pilih Template Website</div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">Klik salah satu template di bawah ini. Tata letak website kamu akan langsung berubah!</p>
                                <div class="row g-3">

                                    <!-- Template: Portal Dinas / Berita -->
                                    <div class="col-md-4">
                                        <div class="tpl-card" data-theme="dinas">
                                            <span class="tpl-badge-active"><i class="fas fa-check-circle"></i> Aktif</span>
                                            <div class="tpl-icon tpl-dinas"><i class="fas fa-landmark"></i></div>
                                            <span class="badge bg-secondary mb-1">Pemerintah & Berita</span>
                                            <h6 class="fw-bold mb-1">Portal Dinas</h6>
                                            <p class="text-muted" style="font-size:12px; flex:1;">Portal berita resmi, instansi, atau kantor desa.</p>
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-1 select-tpl-btn">Pilih</button>
                                        </div>
                                    </div>

                                    <!-- Template: Toko Online -->
                                    <div class="col-md-4">
                                        <div class="tpl-card" data-theme="katalog">
                                            <span class="tpl-badge-active"><i class="fas fa-check-circle"></i> Aktif</span>
                                            <div class="tpl-icon tpl-katalog"><i class="fas fa-shopping-bag"></i></div>
                                            <span class="badge bg-success mb-1">Toko & Bisnis</span>
                                            <h6 class="fw-bold mb-1">Katalog Toko</h6>
                                            <p class="text-muted" style="font-size:12px; flex:1;">Etalase produk dengan tombol pesan via WhatsApp.</p>
                                            <button type="button" class="btn btn-sm btn-outline-success mt-1 select-tpl-btn">Pilih</button>
                                        </div>
                                    </div>

                                    <!-- Template: Company Profile -->
                                    <div class="col-md-4">
                                        <div class="tpl-card" data-theme="company">
                                            <span class="tpl-badge-active"><i class="fas fa-check-circle"></i> Aktif</span>
                                            <div class="tpl-icon tpl-company"><i class="fas fa-briefcase"></i></div>
                                            <span class="badge bg-primary mb-1">Profil & Lembaga</span>
                                            <h6 class="fw-bold mb-1">Company Profile</h6>
                                            <p class="text-muted" style="font-size:12px; flex:1;">Landing page profesional untuk perusahaan atau lembaga.</p>
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-1 select-tpl-btn">Pilih</button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="site_theme" value="dinas">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="d-flex gap-2 pb-5">
                    <button type="button" id="btn-save-site" class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> Simpan & Terapkan
                    </button>
                    <a id="btn-open-site" href="#" target="_blank" class="btn btn-outline-secondary">
                        <i class="fas fa-eye"></i> Buka Website Saya
                    </a>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_MY_SITE = '<?= base_url("api/user/my-site") ?>';
        let currentSiteUrl = '';

        $(document).ready(function() {
            loadMySite();

            // Klik kartu template
            $(document).on('click', '.tpl-card, .select-tpl-btn', function(e) {
                const card = $(this).closest('.tpl-card');
                $('.tpl-card').removeClass('active');
                card.addClass('active');
                $('#site_theme').val(card.data('theme'));
            });

            // Tombol Simpan
            $('#btn-save-site').on('click', function() {
                const data = {
                    site_title: $('#site_title').val().trim(),
                    site_bio:   $('#site_bio').val().trim(),
                    site_theme: $('#site_theme').val(),
                    phone_wa:   $('#phone_wa').val().trim(),
                };

                if (!data.site_title) {
                    showAlert('Nama website wajib diisi!', 'warning');
                    $('#site_title').focus();
                    return;
                }

                $('#btn-save-site').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: API_MY_SITE,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(res) {
                        showAlert('✅ ' + res.message, 'success');
                        loadMySite(); // reload URL baru
                    },
                    error: function(xhr) {
                        showAlert(xhr.responseJSON?.message || 'Gagal menyimpan pengaturan.', 'danger');
                    },
                    complete: function() {
                        $('#btn-save-site').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan & Terapkan');
                    }
                });
            });
        });

        function loadMySite() {
            $.ajax({
                url: API_MY_SITE,
                method: 'GET',
                success: function(res) {
                    if (!res.data) return;
                    const d = res.data;

                    $('#site_title').val(d.site_title || '');
                    $('#site_bio').val(d.site_bio || '');
                    $('#phone_wa').val(d.phone_wa || '');

                    // Set tema aktif
                    const theme = d.site_theme || 'dinas';
                    $('#site_theme').val(theme);
                    $('.tpl-card').removeClass('active');
                    $(`.tpl-card[data-theme="${theme}"]`).addClass('active');

                    // Tampilkan URL website pribadi
                    currentSiteUrl = d.site_url;
                    $('#site-url-display').html(
                        `<a href="${currentSiteUrl}" target="_blank" style="text-decoration:none; color:inherit;">${currentSiteUrl}</a>`
                    );
                    $('#btn-preview-site').attr('href', currentSiteUrl);
                    $('#btn-open-site').attr('href', currentSiteUrl);
                },
                error: function() {
                    showAlert('Gagal memuat data website. Coba refresh halaman.', 'danger');
                }
            });
        }

        function showAlert(message, type) {
            const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            $('#alert-message').html(alertHtml);
            setTimeout(() => $('#alert-message').html(''), 6000);
        }
    </script>
</body>
</html>

