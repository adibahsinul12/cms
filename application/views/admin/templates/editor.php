<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Code Editor - Advanced</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CodeMirror CSS & Themes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; height: 100vh; overflow: hidden; background: #1E1E2E; color: #CDD6F4; }
        
        .editor-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* Top Navbar */
        .editor-navbar {
            height: 52px;
            background: #181825;
            border-bottom: 1px solid #313244;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .editor-main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* File Tree Panel */
        .filetree-panel {
            width: 260px;
            background: #11111B;
            border-right: 1px solid #313244;
            display: flex;
            flex-direction: column;
        }

        .tree-header {
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #A6ADC8;
            border-bottom: 1px solid #313244;
        }

        .tree-content {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0;
            font-family: monospace;
            font-size: 13px;
        }

        .tree-item {
            padding: 6px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: #BAC2DE;
            transition: background 0.15s;
        }
        .tree-item:hover { background: #181825; color: #CDD6F4; }
        .tree-item.active { background: #313244; color: #89B4FA; font-weight: 600; }
        .tree-item.dir { font-weight: 600; color: #F9E2AF; }

        /* Code Workspace */
        .code-workspace {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #1E1E2E;
        }

        .editor-tabs {
            height: 40px;
            background: #181825;
            border-bottom: 1px solid #313244;
            display: flex;
            align-items: center;
            padding: 0 10px;
        }

        .editor-tab {
            padding: 6px 16px;
            background: #1E1E2E;
            color: #CDD6F4;
            border: 1px solid #313244;
            border-bottom: none;
            border-radius: 6px 6px 0 0;
            font-size: 12.5px;
            font-family: monospace;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .codemirror-container {
            flex: 1;
            overflow: hidden;
            position: relative;
        }

        .CodeMirror {
            height: 100% !important;
            font-family: 'Fira Code', 'Cascadia Code', Consolas, monospace;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Bottom Revision Drawer */
        .revision-panel {
            height: 160px;
            background: #11111B;
            border-top: 1px solid #313244;
            display: flex;
            flex-direction: column;
        }

        .revision-header {
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 700;
            color: #A6ADC8;
            border-bottom: 1px solid #313244;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .revision-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px 16px;
        }

        .revision-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="editor-wrapper">
        <!-- TOP NAVBAR -->
        <header class="editor-navbar">
            <div class="d-flex align-items-center gap-3">
                <a href="<?= base_url('admin/templates') ?>" class="btn btn-sm btn-outline-light border-secondary">
                    <i class="fas fa-arrow-left"></i> Galeri
                </a>
                <span class="fw-bold fs-6"><i class="fas fa-code text-primary"></i> <span id="template-title">Editor Kode</span></span>
                <span class="badge bg-secondary" id="active-file-badge">Pilih file...</span>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('template/preview') ?>/<?= $template_id ?>" target="_blank" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-eye"></i> Demo
                </a>
                <button type="button" class="btn btn-sm btn-success fw-bold px-3" id="btn-save-code">
                    <i class="fas fa-save"></i> Simpan File
                </button>
            </div>
        </header>

        <!-- MAIN WORKSPACE -->
        <div class="editor-main">
            <!-- FILE TREE -->
            <aside class="filetree-panel">
                <div class="tree-header">
                    <i class="fas fa-folder-open me-1"></i> File Manager
                </div>
                <div class="tree-content" id="tree-content">
                    <div class="p-3 text-muted small">Memuat berkas...</div>
                </div>
            </aside>

            <!-- CODE WORKSPACE -->
            <section class="code-workspace">
                <div class="editor-tabs">
                    <div class="editor-tab">
                        <i class="fas fa-file-code text-info"></i>
                        <span id="tab-filename">Tidak ada file yang dibuka</span>
                    </div>
                </div>
                <div class="codemirror-container">
                    <textarea id="code-editor"></textarea>
                </div>

                <!-- REVISION HISTORY PANEL -->
                <div class="revision-panel">
                    <div class="revision-header">
                        <span><i class="fas fa-history text-warning me-1"></i> Riwayat Revisi & Backup (Version Control)</span>
                        <small class="text-muted">Setiap simpan otomatis membuat revisi baru</small>
                    </div>
                    <div class="revision-list" id="revisions-list">
                        <div class="text-muted small py-2">Pilih file untuk melihat riwayat revisi.</div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CodeMirror JS & Modes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/php/php.min.js"></script>

    <script>
        const TEMPLATE_ID = <?= json_encode($template_id ?: 1) ?>;
        const API_FILES   = '<?= base_url("api/templates/files") ?>/' + TEMPLATE_ID;
        const API_CONTENT = '<?= base_url("api/templates/file_content") ?>';
        const API_SAVE    = '<?= base_url("api/templates/save_file") ?>';
        const API_REVS    = '<?= base_url("api/templates/revisions") ?>/' + TEMPLATE_ID;
        const API_RESTORE = '<?= base_url("api/templates/restore_revision") ?>';

        let editor = null;
        let currentFilePath = null;

        $(document).ready(function() {
            // Inisialisasi CodeMirror
            editor = CodeMirror.fromTextArea(document.getElementById('code-editor'), {
                lineNumbers: true,
                theme: 'dracula',
                mode: 'application/x-httpd-php',
                indentUnit: 4,
                tabSize: 4,
                lineWrapping: true
            });

            loadFileTree();

            // Klik item berkas
            $(document).on('click', '.tree-item.file', function() {
                $('.tree-item').removeClass('active');
                $(this).addClass('active');
                const path = $(this).data('path');
                openFile(path);
            });

            // Tombol Simpan
            $('#btn-save-code').on('click', function() {
                if (!currentFilePath) {
                    alert('Silakan pilih berkas terlebih dahulu dari panel kiri.');
                    return;
                }

                const content = editor.getValue();
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: API_SAVE,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        template_id: TEMPLATE_ID,
                        file_path: currentFilePath,
                        content: content
                    }),
                    success: function(res) {
                        alert('✅ ' + (res.message || 'Berkas berhasil disimpan!'));
                        loadRevisions(currentFilePath);
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Gagal menyimpan berkas.');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan File');
                    }
                });
            });
        });

        function loadFileTree() {
            $.ajax({
                url: API_FILES,
                method: 'GET',
                success: function(res) {
                    const tree = res.data || [];
                    let html = '';
                    html += renderTreeNodes(tree);
                    $('#tree-content').html(html);

                    // Auto open home.php jika ada
                    const defaultFile = $('.tree-item[data-path="home.php"]');
                    if (defaultFile.length) {
                        defaultFile.click();
                    } else {
                        const firstFile = $('.tree-item.file').first();
                        if (firstFile.length) firstFile.click();
                    }
                }
            });
        }

        function renderTreeNodes(nodes) {
            let html = '';
            nodes.forEach(function(node) {
                if (node.type === 'directory') {
                    html += `
                        <div class="tree-item dir"><i class="fas fa-folder text-warning"></i> ${node.name}</div>
                        <div style="padding-left: 14px;">
                            ${renderTreeNodes(node.children || [])}
                        </div>
                    `;
                } else {
                    let icon = 'fa-file-code text-info';
                    if (node.extension === 'css') icon = 'fa-css3-alt text-primary';
                    else if (node.extension === 'js') icon = 'fa-js text-warning';
                    else if (node.extension === 'json') icon = 'fa-file-alt text-success';

                    html += `
                        <div class="tree-item file" data-path="${node.path}">
                            <i class="fas ${icon}"></i> ${node.name}
                        </div>
                    `;
                }
            });
            return html;
        }

        function openFile(path) {
            currentFilePath = path;
            $('#tab-filename').text(path);
            $('#active-file-badge').text(path);

            $.ajax({
                url: `${API_CONTENT}?template_id=${TEMPLATE_ID}&file_path=${encodeURIComponent(path)}`,
                method: 'GET',
                success: function(res) {
                    if (res.data) {
                        editor.setValue(res.data.content);

                        // Atur mode CodeMirror sesuai ekstensi
                        const ext = res.data.extension;
                        if (ext === 'css') editor.setOption('mode', 'css');
                        else if (ext === 'js') editor.setOption('mode', 'javascript');
                        else if (ext === 'json') editor.setOption('mode', 'application/json');
                        else editor.setOption('mode', 'application/x-httpd-php');

                        loadRevisions(path);
                    }
                },
                error: function() {
                    alert('Gagal membuka isi berkas.');
                }
            });
        }

        function loadRevisions(path) {
            $.ajax({
                url: `${API_REVS}?file_path=${encodeURIComponent(path)}`,
                method: 'GET',
                success: function(res) {
                    const revs = res.data || [];
                    if (!revs.length) {
                        $('#revisions-list').html('<div class="text-muted small py-2">Belum ada riwayat revisi untuk berkas ini.</div>');
                        return;
                    }

                    let html = '';
                    revs.forEach(function(r) {
                        const timeStr = new Date(r.created_at).toLocaleString('id-ID');
                        html += `
                            <div class="revision-row">
                                <div>
                                    <strong class="text-light">Revisi #${r.id}</strong> — 
                                    <span class="text-muted">${timeStr} oleh ${r.revised_by_name || 'Admin'}</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-warning py-0 px-2" onclick="restoreRevision(${r.id})">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                            </div>
                        `;
                    });
                    $('#revisions-list').html(html);
                }
            });
        }

        function restoreRevision(revId) {
            if (!confirm('Kembalikan isi file ini ke versi revisi tersebut? Konten yang belum disimpan akan tergantikan.')) return;

            $.ajax({
                url: `${API_RESTORE}/${revId}`,
                method: 'POST',
                success: function(res) {
                    alert('✅ ' + (res.message || 'File berhasil direstore!'));
                    openFile(currentFilePath);
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Gagal merestore revisi.');
                }
            });
        }
    </script>
</body>
</html>

