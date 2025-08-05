<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
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
        .form-control {
            border: 1px solid #dddfe2;
            border-radius: 6px;
            padding: 14px 16px;
            font-size: 16px;
            margin-bottom: 12px;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #1877f2;
            box-shadow: none;
            outline: none;
        }
        .form-control::placeholder {
            color: #8a8d91;
        }
        .btn-login {
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
        .btn-login:hover {
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
        .form-check {
            margin: 20px 0;
        }
        .form-check-label {
            color: #606770;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h1 class="app-title">ABSENSI & KEGIATAN</h1>
            <p class="app-subtitle">Silahkan Login Terlebih Dahulu</p>

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

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
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

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="Password"
                           required>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-login">
                    Masuk
                </button>
            </form>

            {{-- Uncomment jika ada halaman register
            <div class="text-center mt-3">
                <span class="text-muted">Belum punya akun? </span>
                <a href="{{ route('register') }}" class="text-decoration-none">Daftar sekarang</a>
            </div>
            --}}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
