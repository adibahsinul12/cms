<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | CMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            padding: 40px;
            width: 100%;
            max-width: 480px;
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .register-card .logo {
            text-align: center;
            font-size: 48px;
            margin-bottom: 10px;
        }
        .register-card h2 {
            text-align: center;
            color: #333;
            margin-bottom: 5px;
            font-weight: 700;
        }
        .register-card p.subtitle {
            text-align: center;
            color: #888;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-right: none;
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .login-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .alert {
            border-radius: 8px;
            font-size: 14px;
        }
        .password-toggle {
            cursor: pointer;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="logo">📝</div>
        <h2>Daftar Akun</h2>
        <p class="subtitle">Buat akun baru untuk CMS Admin</p>

        <div id="alert-message"></div>

        <form id="register-form">
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-id-card text-muted"></i></span>
                    <input type="text" class="form-control" id="full_name" placeholder="Nama lengkap kamu" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Username *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" class="form-control" id="username" placeholder="Username unik" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" class="form-control" id="email" placeholder="email@example.com" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" class="form-control" id="password" placeholder="Min. 6 karakter" required minlength="6">
                    <span class="input-group-text password-toggle" onclick="togglePassword('password', 'eye-icon-1')">
                        <i class="fas fa-eye text-muted" id="eye-icon-1"></i>
                    </span>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Konfirmasi Password *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" class="form-control" id="password_confirm" placeholder="Ulangi password" required>
                    <span class="input-group-text password-toggle" onclick="togglePassword('password_confirm', 'eye-icon-2')">
                        <i class="fas fa-eye text-muted" id="eye-icon-2"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-register" id="btn-register">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </button>
        </form>

        <div class="login-link"><a href="<?= base_url('login') ?>">Login di sini</a>
            Sudah punya akun? 
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        $(document).ready(function() {
            $('#register-form').on('submit', function(e) {
                e.preventDefault();

                const password = $('#password').val();
                const confirm = $('#password_confirm').val();

                if (password !== confirm) {
                    showAlert('Password dan konfirmasi tidak sama!', 'danger');
                    return;
                }

                const btn = $('#btn-register');
                const originalText = btn.html();
                btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

                const data = {
                    full_name: $('#full_name').val(),
                    username: $('#username').val(),
                    email: $('#email').val(),
                    password: password,
                    role_id: 4 // Default: Subscriber
                };

                $.ajax({
                    url: '<?= base_url("api/auth/register") ?>',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(res) {
                        if (res.status === 'success') {
                            showAlert('Registrasi berhasil! Silakan login.', 'success');
                            setTimeout(function() {
                                window.location.href = '<?= base_url("login") ?>';
                            }, 1500);
                        } else {
                            showAlert(res.message || 'Registrasi gagal!', 'danger');
                            btn.html(originalText).prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON;
                        let msg = 'Registrasi gagal!';
                        if (err && err.errors) {
                            msg = Object.values(err.errors).join('<br>');
                        } else if (err && err.message) {
                            msg = err.message;
                        }
                        showAlert(msg, 'danger');
                        btn.html(originalText).prop('disabled', false);
                    }
                });
            });
        });

        function showAlert(message, type) {
            const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
            const alert = `<div class="alert alert-${type} alert-dismissible fade show">
                <i class="fas fa-${icon}"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            $('#alert-message').html(alert);
            setTimeout(() => $('#alert-message').html(''), 5000);
        }
    </script>
</body>
</html>