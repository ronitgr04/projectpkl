<!DOCTYPE html>
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>Laravel</title>

    @vite('resources/css/app.css')
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

</head>

<body class="">
    <div className="">

        @extends('layouts.contentMahasiswa')

        @section('content')
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-semibold mb-2">Selamat Datang, {{ auth()->user()->username }}.</h2>
            <p class="text-gray-600">
                    Absensi Mahasiswa 
            </p>
        </div>
        @endsection

    </div>
</body>

</html> --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>     {{-- 424 --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Absensi</title>
    @vite('resources/css/app.css')
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>
<body class="">
    <div class="">
        @extends('layouts.contentMahasiswa')
        @section('content')
        <div class="p-6">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('mahasiswa.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L9 3.414V19a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-3.5h2.5a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H15V3.414l7.293 7.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Absensi</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <!-- Page Title -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Absensi</h1>
            </div>
            <!-- Jam Absensi Info -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h6 class="font-semibold text-blue-800">Jam Absensi: {{ $instansi->getFormattedAttendanceHours() }}</h6>
                        <p class="text-sm text-blue-600">
                            Waktu sekarang: <span id="currentTimeDisplay" class="font-medium">{{ $currentTime->format('H:i:s') }}</span>
                            @if(!$isWithinAttendanceHours)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Di luar jam absensi
                            </span>
                            @else
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Dalam jam absensi
                            </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <!-- Location Info -->
            @if($instansi->isLocationCheckEnabled())
            @if(!isset($todayAttendance) && !$todayAttendance)
            <div class="mb-6 bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-purple-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <h6 class="font-semibold text-purple-800">Pengecekan Lokasi Aktif</h6>
                        <p class="text-sm text-purple-600 mb-2">
                            Untuk absensi "Hadir", Anda harus berada dalam radius {{ $instansi->formatted_radius }} dari kantor.
                        </p>
                        <div id="locationStatus" class="text-sm text-purple-600">
                            <span id="locationMessage">Mengecek lokasi...</span>
                            <span id="locationAccuracy" class="ml-2"></span>
                        </div>
                        <div id="distanceInfo" class="mt-2 text-sm font-medium" style="display: none;"></div>
                    </div>
                </div>
            </div>
            @endif


            @endif

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="mb-6 flex items-center p-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8" onclick="this.parentElement.style.display='none'">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
            @endif
            @if(session('error'))
            <div class="mb-6 flex items-center p-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8" onclick="this.parentElement.style.display='none'">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
            @endif
            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Absensi -->
               <!-- BAGIAN YANG HARUS DIPERBAIKI DI VIEW mahasiswa.absensi -->

<!-- Form Absensi Section - LOGIC DIPERBAIKI -->
<div class="lg:col-span-2">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                Form Absensi
            </h3>
        </div>
        <div class="p-6">
            @if(isset($todayAttendance) && $todayAttendance)
                <!-- SUDAH ABSEN HARI INI - Tampilkan status -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                    <div class="flex items-center justify-center mb-4">
                        <svg class="w-16 h-16 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-green-800 mb-2">Absensi Sudah Dilakukan</h4>
                    <p class="text-green-700 mb-4">Anda sudah melakukan absensi hari ini</p>

                    <div class="bg-white rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-semibold text-gray-700">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $todayAttendance->status_badge_class }} ml-2">
                                    {{ $todayAttendance->status }}
                                </span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Waktu:</span>
                                <span class="text-gray-900 ml-2">{{ $todayAttendance->waktu_display }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Tanggal:</span>
                                <span class="text-gray-900 ml-2">{{ $todayAttendance->tanggal_formatted }}</span>
                            </div>
                            @if($todayAttendance->hasLocation() && $todayAttendance->status === 'Hadir')
                            <div>
                                <span class="font-semibold text-gray-700">Jarak:</span>
                                <span class="text-gray-900 ml-2">{{ $todayAttendance->formatted_distance }}</span>
                            </div>
                            @endif
                        </div>

                        @if($todayAttendance->keterangan)
                        <div class="mt-3 pt-3 border-t">
                            <span class="font-semibold text-gray-700">Keterangan:</span>
                            <p class="text-gray-900 mt-1">{{ $todayAttendance->keterangan }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="text-sm text-green-600">
                        <p>✓ Absensi Anda telah tercatat dalam sistem</p>
                        <p class="mt-1">Absensi berikutnya dapat dilakukan besok pada jam {{ $instansi->getFormattedAttendanceHours() }}</p>
                    </div>
                </div>

            @elseif(!$isWithinAttendanceHours)
                <!-- DI LUAR JAM ABSENSI - Tidak bisa absen -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <div class="flex items-center justify-center mb-4">
                        <svg class="w-16 h-16 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-red-800 mb-2">Absensi Tidak Tersedia</h4>
                    <p class="text-red-700 mb-4">
                        Absensi hanya dapat dilakukan pada jam <strong>{{ $instansi->getFormattedAttendanceHours() }}</strong>
                    </p>
                    <div class="bg-white rounded-lg p-4">
                        <div class="text-sm text-red-600">
                            <p><strong>Waktu saat ini:</strong> <span id="outsideHoursTime">{{ $currentTime->format('H:i:s') }}</span> WIB</p>
                            <p class="mt-2">Silakan kembali pada jam operasional untuk melakukan absensi</p>
                        </div>
                    </div>
                </div>

            @else
                <!-- FORM ABSENSI - Bisa absen dan belum absen hari ini -->
                <form action="{{ route('mahasiswa.absensi.store') }}" method="POST" id="absensiForm">
                    @csrf
                    <!-- Hidden fields untuk lokasi -->
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <!-- Informasi Mahasiswa -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Induk Mahasiswa</label>
                            <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $mahasiswa->nim ?? 'Data tidak tersedia' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $mahasiswa->nama_lengkap ?? 'Data tidak tersedia' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Universitas</label>
                            <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $mahasiswa->universitas ?? 'Data tidak tersedia' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                            <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ date('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu</label>
                            <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md" id="currentTime">{{ $currentTime->format('H:i') }}</p>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Hadir" {{ old('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="Izin" {{ old('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                                <option value="Sakit" {{ old('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            </select>
                            @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Location Warning for Hadir -->
                    @if($instansi->isLocationCheckEnabled())
                    <div id="locationWarning" class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4" style="display: none;">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h6 class="font-semibold text-yellow-800">Perhatian: Pengecekan Lokasi Diperlukan</h6>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Untuk absensi "Hadir", Anda harus berada dalam radius {{ $instansi->formatted_radius }} dari kantor.
                                    Pastikan GPS aktif dan izinkan akses lokasi pada browser.
                                </p>
                                <div id="locationCheckResult" class="mt-2 text-sm font-medium"></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Keterangan -->
                    <div class="mb-6">
                        <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Keterangan
                            <span class="text-red-500" id="requiredIndicator" style="display: none;">*</span>
                            <span class="text-gray-500 text-xs">(Wajib untuk status Izin dan Sakit)</span>
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror"
                            placeholder="Masukkan keterangan jika diperlukan (misal: alasan izin/sakit)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" id="submitBtn" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Kirim Absensi
                        </button>
                        {{-- <a href="#" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Kirim Absensi
                        </a> --}}
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
                <!-- Sidebar Info -->
                <div class="space-y-6">
                    <!-- Attendance Hours Card -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                Jadwal Absensi
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-indigo-600 mb-2">
                                    {{ $instansi->getFormattedAttendanceHours() }}
                                </div>
                                <p class="text-gray-600 mb-4">Jam Operasional Absensi</p>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700">Status saat ini:</span>
                                        @if($isWithinAttendanceHours)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Tersedia
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            Tidak Tersedia
                                        </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        Waktu: <span id="sidebarTime" class="font-medium">{{ $currentTime->format('H:i:s') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Location Info Card -->
                    @if($instansi->isLocationCheckEnabled())
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-purple-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                Info Lokasi
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-4">
                                <div class="text-2xl font-bold text-purple-600 mb-2">
                                    {{ $instansi->formatted_radius }}
                                </div>
                                <p class="text-gray-600 mb-4">Radius Absensi</p>
                            </div>
                            <div class="space-y-3">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="text-sm font-medium text-gray-700">Lokasi Kantor:</div>
                                    <div class="text-xs text-gray-600 mt-1">{{ $instansi->location_address }}</div>
                                    @if($instansi->google_maps_url)
                                    <a href="{{ $instansi->google_maps_url }}" target="_blank" class="inline-flex items-center mt-2 text-xs text-purple-600 hover:text-purple-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Lihat di Maps
                                    </a>
                                    @endif
                                </div>
                                <div id="userLocationInfo" class="bg-gray-50 rounded-lg p-3">
                                    <div class="text-sm font-medium text-gray-700">Lokasi Anda:</div>
                                    <div id="userLocationText" class="text-xs text-gray-600 mt-1">Memuat lokasi...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Info Card -->
                   
        <script>
            // Global variables
            let userLocation = null;
            let locationCheckEnabled = {{ $instansi->isLocationCheckEnabled() ? 'true' : 'false' }};
            let officeLocation = @json($instansi->hasLocation() ? ['lat' => $instansi->latitude, 'lon' => $instansi->longitude, 'radius' => $instansi->radius_absensi] : null);
            let isLocationFetching = false;

            // Geolocation functions
            function checkGeolocationSupport() {
                return 'geolocation' in navigator;
            }

            // Fixed to always get fresh location like the location test
            function getCurrentPosition() {
                return new Promise((resolve, reject) => {
                    if (!checkGeolocationSupport()) {
                        reject(new Error('Geolocation tidak didukung oleh browser ini'));
                        return;
                    }

                    const options = {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 0  // Critical fix: Always get fresh location, no cache
                    };

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            resolve({
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude,
                                accuracy: position.coords.accuracy,
                                timestamp: position.timestamp
                            });
                        },
                        (error) => {
                            let errorMessage = 'Gagal mendapatkan lokasi: ';
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMessage += 'Akses lokasi ditolak oleh pengguna';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMessage += 'Informasi lokasi tidak tersedia';
                                    break;
                                case error.TIMEOUT:
                                    errorMessage += 'Waktu habis dalam mendapatkan lokasi';
                                    break;
                                default:
                                    errorMessage += 'Kesalahan tidak diketahui';
                                    break;
                            }
                            reject(new Error(errorMessage));
                        },
                        options
                    );
                });
            }

            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371000; // Earth's radius in meters
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                         Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                         Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                return R * c;
            }

            function formatDistance(distance) {
                if (distance >= 1000) {
                    return (distance / 1000).toFixed(2) + ' km';
                }
                return Math.round(distance) + ' m';
            }

            // Update location info
            function updateLocationInfo() {
                if (!locationCheckEnabled || !officeLocation || isLocationFetching) {
                    return;
                }

                isLocationFetching = true;

                const locationMessage = document.getElementById('locationMessage');
                const userLocationText = document.getElementById('userLocationText');
                if (locationMessage) {
                    locationMessage.innerHTML = '<span class="text-blue-600">Mendapatkan lokasi terbaru...</span>';
                }
                if (userLocationText) {
                    userLocationText.innerHTML = '<span class="text-blue-600">Mencari sinyal GPS...</span>';
                }

                getCurrentPosition()
                    .then(position => {
                        userLocation = position;
                        // Set hidden form fields
                        document.getElementById('latitude').value = position.latitude;
                        document.getElementById('longitude').value = position.longitude;

                        // Calculate distance to office
                        const distance = calculateDistance(
                            position.latitude, position.longitude,
                            officeLocation.lat, officeLocation.lon
                        );
                        const withinRadius = distance <= officeLocation.radius;
                        const formattedDistance = formatDistance(distance);
                        const allowedRadius = formatDistance(officeLocation.radius);

                        // Update UI
                        if (locationMessage) {
                            locationMessage.innerHTML = withinRadius ?
                                '<span class="text-green-600">Dalam radius kantor ✓</span>' :
                                '<span class="text-red-600">Di luar radius kantor ✗</span>';
                        }

                        if (userLocationText) {
                            userLocationText.innerHTML = `
                                Jarak: ${formattedDistance} dari kantor<br>
                                <span class="text-xs ${withinRadius ? 'text-green-600' : 'text-red-600'}">
                                    ${withinRadius ? '✓' : '✗'} Radius: ${allowedRadius} | Akurasi: ±${Math.round(position.accuracy)}m
                                </span>
                            `;
                        }

                        const distanceInfo = document.getElementById('distanceInfo');
                        if (distanceInfo) {
                            distanceInfo.innerHTML = `
                                <span class="${withinRadius ? 'text-green-600' : 'text-red-600'}">
                                    ${withinRadius ? '✓' : '✗'} Jarak: ${formattedDistance} (Max: ${allowedRadius})
                                </span>
                            `;
                            distanceInfo.style.display = 'block';
                        }

                        // Update location check result in form
                        const locationCheckResult = document.getElementById('locationCheckResult');
                        if (locationCheckResult) {
                            locationCheckResult.innerHTML = `
                                <span class="${withinRadius ? 'text-green-600' : 'text-red-600'}">
                                    ${withinRadius ? '✓ Lokasi valid' : '✗ Lokasi di luar radius'}: ${formattedDistance}
                                </span>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Location error:', error);
                        if (locationMessage) {
                            locationMessage.innerHTML = '<span class="text-red-600">Gagal mendapatkan lokasi</span>';
                        }
                        if (userLocationText) {
                            userLocationText.innerHTML = `<span class="text-red-600">${error.message}</span>`;
                        }
                        const locationCheckResult = document.getElementById('locationCheckResult');
                        if (locationCheckResult) {
                            locationCheckResult.innerHTML = `<span class="text-red-600">✗ ${error.message}</span>`;
                        }
                    })
                    .finally(() => {
                        isLocationFetching = false;
                    });
            }

            // Update live time and check attendance hours
            function updateTime() {
                const now = new Date();
                const jakartaTime = new Date(now.toLocaleString("en-US", {
                    timeZone: "Asia/Jakarta"
                }));
                const timeString = jakartaTime.toLocaleTimeString('id-ID');
                const currentHour = jakartaTime.getHours();
                const currentMinute = jakartaTime.getMinutes();
                const currentSecond = jakartaTime.getSeconds();
                const currentTimeFormatted = String(currentHour).padStart(2, '0') + ':' + String(currentMinute).padStart(2, '0');
                const currentTimeWithSeconds = String(currentHour).padStart(2, '0') + ':' + String(currentMinute).padStart(2, '0') + ':' + String(currentSecond).padStart(2, '0');

                // Update all time displays
                const liveTimeElement = document.getElementById('liveTime');
                if (liveTimeElement) {
                    liveTimeElement.textContent = currentTimeWithSeconds;
                }
                const currentTimeElement = document.getElementById('currentTime');
                if (currentTimeElement) {
                    currentTimeElement.textContent = currentTimeFormatted;
                }
                const currentTimeDisplayElement = document.getElementById('currentTimeDisplay');
                if (currentTimeDisplayElement) {
                    currentTimeDisplayElement.textContent = currentTimeWithSeconds;
                }
                const sidebarTimeElement = document.getElementById('sidebarTime');
                if (sidebarTimeElement) {
                    sidebarTimeElement.textContent = currentTimeWithSeconds;
                }
                const outsideHoursTimeElement = document.getElementById('outsideHoursTime');
                if (outsideHoursTimeElement) {
                    outsideHoursTimeElement.textContent = currentTimeFormatted;
                }
            }

            // Handle status change
            function handleStatusChange() {
                const statusSelect = document.getElementById('status');
                const keteranganField = document.getElementById('keterangan');
                const requiredIndicator = document.getElementById('requiredIndicator');
                const locationWarning = document.getElementById('locationWarning');
                const locationCheckResult = document.getElementById('locationCheckResult');

                if (!statusSelect) return;

                const selectedStatus = statusSelect.value;

                // Handle keterangan requirement
                if (selectedStatus === 'Izin' || selectedStatus === 'Sakit') {
                    keteranganField.placeholder = 'Contoh: Izin untuk keperluan keluarga, mengurus administrasi, dll.';
                    keteranganField.setAttribute('required', 'required');
                    keteranganField.classList.add('border-yellow-300');
                    if (requiredIndicator) requiredIndicator.style.display = 'inline';
                } else {
                    keteranganField.placeholder = 'Masukkan keterangan jika diperlukan';
                    keteranganField.removeAttribute('required');
                    keteranganField.classList.remove('border-yellow-300');
                    if (requiredIndicator) requiredIndicator.style.display = 'none';
                }

                // Handle location warning for "Hadir" status
                if (locationCheckEnabled && locationWarning) {
                    if (selectedStatus === 'Hadir') {
                        locationWarning.style.display = 'block';
                        updateLocationInfo(); // Update location when "Hadir" is selected
                    } else {
                        locationWarning.style.display = 'none';
                        // Clear location data for non-Hadir status (optional)
                        document.getElementById('latitude').value = '';
                        document.getElementById('longitude').value = '';
                        if (locationCheckResult) {
                            locationCheckResult.innerHTML = '';
                        }
                    }
                }
            }

            // NEW: Get fresh location before submission for "Hadir" status
            async function getFreshLocationForSubmission() {
                const locationCheckResult = document.getElementById('locationCheckResult');

                if (locationCheckResult) {
                    locationCheckResult.innerHTML = `
                        <div class="text-blue-600">
                            <svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mendapatkan lokasi terbaru...
                        </div>
                    `;
                }

                try {
                    const position = await getCurrentPosition();
                    document.getElementById('latitude').value = position.latitude;
                    document.getElementById('longitude').value = position.longitude;

                    // Calculate distance to office
                    const distance = calculateDistance(
                        position.latitude, position.longitude,
                        officeLocation.lat, officeLocation.lon
                    );

                    const withinRadius = distance <= officeLocation.radius;
                    const formattedDistance = formatDistance(distance);

                    if (locationCheckResult) {
                        locationCheckResult.innerHTML = `
                            <span class="${withinRadius ? 'text-green-600' : 'text-red-600'}">
                                ${withinRadius ? '✓ Lokasi valid' : '✗ Lokasi di luar radius'}: ${formattedDistance}
                            </span>
                        `;
                    }

                    return {
                        position,
                        withinRadius,
                        distance
                    };
                } catch (error) {
                    if (locationCheckResult) {
                        locationCheckResult.innerHTML = `<span class="text-red-600">✗ ${error.message}</span>`;
                    }
                    throw error;
                }
            }

            // Form validation and submission - now fully async
            async function handleFormSubmission(e) {
                e.preventDefault(); // Always prevent default to handle async

                const form = document.getElementById('absensiForm');
                const status = document.querySelector('select[name="status"]').value;
                const keterangan = document.querySelector('textarea[name="keterangan"]').value.trim();
                const submitBtn = document.getElementById('submitBtn');

                // Validate keterangan for Izin and Sakit
                if ((status === 'Izin' || status === 'Sakit') && keterangan === '') {
                    alert('Keterangan wajib diisi untuk status ' + status);
                    document.querySelector('textarea[name="keterangan"]').focus();
                    return;
                }

                // Validate location for "Hadir" status
                if (status === 'Hadir' && locationCheckEnabled && officeLocation) {
                    try {
                        // Get fresh location right before submission
                        const locationData = await getFreshLocationForSubmission();

                        if (!locationData.withinRadius) {
                            const userDistance = formatDistance(locationData.distance);
                            const allowedRadius = formatDistance(officeLocation.radius);
                            throw new Error(`Anda berada di luar radius kantor.\nJarak Anda: ${userDistance}\nRadius yang diizinkan: ${allowedRadius}`);
                        }
                    } catch (error) {
                        alert(error.message);
                        return;
                    }
                }

                // Show loading state
                if (submitBtn) {
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    `;
                    submitBtn.disabled = true;
                }

                // Submit the form
                form.submit();
            }

            // Real-time validation for keterangan field
            function handleKeteranganInput() {
                const keteranganField = document.querySelector('textarea[name="keterangan"]');
                if (!keteranganField) return;

                keteranganField.addEventListener('input', function() {
                    const status = document.querySelector('select[name="status"]').value;
                    if ((status === 'Izin' || status === 'Sakit') && this.value.trim() === '') {
                        this.classList.add('border-red-300');
                    } else {
                        this.classList.remove('border-red-300');
                    }
                });
            }

            // Initialize everything when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Update time immediately and then every second
                updateTime();
                setInterval(updateTime, 1000);

                // Initialize location check if enabled
                if (locationCheckEnabled && officeLocation) {
                    updateLocationInfo();
                    // Update location every 30 seconds
                    setInterval(updateLocationInfo, 30000);
                }

                // Set up event listeners
                const statusSelect = document.getElementById('status');
                if (statusSelect) {
                    statusSelect.addEventListener('change', handleStatusChange);
                }

                const absensiForm = document.getElementById('absensiForm');
                if (absensiForm) {
                    absensiForm.addEventListener('submit', handleFormSubmission);
                }

                // Set up keterangan field validation
                handleKeteranganInput();

                // Request location permission early if needed
                if (locationCheckEnabled && checkGeolocationSupport()) {
                    // Small delay to let page load completely
                    setTimeout(() => {
                        getCurrentPosition().catch(error => {
                            console.log('Initial location request failed:', error.message);
                        });
                    }, 1000);
                }
            });

            // Handle page visibility change to update location when page becomes visible
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden && locationCheckEnabled && officeLocation) {
                    updateLocationInfo();
                }
            });

            // Debug function for location testing
            function debugLocation() {
                console.log('Location Check Debug Info:');
                console.log('Location Check Enabled:', locationCheckEnabled);
                console.log('Office Location:', officeLocation);
                console.log('User Location:', userLocation);
                console.log('Geolocation Support:', checkGeolocationSupport());
                if (userLocation && officeLocation) {
                    const distance = calculateDistance(
                        userLocation.latitude, userLocation.longitude,
                        officeLocation.lat, officeLocation.lon
                    );
                    console.log('Distance to office:', formatDistance(distance));
                    console.log('Within radius:', distance <= officeLocation.radius);
                }
            }

            // Make debug function available globally
            window.debugLocation = debugLocation;
        </script>
        @endsection
    </div>
</body>
</html>
