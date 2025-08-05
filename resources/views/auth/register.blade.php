<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .register-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 500px;
        }
        .app-title {
            font-size: 32px;
            font-weight: 600;
            color: #1c1e21;
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .app-subtitle {
            color: #606770;
            text-align: center;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .form-control, .form-select {
            border: 1px solid #dddfe2;
            border-radius: 6px;
            padding: 14px 16px;
            font-size: 16px;
            margin-bottom: 12px;
            transition: border-color 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #1877f2;
            box-shadow: none;
            outline: none;
        }
        .form-control::placeholder {
            color: #8a8d91;
        }
        .btn-register {
            background-color: #1877f2;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            transition: background-color 0.2s;
        }
        .btn-register:hover {
            background-color: #166fe5;
        }
        .form-label {
            display: none;
        }
        .alert {
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .invalid-feedback {
            display: block;
            margin-top: -8px;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #606770;
            font-size: 14px;
        }
        .login-link a {
            color: #1877f2;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .input-group .btn {
            border: 1px solid #dddfe2;
            border-left: none;
        }
        .input-group .form-control {
            border-right: none;
        }
        .input-group .form-control:focus + .btn {
            border-color: #1877f2;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <h1 class="app-title">ABSENSI & KEGIATAN</h1>
            <p class="app-subtitle">Silakan lengkapi form pendaftaran</p>

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kode_pengguna" class="form-label">Kode Pengguna</label>
                        <input type="text"
                               class="form-control @error('kode_pengguna') is-invalid @enderror"
                               id="kode_pengguna"
                               name="kode_pengguna"
                               value="{{ old('kode_pengguna') }}"
                               placeholder="Kode Pengguna"
                               required>
                        @error('kode_pengguna')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text"
                               class="form-control @error('username') is-invalid @enderror"
                               id="username"
                               name="username"
                               value="{{ old('username') }}"
                               placeholder="Username"
                               required>
                        @error('username')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="level" class="form-label">Level Akses</label>
                    <select class="form-select @error('level') is-invalid @enderror"
                            id="level"
                            name="level"
                            required>
                        <option value="">Pilih Level Akses</option>
                        <option value="Admin" {{ old('level') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="User" {{ old('level') == 'User' ? 'selected' : '' }}>User</option>
                    </select>
                    @error('level')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Password"
                               required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password"
                               class="form-control @error('password_confirmation') is-invalid @enderror"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="Konfirmasi Password"
                               required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePasswordConfirm">
                            <i class="bi bi-eye" id="toggleIconConfirm"></i>
                        </button>
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-register">
                    Daftar Sekarang
                </button>
            </form>

            <div class="login-link">
                <span>Sudah punya akun? </span>
                <a href="{{ route('login') }}">Masuk sekarang</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        });

        // Toggle password confirmation visibility
        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const passwordField = document.getElementById('password_confirmation');
            const toggleIcon = document.getElementById('toggleIconConfirm');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        });
    </script>
</body>
</html>
