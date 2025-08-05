<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;


// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    // Register routes (opsional - hapus jika tidak diperlukan)
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Protected Routes (Authenticated users)
Route::middleware('auth.custom')->group(function () {
    // Dashboard umum - akan redirect otomatis berdasarkan level
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin only routes
    Route::middleware('auth.custom:Admin')->group(function () {
        Route::get('/admin', function () {
            return view('admin.index');
        })->name('admin.index');

        // Admin Dashboard
            Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');

            // Mahasiswa routes dengan prefix admin
            Route::prefix('admin')->group(function () {
                // Custom route untuk create account - HARUS SEBELUM resource routes
                Route::post('mahasiswa/{mahasiswa}/create-account', [MahasiswaController::class, 'createAccount'])
                    ->name('admin.mahasiswa.create-account');

                // Optional: Form untuk create account (jika ingin halaman terpisah)
                Route::get('mahasiswa/{mahasiswa}/create-account-form', [MahasiswaController::class, 'showCreateAccountForm'])
                    ->name('admin.mahasiswa.create-account-form');

                // API route (jika masih diperlukan)
                Route::get('api/mahasiswa', [MahasiswaController::class, 'api'])
                    ->name('admin.mahasiswa.api');

            // Resource routes untuk mahasiswa dengan nama yang unik
            Route::resource('mahasiswa', MahasiswaController::class)->names([
                'index' => 'admin.mahasiswa.index',
                'create' => 'admin.mahasiswa.create',
                'store' => 'admin.mahasiswa.store',
                'show' => 'admin.mahasiswa.show',
                'edit' => 'admin.mahasiswa.edit',
                'update' => 'admin.mahasiswa.update',
                'destroy' => 'admin.mahasiswa.destroy',
            ]);

            Route::get('/absensi', function () {
                return view('admin.absensi');
            })->name('admin.absensi');

            Route::get('/kegiatan', function () {
                return view('admin.kegiatan');
            })->name('admin.kegiatan');
            // Route::get('/kegiatan', function () {
            // $kegiatan = Kegiatan::with('user')->where('user_id', auth()->id())->paginate(10);
            // return view('dashboard.kegiatan', compact('kegiatan'));
            // });
            

            Route::get('/pengaturan', function () {
                return view('admin.pengaturan');
            })->name('admin.pengaturan');

            Route::get('/administrator', function () {
                return view('admin.administrator');
            })->name('admin.administrator');
        });
    });

    // User/Mahasiswa level routes
    Route::middleware('auth.custom:User')->group(function () {
        // Mahasiswa Dashboard
        Route::get('/mahasiswa/dashboard', [AuthController::class, 'mahasiswaDashboard'])->name('mahasiswa.dashboard');

        // Routes khusus mahasiswa
        Route::prefix('mahasiswa')->group(function () {
            Route::get('/', function () {
                return view('mahasiswa.index');
            })->name('mahasiswa.index');

            Route::get('/absensi', function () {
                return view('mahasiswa.absensi');
            })->name('mahasiswa.absensi');

            Route::get('/kegiatan', function () {
                return view('mahasiswa.kegiatan');
            })->name('mahasiswa.kegiatan');

            Route::get('/riwayatabsensi', function () {
                return view('mahasiswa.riwayatabsensi');
            })->name('mahasiswa.riwayatabsensi');
            Route::get('/profil', function () {
                return view('mahasiswa.profil');
            })->name('mahasiswa.profil');
        });
    });

    // Routes accessible by both Admin and User
    Route::middleware('auth.custom:Admin,User')->group(function () {
        Route::get('/profile', function () {
            return view('profile');
        })->name('profile');
        // Add more shared routes here
    });
});
