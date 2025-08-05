<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-4">
                    <h4 class="text-white mb-4">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </h4>
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} mb-2" href="{{ route('dashboard') }}">
                            <i class="bi bi-house me-2"></i>Beranda
                        </a>

                        @if(auth()->user()->isAdmin())
                            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }} mb-2" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-gear me-2"></i>Admin Dashboard
                            </a>
                        @endif

                        @if(auth()->user()->isUser())
                            <a
                            class="nav-link "
                            >
                                <i class="bi bi-person me-2"></i>User Panel
                            </a>
                        @endif

                        <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }} mb-2" href="{{ route('profile') }}">
                            <i class="bi bi-person-circle me-2"></i>Profil
                        </a>

                        <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">

                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-start w-100 border-0 p-0"
                                    onclick="return confirm('Apakah Anda yakin ingin logout?')">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">@yield('title', 'Dashboard')</h2>
                    <div class="d-flex align-items-center">
                        <span class="me-3">
                            <i class="bi bi-person-circle me-2"></i>{{ auth()->user()->username }}
                        </span>
                        <span class="badge bg-primary">{{ auth()->user()->level }}</span>
                    </div>
                </div>

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

                <!-- Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
