<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Customizer - Live Preview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; height: 100vh; overflow: hidden; background: #0F172A; }
        
        .customizer-wrapper {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        /* Panel Kiri: Kontrol Kustomisasi */
        .controls-panel {
            width: 380px;
            min-width: 340px;
            max-width: 440px;
            background: #FFFFFF;
            border-right: 1px solid #E2E8F0;
            display: flex;
            flex-direction: column;
            z-index: 20;
            box-shadow: 4px 0 20px rgba(0,0,0,0.06);
        }

        .panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #F8FAFC;
        }

        .panel-header h5 {
            font-size: 15px;
            font-weight: 700;
            margin: 0;
            color: #0F172A;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .panel-scrollable {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .panel-footer {
            padding: 14px 20px;
            border-top: 1px solid #E2E8F0;
            background: #F8FAFC;
            display: flex;
            gap: 10px;
        }

        /* Accordion Kustomisasi */
        .accordion-item {
            border: 1px solid #E2E8F0;
            border-radius: 10px !important;
            margin-bottom: 12px;
            overflow: hidden;
        }
        .accordion-button {
            font-size: 13.5px;
            font-weight: 700;
            padding: 12px 16px;
            background: #fff;
            color: #1E293B;
        }
        .accordion-button:not(.collapsed) {
            background: #F0F7FF;
            color: #0D6EFD;
            box-shadow: none;
        }
        .accordion-body {
            padding: 16px;
            font-size: 13px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 4px;
        }

        /* Color Picker Input */
        .color-picker-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .color-picker-wrap input[type="color"] {
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            width: 42px;
            height: 36px;
            padding: 2px;
            cursor: pointer;
        }

        /* Panel Kanan: Preview Iframe & Toolbar */
        .preview-panel {
            flex: 1;
            background: #0F172A;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .preview-toolbar {
            height: 52px;
            background: #1E293B;
            border-bottom: 1px solid #334155;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            color: #94A3B8;
        }

        .device-btns {
            display: flex;
            background: #0F172A;
            border-radius: 8px;
            padding: 3px;
            gap: 2px;
        }
        .device-btn {
            background: none;
            border: none;
            color: #94A3B8;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
        }
        .device-btn.active, .device-btn:hover {
            background: #334155;
            color: #F8FAFC;
        }

        .preview-viewport {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
            background: #0F172A;
        }

        #preview-frame {
            width: 100%;
            height: 100%;
            border: none;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            transition: width 0.3s ease;
        }

        /* Viewport device modes */
        #preview-frame.desktop-view { width: 100%; height: 100%; border-radius: 8px; }
        #preview-frame.tablet-view  { width: 768px; height: 95%; border-radius: 16px; border: 8px solid #334155; }
        #preview-frame.mobile-view  { width: 375px; height: 92%; border-radius: 24px; border: 10px solid #334155; }
    </style>
</head>
<body>

    <div class="customizer-wrapper">
        <!-- PANEL KIRI: OPSI KUSTOMISASI -->
        <aside class="controls-panel">
            <div class="panel-header">
                <h5><i class="fas fa-palette text-primary"></i> <span id="tpl-name-title">Customizer</span></h5>
                <a href="<?= base_url('admin/templates') ?>" class="btn btn-sm btn-outline-secondary" title="Kembali ke Galeri">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            <div class="panel-scrollable">
                <div class="accordion" id="customizerAccordion">

                    <!-- SEKSI 1: IDENTITAS SITUS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#secIdentity">
                                <i class="fas fa-id-card me-2 text-primary"></i> Identitas & Logo
                            </button>
                        </h2>
                        <div id="secIdentity" class="accordion-collapse collapse show" data-bs-parent="#customizerAccordion">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Situs</label>
                                    <input type="text" class="form-control form-control-sm opt-field" id="site_title" data-opt="site_title">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Slogan / Tagline</label>
                                    <input type="text" class="form-control form-control-sm opt-field" id="site_tagline" data-opt="site_tagline">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Logo URL</label>
                                    <input type="text" class="form-control form-control-sm opt-field" id="logo_url" data-opt="logo_url" placeholder="https://... / path logo">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Favicon URL</label>
                                    <input type="text" class="form-control form-control-sm opt-field" id="favicon_url" data-opt="favicon_url">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEKSI 2: WARNA TEMA -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#secColors">
                                <i class="fas fa-fill-drip me-2 text-primary"></i> Palet Warna
                            </button>
                        </h2>
                        <div id="secColors" class="accordion-collapse collapse" data-bs-parent="#customizerAccordion">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label class="form-label">Warna Primer (Utama / Tombol / Brand)</label>
                                    <div class="color-picker-wrap">
                                        <input type="color" class="opt-field" id="color_primary" data-opt="color_primary" value="#0D6EFD">
                                        <input type="text" class="form-control form-control-sm text-uppercase" id="color_primary_hex" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Warna Sekunder (Aksen / Gelap)</label>
                                    <div class="color-picker-wrap">
                                        <input type="color" class="opt-field" id="color_secondary" data-opt="color_secondary" value="#6C757D">
                                        <input type="text" class="form-control form-control-sm text-uppercase" id="color_secondary_hex" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEKSI 3: TIPOGRAFI -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#secTypography">
                                <i class="fas fa-font me-2 text-primary"></i> Tipografi (Google Fonts)
                            </button>
                        </h2>
                        <div id="secTypography" class="accordion-collapse collapse" data-bs-parent="#customizerAccordion">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label class="form-label">Font Judul (Heading)</label>
                                    <select class="form-select form-select-sm opt-field" id="font_heading" data-opt="font_heading">
                                        <option value="Plus Jakarta Sans">Plus Jakarta Sans (Modern & Rapi)</option>
                                        <option value="Inter">Inter (Bersih & Standar)</option>
                                        <option value="Poppins">Poppins (Geometris & Tegas)</option>
                                        <option value="Merriweather">Merriweather (Klasik Editorial / Serif)</option>
                                        <option value="Roboto">Roboto (Universal)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Font Isi (Body)</label>
                                    <select class="form-select form-select-sm opt-field" id="font_body" data-opt="font_body">
                                        <option value="Inter">Inter</option>
                                        <option value="Plus Jakarta Sans">Plus Jakarta Sans</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="Open Sans">Open Sans</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEKSI 4: TATA LETAK & HEADER/FOOTER -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#secLayout">
                                <i class="fas fa-th-large me-2 text-primary"></i> Tata Letak & Header
                            </button>
                        </h2>
                        <div id="secLayout" class="accordion-collapse collapse" data-bs-parent="#customizerAccordion">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Kolom Grid</label>
                                    <select class="form-select form-select-sm opt-field" id="layout_columns" data-opt="layout_columns">
                                        <option value="1">1 Kolom (Single Column)</option>
                                        <option value="2">2 Kolom (Konten + Sidebar)</option>
                                        <option value="3">3 Kolom (Grid Showcase)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gaya Header Navbar</label>
                                    <select class="form-select form-select-sm opt-field" id="header_style" data-opt="header_style">
                                        <option value="sticky">Sticky (Menempel saat di-scroll)</option>
                                        <option value="fixed">Fixed (Tetap di atas)</option>
                                        <option value="classic">Klasik / Statis</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gaya Footer</label>
                                    <select class="form-select form-select-sm opt-field" id="footer_style" data-opt="footer_style">
                                        <option value="classic">Klasik Terang</option>
                                        <option value="dark">Gelap Elegan</option>
                                        <option value="minimal">Minimalis</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Teks Hak Cipta Footer</label>
                                    <input type="text" class="form-control form-control-sm opt-field" id="footer_text" data-opt="footer_text">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEKSI 5: MEDIA SOSIAL -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#secSocial">
                                <i class="fas fa-share-alt me-2 text-primary"></i> Media Sosial & Kontak
                            </button>
                        </h2>
                        <div id="secSocial" class="accordion-collapse collapse" data-bs-parent="#customizerAccordion">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fab fa-whatsapp text-success"></i> WhatsApp (Nomor/Link)</label>
                                    <input type="text" class="form-control form-control-sm opt-field" id="social_whatsapp" data-opt="social_whatsapp" placeholder="628123456789">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><i class="fab fa-instagram text-danger"></i> Instagram URL</label>
                                    <input type="text" class="form-control form-control-sm opt-field" id="social_instagram" data-opt="social_instagram" placeholder="https://instagram.com/...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><i class="fab fa-facebook text-primary"></i> Facebook URL</label>
                                    <input type="text" class="form-control form-control-sm opt-field" id="social_facebook" data-opt="social_facebook">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEKSI 6: CUSTOM CSS & JS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#secCustomCode">
                                <i class="fas fa-code me-2 text-primary"></i> Custom CSS & JS
                            </button>
                        </h2>
                        <div id="secCustomCode" class="accordion-collapse collapse" data-bs-parent="#customizerAccordion">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label class="form-label">Custom CSS Tambahan</label>
                                    <textarea class="form-control form-control-sm opt-field font-monospace" id="custom_css" data-opt="custom_css" rows="4" placeholder="/* CSS kustom Anda */"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Custom JavaScript Tambahan</label>
                                    <textarea class="form-control form-control-sm opt-field font-monospace" id="custom_js" data-opt="custom_js" rows="3" placeholder="// JS kustom Anda"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer Tombol Simpan -->
            <div class="panel-footer">
                <button type="button" class="btn btn-primary btn-sm flex-grow-1 fw-bold" id="btn-save-publish">
                    <i class="fas fa-check"></i> Simpan & Publikasikan
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-default" title="Reset opsi ke bawaan">
                    <i class="fas fa-undo"></i>
                </button>
            </div>
        </aside>

        <!-- PANEL KANAN: LIVE PREVIEW IFRAME -->
        <main class="preview-panel">
            <div class="preview-toolbar">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary"><i class="fas fa-eye"></i> Live Preview</span>
                    <small class="text-muted" id="preview-url-text">Memuat pratinjau...</small>
                </div>
                <div class="device-btns">
                    <button class="device-btn active" data-device="desktop" title="Tampilan Komputer"><i class="fas fa-desktop"></i></button>
                    <button class="device-btn" data-device="tablet" title="Tampilan Tablet"><i class="fas fa-tablet-alt"></i></button>
                    <button class="device-btn" data-device="mobile" title="Tampilan Ponsel"><i class="fas fa-mobile-alt"></i></button>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-light border-0" id="btn-reload-iframe" title="Muat ulang preview">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>

            <div class="preview-viewport">
                <iframe id="preview-frame" class="desktop-view" src="about:blank"></iframe>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const TEMPLATE_ID = <?= json_encode($template_id ?: 1) ?>;
        const API_OPTIONS = '<?= base_url("api/templates/options") ?>/' + TEMPLATE_ID;
        const API_DETAIL  = '<?= base_url("api/templates/detail") ?>/' + TEMPLATE_ID;
        const API_RESET   = '<?= base_url("api/templates/reset_options") ?>/' + TEMPLATE_ID;
        const PREVIEW_URL = '<?= base_url("template/preview") ?>/' + TEMPLATE_ID;

        let currentOptions = {};

        $(document).ready(function() {
            // Pasang iframe preview
            $('#preview-frame').attr('src', PREVIEW_URL);
            $('#preview-url-text').text(PREVIEW_URL);

            // Load opsi awal
            loadOptions();

            // Device switcher toolbar
            $('.device-btn').on('click', function() {
                $('.device-btn').removeClass('active');
                $(this).addClass('active');
                const dev = $(this).data('device');
                $('#preview-frame').removeClass('desktop-view tablet-view mobile-view').addClass(`${dev}-view`);
            });

            // Reload iframe
            $('#btn-reload-iframe').on('click', function() {
                $('#preview-frame').attr('src', PREVIEW_URL + '?t=' + Date.now());
            });

            // Sinkronisasi color hex text
            $('#color_primary').on('input', function() {
                $('#color_primary_hex').val($(this).val());
            });
            $('#color_secondary').on('input', function() {
                $('#color_secondary_hex').val($(this).val());
            });

            // Deteksi perubahan form untuk kirim live postMessage ke iframe
            $(document).on('input change', '.opt-field', function() {
                const optKey = $(this).data('opt');
                const optVal = $(this).val();
                currentOptions[optKey] = optVal;

                sendLiveUpdate();
            });

            // Simpan & Publikasikan
            $('#btn-save-publish').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: API_OPTIONS,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(currentOptions),
                    success: function(res) {
                        alert('✅ ' + (res.message || 'Perubahan berhasil disimpan dan dipublikasikan!'));
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Gagal menyimpan perubahan');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('<i class="fas fa-check"></i> Simpan & Publikasikan');
                    }
                });
            });

            // Reset ke default
            $('#btn-reset-default').on('click', function() {
                if (!confirm('Yakin ingin mereset seluruh opsi kustomisasi ke bawaan tema?')) return;

                $.ajax({
                    url: API_RESET,
                    method: 'POST',
                    success: function(res) {
                        alert('✅ ' + res.message);
                        loadOptions();
                        $('#btn-reload-iframe').click();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Gagal mereset opsi');
                    }
                });
            });
        });

        function loadOptions() {
            $.ajax({
                url: API_DETAIL,
                method: 'GET',
                success: function(res) {
                    if (!res.data) return;
                    const tpl = res.data;
                    $('#tpl-name-title').text(tpl.name);

                    currentOptions = tpl.options || {};

                    // Isi field formulir
                    for (const [key, val] of Object.entries(currentOptions)) {
                        const el = $(`[data-opt="${key}"]`);
                        if (el.length) {
                            el.val(val);
                        }
                    }

                    // Sinkronisasi color hex
                    $('#color_primary_hex').val($('#color_primary').val());
                    $('#color_secondary_hex').val($('#color_secondary').val());

                    sendLiveUpdate();
                }
            });
        }

        function sendLiveUpdate() {
            const iframe = document.getElementById('preview-frame');
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.postMessage({
                    type: 'CUSTOMIZER_UPDATE',
                    options: currentOptions
                }, '*');
            }
        }
    </script>
</body>
</html>

