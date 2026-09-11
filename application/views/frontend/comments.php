<!-- FORM KOMENTAR -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-comment"></i> Tinggalkan Komentar</h5>
    </div>
    <div class="card-body">
        <form id="comment-form">
            <input type="hidden" id="post_id" value="<?= $post_id ?? 0 ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama *</label>
                    <input type="text" class="form-control" id="author_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" id="author_email" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Komentar *</label>
                <textarea class="form-control" id="content" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Kirim Komentar
            </button>
        </form>
    </div>
</div>

<!-- DAFTAR KOMENTAR -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-comments"></i> Komentar (<span id="comment-count">0</span>)</h5>
    </div>
    <div class="card-body" id="comments-list">
        <p class="text-muted">Loading komentar...</p>
    </div>
</div>

<script>
$(document).ready(function() {
    const POST_ID = $('#post_id').val();
    loadComments();

    $('#comment-form').on('submit', function(e) {
        e.preventDefault();
        const data = {
            post_id: POST_ID,
            author_name: $('#author_name').val(),
            author_email: $('#author_email').val(),
            content: $('#content').val(),
            parent_id: null
        };

        $.ajax({
            url: '<?= base_url("api/comments/add") ?>',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function() {
                alert('Komentar berhasil dikirim! Menunggu moderasi.');
                $('#comment-form')[0].reset();
            },
            error: function() {
                alert('Gagal mengirim komentar. Coba lagi.');
            }
        });
    });

    function loadComments() {
        $.ajax({
            url: `<?= base_url("api/comments") ?>?post_id=${POST_ID}&status=approved`,
            method: 'GET',
            success: function(res) {
                if (res.data && res.data.length > 0) {
                    $('#comment-count').text(res.data.length);
                    let html = '';
                    res.data.forEach(function(c) {
                        html += `
                            <div class="comment-item mb-3 pb-3 border-bottom">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-circle fa-2x text-secondary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <strong>${c.author_name}</strong>
                                        <small class="text-muted ms-2">${new Date(c.created_at).toLocaleDateString()}</small>
                                        <p class="mb-1 mt-2">${c.content}</p>
                                        <button class="btn btn-sm btn-link reply-btn" data-id="${c.id}">Balas</button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    $('#comments-list').html(html);
                } else {
                    $('#comment-count').text('0');
                    $('#comments-list').html('<p class="text-muted">Belum ada komentar. Jadilah yang pertama!</p>');
                }
            },
            error: function() {
                $('#comments-list').html('<p class="text-muted">Komentar belum tersedia.</p>');
            }
        });
    }
});
</script>