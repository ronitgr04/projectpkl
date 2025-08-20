<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\MahasiswaController;
// use App\Http\Controllers\KegiatanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaAbsensiController;
use App\Http\Controllers\MahasiswaKegiatanController;
use App\Http\Controllers\ProfilMahasiswaController; // TAMBAHAN BARU
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KegiatanMahasiswaController;


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
            // routes/web.php
            

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

              Route::get('kegiatan/export', [KegiatanController::class, 'export'])
                ->name('admin.kegiatan.export');

            // Resource routes untuk kegiatan dengan nama yang unik untuk admin
            Route::resource('kegiatan', KegiatanController::class)->names([
                'index' => 'admin.kegiatan.index',
                'create' => 'admin.kegiatan.create',
                'store' => 'admin.kegiatan.store',
                'show' => 'admin.kegiatan.show',
                'edit' => 'admin.kegiatan.edit',
                'update' => 'admin.kegiatan.update',
                'destroy' => 'admin.kegiatan.destroy',
            ]);
            // Route::get('/kegiatan', function () {
            //     return view('admin.kegiatan');
            // })->name('admin.kegiatan');
            // Route::get('/kegiatan', function () {
            // $kegiatan = Kegiatan::with('user')->where('user_id', auth()->id())->paginate(10);
            // return view('dashboard.kegiatan', compact('kegiatan'));
            // });
            

            // Route::get('/pengaturan', function () {
            //     return view('admin.pengaturan');
            // })->name('admin.pengaturan');
            Route::get('/instansi', [InstansiController::class, 'index'])->name('admin.instansi.index');
            Route::put('/instansi', [InstansiController::class, 'update'])->name('admin.instansi.update');
            Route::delete('/instansi/remove-logo', [InstansiController::class, 'removeLogo'])->name('admin.instansi.remove-logo');

            Route::get('/administrator', function () {
                return view('admin.administrator');
            })->name('admin.administrator');
        });
    });


                                 ////////  MAHASISWA /////////
    // User/Mahasiswa level routes
    Route::middleware('auth.custom:User')->group(function () {
        // Mahasiswa Dashboard
        Route::get('/mahasiswa/dashboard', [AuthController::class, 'mahasiswaDashboard'])->name('mahasiswa.dashboard');
        
        // Routes khusus mahasiswa
        Route::prefix('mahasiswa')->group(function () {
            Route::get('/', function () {
                return view('mahasiswa.index');
            })->name('mahasiswa.index');

            // Route::get('/absensi', function () {
            //     return view('mahasiswa.absensi');
            // })->name('mahasiswa.absensi');
            Route::get('/absensi', [MahasiswaAbsensiController::class, 'index'])->name('mahasiswa.absensi');
            Route::post('/absensi', [MahasiswaAbsensiController::class, 'store'])->name('mahasiswa.absensi.store');
            Route::get('/absensi/check-today', [MahasiswaAbsensiController::class, 'checkTodayAttendance'])->name('mahasiswa.absensi.check-today');

            // Route::get('/kegiatan', function () {
            //     return view('mahasiswa.kegiatan');
            // })->name('mahasiswa.kegiatan');

            //
             Route::get('kegiatan/export/form', [MahasiswaKegiatanController::class, 'showExportForm'])
                ->name('mahasiswa.kegiatan.export.form');
            Route::post('kegiatan/export/pdf', [MahasiswaKegiatanController::class, 'exportPdf'])
                ->name('mahasiswa.kegiatan.export.pdf');

            // Resource routes untuk kegiatan
            Route::resource('kegiatan', MahasiswaKegiatanController::class)->names([
                'index' => 'mahasiswa.kegiatan.index',
                'create' => 'mahasiswa.kegiatan.create',
                'store' => 'mahasiswa.kegiatan.store',
                'show' => 'mahasiswa.kegiatan.show',
                'edit' => 'mahasiswa.kegiatan.edit',
                'update' => 'mahasiswa.kegiatan.update',
                'destroy' => 'mahasiswa.kegiatan.destroy',
            ]);



            Route::get('/riwayatabsensi', function () {
                return view('mahasiswa.riwayatabsensi');
            })->name('mahasiswa.riwayatabsensi');

            // Profil
            Route::get('/profil', function () {
                return view('mahasiswa.profil');
            })->name('mahasiswa.profil');
            Route::middleware(['auth'])->group(function () {
            Route::get('/profil', [MahasiswaController::class, 'profilSaya'])->name('profil.mahasiswa');
            });
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
