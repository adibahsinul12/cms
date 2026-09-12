<?php
// Ambil status login dari session (gaya sama seperti di sidebar.php)
$CI =& get_instance();
$CI->load->library('session');
$is_logged_in = (bool) $CI->session->userdata('logged_in');
$current_role = $CI->session->userdata('role_id');
$current_name = $CI->session->userdata('full_name');
$current_user_id = $CI->session->userdata('user_id');
$staff_roles = [1, 3, 4];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Resmi Diskominfo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,500;8..60,600;8..60,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy-900: #0F2A47;
            --navy-700: #1B4570;
            --blue-500: #2F6FED;
            --gold-500: #E7A33E;
            --bg: #F6F8FB;
            --ink: #16233A;
            --muted: #5B6B84;
            --border: #DCE4F0;
            --white: #FFFFFF;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: 'Inter', system-ui, sans-serif;
            line-height: 1.55;
        }

        h1, h2, h3, .headline {
            font-family: 'Source Serif 4', Georgia, serif;
            color: var(--navy-900);
            margin: 0;
        }

        a { text-decoration: none; color: inherit; }

        .wrap {
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Navbar */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: var(--navy-900);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            transition: padding 0.25s ease;
        }
        .navbar .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 18px;
            padding-bottom: 18px;
            transition: padding 0.25s ease;
        }
        .navbar.is-scrolled .wrap { padding-top: 12px; padding-bottom: 12px; }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--white);
            font-weight: 600;
            font-size: 17px;
            letter-spacing: 0.2px;
        }
        .brand .mark {
            width: 30px; height: 30px;
            border-radius: 7px;
            background: linear-gradient(135deg, var(--blue-500), var(--gold-500));
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
        }

        .btn-login {
            border: 1px solid rgba(255,255,255,0.35);
            color: var(--white);
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.15s ease, border-color 0.15s ease;
        }
        .btn-login:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.6);
        }

        /* Nav auth area (logged in state) */
        .nav-auth {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.2);
            color: var(--white);
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            font-family: inherit;
        }
        .nav-user-btn:hover { background: rgba(255,255,255,0.15); }

        .nav-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 12px 30px rgba(15,42,71,0.25);
            min-width: 240px;
            padding: 8px;
            display: none;
            z-index: 50;
        }
        .nav-dropdown.show { display: block; }
        .nav-dropdown .dd-header {
            padding: 8px 10px;
            font-size: 12.5px;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            margin-bottom: 6px;
        }
        .nav-dropdown button, .nav-dropdown a {
            display: block;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 9px 10px;
            font-family: inherit;
            font-size: 13.5px;
            color: var(--ink);
            border-radius: 6px;
            cursor: pointer;
        }
        .nav-dropdown button:hover, .nav-dropdown a:hover {
            background: var(--bg);
        }
        .nav-dropdown .btn-logout-item { color: #C4432A; }
        .nav-dropdown .pending-note {
            font-size: 12.5px;
            color: var(--gold-500);
            padding: 8px 10px;
            background: #FFF7E8;
            border-radius: 6px;
            margin: 4px 0;
        }

        /* Request role modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,42,71,0.55);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.show { display: flex; }
        .modal-box {
            background: var(--white);
            border-radius: 12px;
            padding: 28px;
            width: 100%;
            max-width: 380px;
        }
        .modal-box h3 { font-size: 20px; margin-bottom: 8px; }
        .modal-box p { color: var(--muted); font-size: 13.5px; margin-bottom: 20px; }
        .role-choice {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }
        .role-choice label {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 14px;
            cursor: pointer;
        }
        .role-choice label:hover { border-color: var(--blue-500); }
        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
        .btn-plain {
            padding: 9px 16px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            border: 1px solid var(--border);
            background: var(--white);
            color: var(--ink);
        }
        .btn-primary-solid {
            padding: 9px 16px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            border: none;
            background: var(--blue-500);
            color: var(--white);
        }

        /* Hero */
        .hero {
            position: relative;
            background: var(--navy-900);
            overflow: hidden;
            padding: 76px 0 96px;
        }
        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 85% 15%, rgba(47,111,237,0.35), transparent 45%),
                radial-gradient(circle at 15% 85%, rgba(231,163,62,0.18), transparent 40%);
        }
        .hero .wrap { position: relative; }
        .eyebrow {
            color: var(--gold-500);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 14px;
        }
        .hero h1 {
            color: var(--white);
            font-size: 44px;
            font-weight: 600;
            max-width: 620px;
            line-height: 1.2;
        }
        .hero p {
            color: rgba(255,255,255,0.75);
            font-size: 17px;
            max-width: 520px;
            margin-top: 16px;
        }

        .search-box {
            margin-top: 32px;
            max-width: 460px;
            display: flex;
            background: var(--white);
            border-radius: 8px;
            padding: 4px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
        }
        .search-box input {
            flex: 1;
            border: none;
            outline: none;
            padding: 12px 14px;
            font-family: inherit;
            font-size: 14px;
            color: var(--ink);
            background: transparent;
        }
        .search-box button {
            border: none;
            background: var(--blue-500);
            color: var(--white);
            padding: 0 18px;
            border-radius: 6px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s ease;
        }
        .search-box button:hover { background: #245ad0; }

        /* News section */
        .news-section { padding: 64px 0 80px; }
        .section-head {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            padding-bottom: 16px;
            margin-bottom: 32px;
        }
        .section-head h2 { font-size: 26px; font-weight: 600; }
        .result-count { color: var(--muted); font-size: 14px; }

        .news-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 28px;
        }

        .featured-card {
            background: var(--navy-900);
            border-radius: 12px;
            padding: 32px;
            color: var(--white);
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            position: relative;
            transition: transform 0.2s ease;
        }
        .featured-card:hover { transform: translateY(-3px); }
        .featured-card .tag {
            display: inline-block;
            background: var(--gold-500);
            color: var(--navy-900);
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
            margin-bottom: 16px;
            width: fit-content;
        }
        .featured-card h3 {
            color: var(--white);
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .featured-card p {
            color: rgba(255,255,255,0.75);
            font-size: 14.5px;
            margin: 0 0 14px;
        }
        .featured-card .date {
            color: rgba(255,255,255,0.55);
            font-size: 13px;
        }

        .news-list { display: flex; flex-direction: column; gap: 16px; }

        .news-item {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 18px 20px;
            transition: border-color 0.15s ease, transform 0.15s ease;
        }
        .news-item:hover {
            border-color: var(--blue-500);
            transform: translateX(2px);
        }
        .news-item h3 {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .news-item p {
            color: var(--muted);
            font-size: 13.5px;
            margin: 0 0 10px;
        }
        .news-item .date {
            color: var(--muted);
            font-size: 12.5px;
        }

        .empty-state, .error-state, .loading-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }
        .error-state { color: #C4432A; }

        .spinner {
            width: 32px; height: 32px;
            border: 3px solid var(--border);
            border-top-color: var(--blue-500);
            border-radius: 50%;
            margin: 0 auto 14px;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        footer {
            background: var(--navy-900);
            color: rgba(255,255,255,0.6);
            padding: 28px 0;
            text-align: center;
            font-size: 13px;
        }

        @media (max-width: 860px) {
            .news-grid { grid-template-columns: 1fr; }
            .hero h1 { font-size: 32px; }
        }
    </style>
</head>
<body>

    <nav class="navbar" id="navbar">
        <div class="wrap">
            <a class="brand" href="#">
                <span class="mark">🌐</span> Portal Diskominfo
            </a>

            <?php if (!$is_logged_in): ?>
                <!-- BELUM LOGIN -->
                <a href="<?= base_url('login') ?>" class="btn-login">Login</a>

            <?php elseif (in_array($current_role, $staff_roles, true)): ?>
                <!-- SUDAH LOGIN SEBAGAI STAFF (Admin/Editor/Author) -->
                <div class="nav-auth">
                    <a href="<?= base_url('admin/dashboard') ?>" class="btn-login">
                        <i class="fas fa-th-large"></i> Dashboard Admin
                    </a>
                    <button type="button" class="nav-user-btn" id="btn-nav-user">
                        <?= htmlspecialchars($current_name ?: 'Akun') ?> ▾
                    </button>
                    <div class="nav-dropdown" id="nav-dropdown">
                        <div class="dd-header">Masuk sebagai <?= htmlspecialchars($current_name ?: '') ?></div>
                        <button type="button" class="btn-logout-item" id="btn-nav-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </div>
                </div>

            <?php else: ?>
                <!-- SUDAH LOGIN SEBAGAI USER BIASA (role_id 2) -->
                <div class="nav-auth">
                    <button type="button" class="nav-user-btn" id="btn-nav-user">
                        <?= htmlspecialchars($current_name ?: 'Akun') ?> ▾
                    </button>
                    <div class="nav-dropdown" id="nav-dropdown">
                        <div class="dd-header">Masuk sebagai <?= htmlspecialchars($current_name ?: '') ?></div>
                        <div id="request-status-area">
                            <!-- diisi oleh JS: tombol "Ajukan jadi Staff" atau pesan "Menunggu persetujuan" -->
                        </div>
                        <button type="button" class="btn-logout-item" id="btn-nav-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <!-- MODAL: Ajukan jadi Staff -->
    <div class="modal-overlay" id="request-role-modal">
        <div class="modal-box">
            <h3>Ajukan jadi Staff</h3>
            <p>Pilih posisi yang ingin kamu ajukan. Admin akan meninjau permintaan ini.</p>
            <div class="role-choice">
                <label>
                    <input type="radio" name="requested_role" value="3" checked>
                    <span><strong>Editor</strong> — kelola konten & moderasi komentar</span>
                </label>
                <label>
                    <input type="radio" name="requested_role" value="4">
                    <span><strong>Author</strong> — menulis & mengelola post sendiri</span>
                </label>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-plain" id="btn-cancel-request">Batal</button>
                <button type="button" class="btn-primary-solid" id="btn-submit-request">Kirim Pengajuan</button>
            </div>
        </div>
    </div>

    <header class="hero">
        <div class="wrap">
            <div class="eyebrow">Layanan Informasi Publik</div>
            <h1>Berita dan pengumuman resmi daerah, satu tempat terpercaya.</h1>
            <p>Diskominfo menyajikan informasi terkini seputar kebijakan, kegiatan, dan layanan pemerintah untuk warga.</p>
            <div class="search-box">
                <input type="text" id="search-input" placeholder="Cari berita berdasarkan judul...">
                <button type="button" id="search-btn">Cari</button>
            </div>
        </div>
    </header>

    <main class="news-section">
        <div class="wrap">
            <div class="section-head">
                <h2>Berita Terkini</h2>
                <span class="result-count" id="result-count"></span>
            </div>
            <div class="news-grid" id="public-posts-container">
                <div class="loading-state">
                    <div class="spinner"></div>
                    Memuat berita...
                </div>
            </div>
        </div>
    </main>

    <footer>
        &copy; 2026 Portal Diskominfo. Seluruh hak cipta dilindungi.
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const IS_LOGGED_IN = <?= $is_logged_in ? 'true' : 'false' ?>;
        const CURRENT_ROLE = <?= json_encode($current_role) ?>;
        const CURRENT_USER_ID = <?= json_encode($current_user_id) ?>;
        const USER_API = '<?= base_url("api/user") ?>';

        let allPosts = [];

        function formatDate(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        function renderPosts(posts) {
            const container = $('#public-posts-container');
            $('#result-count').text(posts.length ? posts.length + ' berita' : '');

            if (posts.length === 0) {
                container.html('<div class="empty-state">Tidak ada berita yang cocok dengan pencarian kamu.</div>');
                return;
            }

            let html = '';
            const [featured, ...rest] = posts;

            html += `
                <div class="featured-card">
                    <span class="tag">Sorotan</span>
                    <h3>${featured.title}</h3>
                    <p>${featured.excerpt || 'Tidak ada ringkasan.'}</p>
                    <span class="date">${formatDate(featured.created_at)}</span>
                </div>
                <div class="news-list">
            `;

            if (rest.length === 0) {
                html += '<div class="news-item"><p>Belum ada berita lain.</p></div>';
            } else {
                rest.forEach(function(post) {
                    html += `
                        <div class="news-item">
                            <h3>${post.title}</h3>
                            <p>${post.excerpt || 'Tidak ada ringkasan.'}</p>
                            <span class="date">${formatDate(post.created_at)}</span>
                        </div>
                    `;
                });
            }

            html += '</div>';
            container.html(html);
        }

        function filterPosts(query) {
            const q = query.trim().toLowerCase();
            if (!q) return allPosts;
            return allPosts.filter(function(post) {
                return post.title.toLowerCase().includes(q);
            });
        }

        $(document).ready(function() {
            $.ajax({
                url: '<?= base_url("api/public/posts") ?>',
                type: 'GET',
                success: function(res) {
                    if (res.status === 'success') {
                        allPosts = res.data || [];
                        renderPosts(allPosts);
                    } else {
                        $('#public-posts-container').html('<div class="error-state">Gagal memuat data berita.</div>');
                    }
                },
                error: function() {
                    $('#public-posts-container').html('<div class="error-state">Gagal memuat data berita.</div>');
                }
            });

            $('#search-input').on('input', function() {
                renderPosts(filterPosts($(this).val()));
            });
            $('#search-btn').on('click', function() {
                renderPosts(filterPosts($('#search-input').val()));
            });

            $(window).on('scroll', function() {
                $('#navbar').toggleClass('is-scrolled', $(window).scrollTop() > 10);
            });

            // ===== Dropdown akun =====
            $('#btn-nav-user').on('click', function(e) {
                e.stopPropagation();
                $('#nav-dropdown').toggleClass('show');
            });
            $(document).on('click', function() {
                $('#nav-dropdown').removeClass('show');
            });
            $('#nav-dropdown').on('click', function(e) { e.stopPropagation(); });

            // ===== Logout =====
            $('#btn-nav-logout').on('click', function() {
                if (!confirm('Yakin mau logout?')) return;
                fetch('<?= base_url("api/auth/logout") ?>', { method: 'POST' })
                    .then(function() { window.location.href = '<?= base_url("home") ?>'; })
                    .catch(function() { window.location.href = '<?= base_url("home") ?>'; });
            });

            // ===== Ajukan jadi Staff (khusus role User biasa) =====
            if (IS_LOGGED_IN && CURRENT_ROLE == 2) {
                checkRequestStatus();
            }

            function checkRequestStatus() {
                $.ajax({
                    url: `${USER_API}/detail/${CURRENT_USER_ID}`,
                    method: 'GET',
                    success: function(res) {
                        const u = res.data;
                        if (u.request_status === 'pending') {
                            $('#request-status-area').html(
                                '<div class="pending-note"><i class="fas fa-clock"></i> Pengajuan sedang menunggu persetujuan Admin.</div>'
                            );
                        } else {
                            $('#request-status-area').html(
                                '<button type="button" id="btn-open-request">Ajukan jadi Staff</button>'
                            );
                        }
                    }
                });
            }

            $(document).on('click', '#btn-open-request', function() {
                $('#nav-dropdown').removeClass('show');
                $('#request-role-modal').addClass('show');
            });

            $('#btn-cancel-request').on('click', function() {
                $('#request-role-modal').removeClass('show');
            });

            $('#btn-submit-request').on('click', function() {
                const roleId = $('input[name="requested_role"]:checked').val();
                $.ajax({
                    url: `${USER_API}/request-role`,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ requested_role_id: parseInt(roleId, 10) }),
                    success: function(res) {
                        alert(res.message || 'Pengajuan berhasil dikirim!');
                        $('#request-role-modal').removeClass('show');
                        checkRequestStatus();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Gagal mengirim pengajuan');
                    }
                });
            });
        });
    </script>
</body>
</html>