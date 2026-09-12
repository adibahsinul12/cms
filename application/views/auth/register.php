<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CMS Diskominfo</title>
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
            --danger: #C4432A;
            --success: #1F8A57;
            --warn: #C98A1E;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg);
            color: var(--ink);
            font-family: 'Inter', system-ui, sans-serif;
            display: flex;
            align-items: stretch;
        }

        a { color: var(--blue-500); text-decoration: none; }
        a:hover { text-decoration: underline; }

        .screen {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left brand panel */
        .panel-brand {
            flex: 1.1;
            position: relative;
            background: var(--navy-900);
            color: var(--white);
            padding: 96px 56px 56px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }
        .panel-brand::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 80% 10%, rgba(47,111,237,0.35), transparent 45%),
                radial-gradient(circle at 10% 90%, rgba(231,163,62,0.18), transparent 40%);
        }
        .panel-brand > * { position: relative; }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 17px;
        }
        .brand .mark {
            width: 30px; height: 30px;
            border-radius: 7px;
            background: linear-gradient(135deg, var(--blue-500), var(--gold-500));
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
        }

        /* Back button — glassmorphism */
        .btn-back {
            position: fixed;
            top: 24px;
            left: 24px;
            z-index: 50;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px 9px 12px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 999px;
            color: var(--white);
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(15, 42, 71, 0.18);
            transition: background 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateX(-3px);
        }
        .btn-back:active { transform: translateX(-1px) scale(0.97); }
        .btn-back svg {
            width: 15px; height: 15px;
            transition: transform 0.2s ease;
        }
        .btn-back:hover svg { transform: translateX(-2px); }

        @media (max-width: 860px) {
            .btn-back {
                top: 16px;
                left: 16px;
                background: rgba(15, 42, 71, 0.55);
                border-color: rgba(255, 255, 255, 0.2);
            }
            .panel-form { padding-top: 76px; }
        }

        .panel-brand .headline {
            font-family: 'Source Serif 4', Georgia, serif;
            font-size: 34px;
            font-weight: 600;
            line-height: 1.28;
            max-width: 380px;
        }
        .panel-brand .sub {
            color: rgba(255,255,255,0.7);
            font-size: 14.5px;
            max-width: 360px;
            margin-top: 14px;
        }

        .checklist {
            list-style: none;
            padding: 0;
            margin: 22px 0 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .checklist li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            color: rgba(255,255,255,0.75);
        }
        .checklist li::before {
            content: "";
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--gold-500);
            flex-shrink: 0;
        }

        .panel-brand .footnote {
            color: rgba(255,255,255,0.45);
            font-size: 12.5px;
        }

        /* Right form panel */
        .panel-form {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 32px;
            background: var(--white);
        }

        .form-card { width: 100%; max-width: 380px; }

        .form-card h2 {
            font-family: 'Source Serif 4', Georgia, serif;
            font-size: 26px;
            font-weight: 600;
            color: var(--navy-900);
            margin: 0 0 6px;
        }
        .form-card .lead {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 26px;
        }

        .field { margin-bottom: 16px; }
        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--navy-900);
            margin-bottom: 6px;
        }
        .field .input-wrap { position: relative; }

        .field input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 14.5px;
            color: var(--ink);
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .field input:focus {
            border-color: var(--blue-500);
            box-shadow: 0 0 0 3px rgba(47,111,237,0.15);
        }

        .toggle-pass {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            padding: 4px;
        }
        .toggle-pass:hover { color: var(--blue-500); }

        /* Password strength meter */
        .strength-meter {
            display: flex;
            gap: 4px;
            margin-top: 8px;
            height: 4px;
        }
        .strength-meter span {
            flex: 1;
            background: var(--border);
            border-radius: 2px;
            transition: background 0.2s ease;
        }
        .strength-label {
            font-size: 12px;
            margin-top: 6px;
            color: var(--muted);
            min-height: 15px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: var(--blue-500);
            color: var(--white);
            font-family: inherit;
            font-size: 14.5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s ease, transform 0.1s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 6px;
        }
        .btn-submit:hover:not(:disabled) { background: #245ad0; }
        .btn-submit:active:not(:disabled) { transform: scale(0.99); }
        .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }

        .btn-spinner {
            width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: var(--white);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }
        .btn-submit.is-loading .btn-spinner { display: inline-block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .foot-link {
            margin-top: 20px;
            text-align: center;
            font-size: 13.5px;
            color: var(--muted);
        }

        @media (max-width: 860px) {
            .panel-brand { display: none; }
            .panel-form { padding: 32px 24px; }
        }
    </style>
</head>
<body>

    <button type="button" class="btn-back" id="btn-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 18l-6-6 6-6"/>
        </svg>
        Kembali
    </button>

    <div class="screen">
        <div class="panel-brand">
            <div class="brand">
                <span class="mark">🌐</span> Portal Diskominfo
            </div>
            <div>
                <div class="headline">Satu akun untuk mengelola seluruh konten layanan publik.</div>
                <div class="sub">Buat akun pegawai untuk mulai menulis dan mempublikasikan berita instansi.</div>
                <ul class="checklist">
                    <li>Kelola berita dan pengumuman</li>
                    <li>Kolaborasi antar tim Diskominfo</li>
                    <li>Akses dasbor CMS kapan saja</li>
                </ul>
            </div>
            <div class="footnote">&copy; 2026 Portal Diskominfo</div>
        </div>

        <div class="panel-form">
            <div class="form-card">
                <h2>Daftar Akun Baru</h2>
                <p class="lead">Lengkapi data berikut untuk membuat akun pegawai.</p>

                <form id="form-register" novalidate>
                    <div class="field">
                        <label for="full_name">Nama Lengkap</label>
                        <div class="input-wrap">
                            <input type="text" id="full_name" placeholder="Nama Pegawai" required autocomplete="name">
                        </div>
                    </div>
                    <div class="field">
                        <label for="username">Username</label>
                        <div class="input-wrap">
                            <input type="text" id="username" placeholder="Username unik" required autocomplete="username">
                        </div>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <div class="input-wrap">
                            <input type="email" id="email" placeholder="email@diskominfo.go.id" required autocomplete="email">
                        </div>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <input type="password" id="password" placeholder="Minimal 6 karakter" required minlength="6" autocomplete="new-password">
                            <button type="button" class="toggle-pass" id="toggle-pass">Tampilkan</button>
                        </div>
                        <div class="strength-meter">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="strength-label" id="strength-label"></div>
                    </div>
                    <button type="submit" class="btn-submit" id="btn-register">
                        <span class="btn-spinner"></span>
                        <span class="btn-label">Daftar</span>
                    </button>
                </form>

                <div class="foot-link">
                    Sudah punya akun? <a href="<?= base_url('login') ?>">Login di sini</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/js/notifications.js') ?>"></script>
    <script>
        $(document).ready(function() {

            $('#btn-back').on('click', function() {
                if (document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
                    window.history.back();
                } else {
                    window.location.href = '/';
                }
            });

            $('#toggle-pass').on('click', function() {
                const input = $('#password');
                const isHidden = input.attr('type') === 'password';
                input.attr('type', isHidden ? 'text' : 'password');
                $(this).text(isHidden ? 'Sembunyikan' : 'Tampilkan');
            });

            const $bars = $('.strength-meter span');
            const $strengthLabel = $('#strength-label');
            const colors = { weak: '#C4432A', fair: '#C98A1E', strong: '#1F8A57' };

            $('#password').on('input', function() {
                const val = $(this).val();
                let score = 0;
                if (val.length >= 6) score++;
                if (val.length >= 10 && /[0-9]/.test(val)) score++;
                if (/[A-Z]/.test(val) && /[^A-Za-z0-9]/.test(val)) score++;

                $bars.css('background', 'var(--border)');
                if (val.length === 0) {
                    $strengthLabel.text('');
                    return;
                }
                if (score <= 1) {
                    $bars.eq(0).css('background', colors.weak);
                    $strengthLabel.text('Lemah — tambahkan lebih banyak karakter').css('color', colors.weak);
                } else if (score === 2) {
                    $bars.slice(0, 2).css('background', colors.fair);
                    $strengthLabel.text('Cukup — bisa lebih kuat dengan simbol').css('color', colors.fair);
                } else {
                    $bars.css('background', colors.strong);
                    $strengthLabel.text('Kuat').css('color', colors.strong);
                }
            });

            $('#form-register').on('submit', function(e) {
                e.preventDefault();

                const $btn = $('#btn-register');
                $btn.prop('disabled', true).addClass('is-loading');
                $btn.find('.btn-label').text('Memproses...');

                $.ajax({
                    url: '<?= base_url("api/auth/register") ?>',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        full_name: $('#full_name').val(),
                        username: $('#username').val(),
                        email: $('#email').val(),
                        password: $('#password').val()
                    }),
                    success: function(res) {
                        if (res.status === 'success') {
                            notify('Registrasi berhasil! Mengalihkan ke halaman login...', 'success');
                            setTimeout(function() {
                                window.location.href = '<?= base_url("login") ?>';
                            }, 1500);
                        } else {
                            $btn.prop('disabled', false).removeClass('is-loading');
                            $btn.find('.btn-label').text('Daftar');
                            notify(res.message || 'Gagal mendaftar', 'danger');
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).removeClass('is-loading');
                        $btn.find('.btn-label').text('Daftar');
                        let err = 'Gagal mendaftar';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            err = xhr.responseJSON.message;
                        }
                        notify(err, 'danger');
                    }
                });
            });
        });
    </script>
</body>
</html>