@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #dc3545 0%, #6f42c1 100%); color: white;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">
                            <i class="bi bi-shield-check me-2"></i>Admin Dashboard
                        </h4>
                        <p class="mb-0">Selamat datang di panel administrator, {{ auth()->user()->username }}!</p>
                        <small>Kode Pengguna: {{ auth()->user()->kode_pengguna }}</small>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="bi bi-gear display-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Total Users</h6>
                        <h4 class="mb-0">{{ \App\Models\User::count() }}</h4>
                    </div>
                    <i class="bi bi-people display-6"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Admin Users</h6>
                        <h4 class="mb-0">{{ \App\Models\User::where('level', 'Admin')->count() }}</h4>
                    </div>
                    <i class="bi bi-shield-check display-6"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Regular Users</h6>
                        <h4 class="mb-0">{{ \App\Models\User::where('level', 'User')->count() }}</h4>
                    </div>
                    <i class="bi bi-person display-6"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Online</h6>
                        <h4 class="mb-0">1</h4>
                    </div>
                    <i class="bi bi-wifi display-6"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Actions -->
<div class="row mb-4">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="bi bi-tools me-2"></i>Admin Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-people text-primary me-3"></i>
                        <div>
                            <h6 class="mb-0">Kelola Users</h6>
                            <small class="text-muted">Tambah, edit, hapus pengguna</small>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-gear text-success me-3"></i>
                        <div>
                            <h6 class="mb-0">Pengaturan Sistem</h6>
                            <small class="text-muted">Konfigurasi aplikasi</small>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-bar-chart text-info me-3"></i>
                        <div>
                            <h6 class="mb-0">Laporan</h6>
                            <small class="text-muted">View sistem reports</small>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-database text-warning me-3"></i>
                        <div>
                            <h6 class="mb-0">Database</h6>
                            <small class="text-muted">Backup & maintenance</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Recent Activity
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-person-plus text-white small"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">New user registered</h6>
                            <small class="text-muted">{{ now()->subMinutes(5)->diffForHumans() }}</small>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-check text-white small"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">System backup completed</h6>
                            <small class="text-muted">{{ now()->subHours(2)->diffForHumans() }}</small>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-gear text-white small"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Settings updated</h6>
                            <small class="text-muted">{{ now()->subHours(4)->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>Daftar Pengguna
                </h5>
                <button class="btn btn-light btn-sm">
                    <i class="bi bi-plus me-1"></i>Tambah User
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Kode Pengguna</th>
                                <th>Username</th>
                                <th>Level</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                            <tr>
                                <td>{{ $user->id_user }}</td>
                                <td>{{ $user->kode_pengguna }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle me-2 text-primary"></i>
                                        {{ $user->username }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->level == 'Admin' ? 'danger' : 'primary' }}">
                                        {{ $user->level }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
}
.timeline::before {
    content: '';
    position: absolute;
    left: l16px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
</style>
@endpush
