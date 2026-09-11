<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - SEO Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { min-height: 100vh; }
        .seo-preview {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
        }
        .seo-preview .url { color: #006621; font-size: 13px; }
        .seo-preview .title { color: #1a0dab; font-size: 18px; font-weight: 500; }
        .seo-preview .desc { color: #545454; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
<?php
    $active_menu = 'seo'; // sesuaikan: 'posts', 'categories', 'tags', 'media', 'users', 'settings', 'seo', 'comments', 'logs'
    $this->load->view('admin/partials/sidebar', ['active_menu' => $active_menu]);
?>

            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">🔍 SEO Settings</h1>
                </div>

                <div id="alert-message"></div>

                <form id="seo-form">
                    <div class="row">
                        <div class="col-md-7">
                            <!-- META TAGS -->
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-tags"></i> Meta Tags</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Meta Title</label>
                                        <input type="text" class="form-control" id="meta_title" maxlength="60" placeholder="Judul untuk mesin pencari">
                                        <small class="text-muted"><span id="title-count">0</span>/60 karakter</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Meta Description</label>
                                        <textarea class="form-control" id="meta_description" rows="3" maxlength="160" placeholder="Deskripsi untuk mesin pencari"></textarea>
                                        <small class="text-muted"><span id="desc-count">0</span>/160 karakter</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Meta Keywords</label>
                                        <input type="text" class="form-control" id="meta_keywords" placeholder="keyword1, keyword2, keyword3">
                                    </div>
                                </div>
                            </div>

                            <!-- OPEN GRAPH -->
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-share-alt"></i> Open Graph (Social Media)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">OG Title</label>
                                        <input type="text" class="form-control" id="og_title" placeholder="Judul saat dibagikan">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">OG Description</label>
                                        <textarea class="form-control" id="og_description" rows="2" placeholder="Deskripsi saat dibagikan"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">OG Image URL</label>
                                        <input type="text" class="form-control" id="og_image" placeholder="https://example.com/image.jpg">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <!-- PREVIEW -->
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-eye"></i> Preview Google</h5>
                                </div>
                                <div class="card-body">
                                    <div class="seo-preview">
                                        <div class="url" id="preview-url">https://example.com</div>
                                        <div class="title" id="preview-title">Meta Title akan muncul di sini</div>
                                        <div class="desc" id="preview-desc">Meta description akan muncul di sini...</div>
                                    </div>
                                </div>
                            </div>

                            <!-- TOOLS -->
                            <div class="card">
                                <div class="card-header bg-warning">
                                    <h5 class="mb-0"><i class="fas fa-tools"></i> SEO Tools</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="enable_sitemap" checked>
                                        <label class="form-check-label" for="enable_sitemap">Generate Sitemap</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="enable_robots" checked>
                                        <label class="form-check-label" for="enable_robots">Robots.txt</label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-download"></i> Download Sitemap
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan SEO Settings</button>
                </form>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Counter
            $('#meta_title').on('input', function() {
                $('#title-count').text($(this).val().length);
                $('#preview-title').text($(this).val() || 'Meta Title akan muncul di sini');
            });
            $('#meta_description').on('input', function() {
                $('#desc-count').text($(this).val().length);
                $('#preview-desc').text($(this).val() || 'Meta description akan muncul di sini...');
            });

            // Submit
            $('#seo-form').on('submit', function(e) {
                e.preventDefault();
                const data = {
                    meta_title: $('#meta_title').val(),
                    meta_description: $('#meta_description').val(),
                    meta_keywords: $('#meta_keywords').val(),
                    og_title: $('#og_title').val(),
                    og_description: $('#og_description').val(),
                    og_image: $('#og_image').val(),
                    enable_sitemap: $('#enable_sitemap').is(':checked') ? 1 : 0,
                    enable_robots: $('#enable_robots').is(':checked') ? 1 : 0
                };

                $.ajax({
                    url: '<?= base_url("api/seo") ?>',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function() {
                        showAlert('SEO settings berhasil disimpan!', 'success');
                    },
                    error: function() {
                        showAlert('Gagal menyimpan (API belum tersedia)', 'warning');
                    }
                });
            });
        });

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
