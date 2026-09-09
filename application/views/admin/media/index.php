<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin - Media Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { min-height: 100vh; }
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .media-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .media-item:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .media-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .media-item .info {
            padding: 10px;
            font-size: 14px;
        }
        .media-item .info .file-name {
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .dropzone {
            border: 3px dashed #007bff;
            border-radius: 10px;
            padding: 60px 20px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s;
            cursor: pointer;
        }
        .dropzone.dragover {
            background: #e3f2fd;
            border-color: #0056b3;
        }
        .dropzone i {
            font-size: 48px;
            color: #007bff;
        }
        .upload-progress {
            margin-top: 10px;
        }
        .file-type-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
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
                        <li class="nav-item"><a class="nav-link text-white active" href="<?= base_url('admin/media') ?>"><i class="fas fa-images"></i> Media</a></li>
                    </ul>
                </div>
            </nav>

            <!-- MAIN CONTENT -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">🖼️ Media Library</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>

                <!-- ALERT -->
                <div id="alert-message"></div>

                <!-- MEDIA GRID -->
                <div class="media-grid" id="media-grid">
                    <div class="text-center py-5" id="loading-media">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Loading media...</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL UPLOAD -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-upload"></i> Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="dropzone" id="dropzone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h5 class="mt-3">Drag & Drop files here</h5>
                        <p class="text-muted">or click to select files</p>
                        <input type="file" id="file-input" multiple style="display:none;">
                        <div class="upload-progress" id="upload-progress" style="display:none;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar" style="width:0%"></div>
                            </div>
                            <p class="text-muted mt-2" id="upload-status">Uploading...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL MEDIA -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-image"></i> Media Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img id="detail-image" src="" class="img-fluid rounded" alt="">
                        </div>
                        <div class="col-md-6">
                            <form id="media-form">
                                <input type="hidden" id="detail-id">
                                <div class="mb-3">
                                    <label class="form-label">File Name</label>
                                    <input type="text" class="form-control" id="detail-filename" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">File Type</label>
                                    <input type="text" class="form-control" id="detail-type" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">File Size</label>
                                    <input type="text" class="form-control" id="detail-size" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alt Text</label>
                                    <input type="text" class="form-control" id="detail-alt" placeholder="Alt text for image">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Caption</label>
                                    <textarea class="form-control" id="detail-caption" rows="3" placeholder="Image caption"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Metadata</button>
                                <button type="button" class="btn btn-danger" id="delete-media"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = '<?= base_url("api/media") ?>';
        let detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        let uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));

        $(document).ready(function() {
            loadMedia();

            // Drag & Drop Upload
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('file-input');

            dropzone.addEventListener('click', function() {
                fileInput.click();
            });

            dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            dropzone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    uploadFiles(files);
                }
            });

            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    uploadFiles(this.files);
                    this.value = '';
                }
            });

            // Click media item to show detail
            $(document).on('click', '.media-item', function() {
                const id = $(this).data('id');
                loadMediaDetail(id);
            });

            // Save metadata
            $('#media-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#detail-id').val();
                const data = {
                    alt_text: $('#detail-alt').val(),
                    caption: $('#detail-caption').val()
                };

                $.ajax({
                    url: `${API_URL}/${id}`,
                    method: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function() {
                        showAlert('Metadata updated!', 'success');
                        loadMedia();
                        detailModal.hide();
                    },
                    error: function() {
                        showAlert('Error updating metadata', 'danger');
                    }
                });
            });

            // Delete media
            $('#delete-media').on('click', function() {
                const id = $('#detail-id').val();
                if (confirm('Yakin hapus file ini?')) {
                    $.ajax({
                        url: `${API_URL}/${id}`,
                        method: 'DELETE',
                        success: function() {
                            showAlert('File deleted!', 'success');
                            loadMedia();
                            detailModal.hide();
                        },
                        error: function() {
                            showAlert('Error deleting file', 'danger');
                        }
                    });
                }
            });

            // Reset upload modal on close
            $('#uploadModal').on('hidden.bs.modal', function() {
                $('#upload-progress').hide();
                $('#progress-bar').css('width', '0%');
            });
        });

        function loadMedia() {
            $('#media-grid').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Loading media...</p></div>');
            
            $.ajax({
                url: API_URL,
                method: 'GET',
                success: function(res) {
                    let html = '';
                    if (res.data && res.data.length > 0) {
                        res.data.forEach(function(m) {
                            const isImage = m.file_type.startsWith('image/');
                            const icon = isImage ? '' : '<i class="fas fa-file fa-3x"></i>';
                            html += `
                                <div class="media-item" data-id="${m.id}">
                                    ${isImage ? `<img src="${m.file_path}" alt="${m.alt_text || m.file_name}">` : `<div class="text-center p-4">${icon}</div>`}
                                    <div class="info">
                                        <div class="file-name" title="${m.file_name}">${m.file_name}</div>
                                        <small class="text-muted">${(m.file_size / 1024).toFixed(1)} KB</small>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = `<div class="col-12 text-center py-5">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="text-muted mt-3">No media uploaded yet</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">Upload Now</button>
                        </div>`;
                    }
                    $('#media-grid').html(html);
                },
                error: function() {
                    $('#media-grid').html('<div class="text-center py-5 text-danger">Error loading media</div>');
                }
            });
        }

        function loadMediaDetail(id) {
            $.ajax({
                url: `${API_URL}/${id}`,
                method: 'GET',
                success: function(res) {
                    const m = res.data;
                    $('#detail-id').val(m.id);
                    $('#detail-image').attr('src', m.file_path);
                    $('#detail-filename').val(m.file_name);
                    $('#detail-type').val(m.file_type);
                    $('#detail-size').val((m.file_size / 1024).toFixed(1) + ' KB');
                    $('#detail-alt').val(m.alt_text || '');
                    $('#detail-caption').val(m.caption || '');
                    detailModal.show();
                },
                error: function() {
                    showAlert('Error loading media detail', 'danger');
                }
            });
        }

        function uploadFiles(files) {
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            $('#upload-progress').show();
            $('#progress-bar').css('width', '0%');
            $('#upload-status').text('Uploading...');

            $.ajax({
                url: API_URL,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    const xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percent = (e.loaded / e.total) * 100;
                            $('#progress-bar').css('width', percent + '%');
                            $('#upload-status').text(`Uploading ${Math.round(percent)}%`);
                        }
                    });
                    return xhr;
                },
                success: function(res) {
                    $('#upload-status').text('Upload complete!');
                    setTimeout(function() {
                        uploadModal.hide();
                        loadMedia();
                        showAlert('Files uploaded successfully!', 'success');
                    }, 1000);
                },
                error: function() {
                    $('#upload-status').text('Upload failed!');
                    showAlert('Error uploading files', 'danger');
                }
            });
        }

        function showAlert(message, type) {
            const alert = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            $('#alert-message').html(alert);
            setTimeout(() => $('#alert-message').html(''), 5000);
        }
    </script>
</body>
</html>