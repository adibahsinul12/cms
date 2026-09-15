<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Studio - Galeri Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { min-height: 100vh; }
        .tpl-card {
            border: 2px solid #E2E8F0;
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
        }
        .tpl-card:hover {
            border-color: #0D6EFD;
            transform: translateY(-4px);
            box-shadow: 0 16px 30px rgba(13, 110, 253, 0.1);
        }
        .tpl-card.active-theme {
            border-color: #0D6EFD;
            box-shadow: 0 0 0 2px #0D6EFD;
        }
        .tpl-thumb {
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            position: relative;
        }
        .thumb-dinas     { background: linear-gradient(135deg, #0F2A47, #1B4570); color: #E7A33E; }
        .thumb-katalog   { background: linear-gradient(135deg, #064E3B, #10B981); color: #fff; }
        .thumb-company   { background: linear-gradient(135deg, #1E3A8A, #3B82F6); color: #fff; }
        .thumb-blog      { background: linear-gradient(135deg, #7C2D12, #EA580C); color: #fff; }
        .thumb-portfolio { background: linear-gradient(135deg, #09090B, #581C87); color: #C4B5FD; }
        .thumb-landing   { background: linear-gradient(135deg, #155E75, #06B6D4); color: #fff; }
        .thumb-custom    { background: linear-gradient(135deg, #334155, #64748B); color: #fff; }

        .active-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #0D6EFD;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 50px;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
        }
        .category-badge {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(15, 23, 42, 0.75);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
            backdrop-filter: blur(4px);
        }
        .tpl-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .tpl-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .tpl-meta {
            font-size: 12px;
            color: #64748B;
            margin-bottom: 10px;
        }
        .tpl-desc {
            font-size: 13px;
            color: #475569;
            line-height: 1.55;
            margin-bottom: 18px;
            flex-grow: 1;
        }
        .tpl-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            border-top: 1px solid #F1F5F9;
            padding-top: 14px;
        }
        .filter-btn {
            border-radius: 50px;
            padding: 6px 18px;
            font-size: 13.5px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
<?php
    $active_menu = 'templates';
    $this->load->view('admin/partials/sidebar', ['active_menu' => $active_menu]);
?>

            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <div>
                        <h1 class="h2 mb-1">🎨 Template Studio</h1>
                        <p class="text-muted small mb-0">Pilih, sesuaikan tampilan (Live Customizer), atau edit kode template CMS Anda.</p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-file-import"></i> Import Template (.ZIP)
                        </button>
                        <a href="<?= base_url('admin/templates/customize') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-sliders-h"></i> Buka Customizer
                        </a>
                    </div>
                </div>

                <div id="alert-container"></div>

                <!-- Filter Kategori & Pencarian -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div class="d-flex gap-2 flex-wrap" id="category-filters">
                        <button class="btn btn-sm btn-primary filter-btn active" data-category="all">Semua</button>
                        <button class="btn btn-sm btn-outline-secondary filter-btn" data-category="Pemerintahan">Pemerintahan</button>
                        <button class="btn btn-sm btn-outline-secondary filter-btn" data-category="Toko">Toko & UMKM</button>
                        <button class="btn btn-sm btn-outline-secondary filter-btn" data-category="Bisnis">Bisnis</button>
                        <button class="btn btn-sm btn-outline-secondary filter-btn" data-category="Blog">Blog</button>
                        <button class="btn btn-sm btn-outline-secondary filter-btn" data-category="Portofolio">Portofolio</button>
                    </div>
                    <div style="max-width: 260px; width: 100%;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control" id="search-input" placeholder="Cari template...">
                        </div>
                    </div>
                </div>

                <!-- Grid Kartu Galeri Template -->
                <div class="row g-4" id="template-grid">
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Memuat galeri template...</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL IMPORT TEMPLATE -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fas fa-file-archive text-primary"></i> Import Template (.ZIP)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="import-form" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p class="small text-muted mb-3">Upload berkas template berekstensi <code>.zip</code> yang memiliki berkas konfigurasi <code>theme.json</code> di dalamnya.</p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Berkas ZIP</label>
                            <input type="file" class="form-control" name="theme_zip" id="theme_zip" accept=".zip" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="btn-submit-import">
                            <i class="fas fa-upload"></i> Upload & Pasang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DUPLIKASI TEMPLATE -->
    <div class="modal fade" id="duplicateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fas fa-clone text-success"></i> Duplikasi Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="duplicate-form">
                    <input type="hidden" id="dup-source-id">
                    <div class="modal-body">
                        <p class="small text-muted mb-3">Buat salinan tema ini agar Anda bisa mengedit kode dan tata letaknya secara terpisah.</p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Template Baru</label>
                            <input type="text" class="form-control" id="dup-name" placeholder="Contoh: Katalog UMKM Kustom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Slug Folder Baru (Huruf kecil & tanda strip)</label>
                            <input type="text" class="form-control" id="dup-slug" placeholder="katalog-kustom" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-sm" id="btn-submit-duplicate">
                            <i class="fas fa-clone"></i> Gandakan Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_TEMPLATES = '<?= base_url("api/templates") ?>';
        let allTemplates = [];
        let currentCategory = 'all';

        $(document).ready(function() {
            loadTemplates();

            // Filter Kategori
            $('.filter-btn').on('click', function() {
                $('.filter-btn').removeClass('btn-primary active').addClass('btn-outline-secondary');
                $(this).removeClass('btn-outline-secondary').addClass('btn-primary active');
                currentCategory = $(this).data('category');
                filterAndRender();
            });

            // Search
            $('#search-input').on('input', function() {
                filterAndRender();
            });

            // Auto slug generator pada form duplikasi
            $('#dup-name').on('input', function() {
                const slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
                $('#dup-slug').val(slug);
            });

            // Submit Duplikasi
            $('#duplicate-form').on('submit', function(e) {
                e.preventDefault();
                const sourceId = $('#dup-source-id').val();
                const data = {
                    name: $('#dup-name').val().trim(),
                    slug: $('#dup-slug').val().trim()
                };

                $('#btn-submit-duplicate').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menduplikasi...');

                $.ajax({
                    url: `${API_TEMPLATES}/duplicate/${sourceId}`,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(res) {
                        $('#duplicateModal').modal('hide');
                        showAlert('✅ Template berhasil digandakan!', 'success');
                        loadTemplates();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Gagal menduplikasi template');
                    },
                    complete: function() {
                        $('#btn-submit-duplicate').prop('disabled', false).html('<i class="fas fa-clone"></i> Gandakan Sekarang');
                    }
                });
            });

            // Submit Import ZIP
            $('#import-form').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                $('#btn-submit-import').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengupload...');

                $.ajax({
                    url: `${API_TEMPLATES}/import`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#importModal').modal('hide');
                        $('#import-form')[0].reset();
                        showAlert('✅ ' + (res.message || 'Template berhasil diimport!'), 'success');
                        loadTemplates();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Gagal mengimport template ZIP.');
                    },
                    complete: function() {
                        $('#btn-submit-import').prop('disabled', false).html('<i class="fas fa-upload"></i> Upload & Pasang');
                    }
                });
            });
        });

        function loadTemplates() {
            $.ajax({
                url: API_TEMPLATES,
                method: 'GET',
                success: function(res) {
                    allTemplates = res.data || [];
                    filterAndRender();
                },
                error: function() {
                    $('#template-grid').html('<div class="col-12 text-center py-5 text-danger">Gagal memuat daftar template.</div>');
                }
            });
        }

        function filterAndRender() {
            const query = $('#search-input').val().toLowerCase();
            let filtered = allTemplates;

            if (currentCategory !== 'all') {
                filtered = filtered.filter(t => (t.category || '').toLowerCase() === currentCategory.toLowerCase());
            }

            if (query) {
                filtered = filtered.filter(t => t.name.toLowerCase().includes(query) || (t.description || '').toLowerCase().includes(query));
            }

            renderGrid(filtered);
        }

        function renderGrid(items) {
            if (!items.length) {
                $('#template-grid').html('<div class="col-12 text-center py-5 text-muted">Tidak ada template yang cocok dengan filter atau pencarian Anda.</div>');
                return;
            }

            let html = '';
            items.forEach(function(t) {
                const isActive = parseInt(t.is_active, 10) === 1;
                const isDefault = parseInt(t.is_default, 10) === 1;
                const thumbClass = 'thumb-' + (t.slug || 'custom');

                let icon = 'fa-desktop';
                if (t.slug === 'dinas') icon = 'fa-landmark';
                else if (t.slug === 'katalog') icon = 'fa-shopping-bag';
                else if (t.slug === 'company') icon = 'fa-briefcase';
                else if (t.slug === 'blog') icon = 'fa-newspaper';
                else if (t.slug === 'portfolio') icon = 'fa-layer-group';
                else if (t.slug === 'landing') icon = 'fa-rocket';

                html += `
                    <div class="col-md-6 col-lg-4">
                        <div class="tpl-card ${isActive ? 'active-theme' : ''}">
                            <div class="tpl-thumb ${thumbClass}">
                                <i class="fas ${icon}"></i>
                                ${isActive ? '<span class="active-badge"><i class="fas fa-check-circle"></i> Sedang Aktif</span>' : ''}
                                <span class="category-badge">${t.category || 'Umum'}</span>
                            </div>
                            <div class="tpl-body">
                                <h3 class="tpl-title">${t.name}</h3>
                                <div class="tpl-meta">
                                    <span>v${t.version || '1.0.0'}</span> • 
                                    <span>Oleh ${t.author || 'Tim'}</span>
                                </div>
                                <p class="tpl-desc">${t.description || 'Template berkualitas tinggi untuk kebutuhan website Anda.'}</p>
                                
                                <div class="tpl-actions">
                                    <div class="d-flex gap-2">
                                        ${isActive ? 
                                            `<button class="btn btn-sm btn-success w-100 fw-bold" disabled><i class="fas fa-check"></i> Aktif</button>` : 
                                            `<button class="btn btn-sm btn-primary w-100 fw-bold" onclick="activateTemplate(${t.id})"><i class="fas fa-check"></i> Gunakan</button>`
                                        }
                                        <a href="<?= base_url('admin/templates/customize') ?>/${t.id}" class="btn btn-sm btn-outline-primary w-100" title="Buka Live Customizer">
                                            <i class="fas fa-sliders-h"></i> Kustomisasi
                                        </a>
                                    </div>
                                    <div class="d-flex gap-1 justify-content-between mt-1">
                                        <a href="<?= base_url('admin/templates/editor') ?>/${t.id}" class="btn btn-sm btn-light border flex-fill text-dark" title="Edit Kode (HTML/CSS/JS)">
                                            <i class="fas fa-code text-secondary"></i> Kode
                                        </a>
                                        <a href="<?= base_url('template/preview') ?>/${t.id}" target="_blank" class="btn btn-sm btn-light border flex-fill text-dark" title="Preview Demo">
                                            <i class="fas fa-eye text-secondary"></i> Demo
                                        </a>
                                        <a href="${API_TEMPLATES}/export/${t.id}" class="btn btn-sm btn-light border flex-fill text-dark" title="Download Template ZIP">
                                            <i class="fas fa-download text-secondary"></i> ZIP
                                        </a>
                                        <button class="btn btn-sm btn-light border flex-fill text-dark" onclick="openDuplicateModal(${t.id}, '${escapeHtml(t.name)}', '${t.slug}')" title="Duplikasi Template">
                                            <i class="fas fa-clone text-secondary"></i>
                                        </button>
                                        ${(!isActive && !isDefault) ? `
                                            <button class="btn btn-sm btn-light border text-danger" onclick="deleteTemplate(${t.id}, '${escapeHtml(t.name)}')" title="Hapus Template">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#template-grid').html(html);
        }

        function activateTemplate(id) {
            if (!confirm('Aktifkan template ini sebagai tampilan utama website?')) return;

            $.ajax({
                url: `${API_TEMPLATES}/activate/${id}`,
                method: 'POST',
                success: function(res) {
                    showAlert('✅ ' + (res.message || 'Template berhasil diaktifkan!'), 'success');
                    loadTemplates();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Gagal mengaktifkan template.');
                }
            });
        }

        function openDuplicateModal(id, name, slug) {
            $('#dup-source-id').val(id);
            $('#dup-name').val(name + ' (Salinan)');
            $('#dup-slug').val(slug + '-copy');
            $('#duplicateModal').modal('show');
        }

        function deleteTemplate(id, name) {
            if (!confirm(`Yakin ingin menghapus template "${name}"? Tindakan ini tidak dapat dibatalkan.`)) return;

            $.ajax({
                url: `${API_TEMPLATES}/delete/${id}`,
                method: 'DELETE',
                success: function(res) {
                    showAlert('✅ ' + res.message, 'success');
                    loadTemplates();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Gagal menghapus template.');
                }
            });
        }

        function showAlert(msg, type) {
            $('#alert-container').html(`
                <div class="alert alert-${type} alert-dismissible fade show">
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            setTimeout(() => $('#alert-container').html(''), 5000);
        }

        function escapeHtml(text) {
            return String(text).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }
    </script>
</body>
</html>

