<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CMS Admin</title>
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
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-card .logo {
            text-align: center;
            font-size: 48px;
            margin-bottom: 10px;
        }
        .login-card h2 {
            text-align: center;
            color: #333;
            margin-bottom: 5px;
            font-weight: 700;
        }
        .login-card p.subtitle {
            text-align: center;
            color: #888;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-right: none;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .register-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }
        .register-link a:hover {
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

    <div class="login-card">
        <div class="logo">📝</div>
        <h2>CMS Admin</h2>
        <p class="subtitle">Silakan login untuk melanjutkan</p>

        <!-- Alert -->
        <div id="alert-message"></div>

        <form id="login-form">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username atau Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" class="form-control" id="username" placeholder="Masukkan username/email" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" class="form-control" id="password" placeholder="Masukkan password" required>
                    <span class="input-group-text password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye text-muted" id="eye-icon"></i>
                    </span>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:14px;">Ingat saya</label>
                </div>
                <a href="#" style="font-size:14px; color:#667eea; text-decoration:none;">Lupa password?</a>
            </div>
            <button type="submit" class="btn btn-login" id="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="<?= base_url('auth/register') ?>">Daftar di sini</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
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
            $('#login-form').on('submit', function(e) {
                e.preventDefault();

                const btn = $('#btn-login');
                const originalText = btn.html();
                btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

                const data = {
                    username: $('#username').val(),
                    password: $('#password').val()
                };

                $.ajax({
                    url: '<?= base_url("api/auth/login") ?>',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(res) {
                        if (res.status === 'success') {
                            showAlert('Login berhasil! Mengalihkan...', 'success');
                            setTimeout(function() {
                                window.location.href = '<?= base_url("admin/dashboard") ?>';
                            }, 1000);
                        } else {
                            showAlert(res.message || 'Login gagal!', 'danger');
                            btn.html(originalText).prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON;
                        showAlert(err.message || 'Username atau password salah!', 'danger');
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