<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>Pengaturan Instansi</title>

    @vite('resources/css/app.css')
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCoyecTc0zxzZnAgPRhRnVRrJGlhh_NjFM"></script>
    <style>
        .time-input {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M10 18a8 8 0 100-16 8 8 0 000 16zm0-8V6m0 4l3 3'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }
    </style>
</head>

<body class="">
    <div className="">

        @extends('layouts.content')

        @section('content')
        <!-- CSRF Token untuk JavaScript -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="bg-white m-6 rounded-lg shadow-lg p-6">
            <!-- Header -->
            <div class="flex items-center mb-6">
                <div class="bg-blue-500 p-2 rounded-lg mr-4">
                    <span class="material-icons text-white text-2xl">business</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Pengaturan Website</h2>
                    <p class="text-gray-600">Kelola informasi profil instansi dan pengaturan absensi</p>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <div class="flex">
                    <span class="material-icons mr-2">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <div class="flex">
                    <span class="material-icons mr-2">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.instansi.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Profil Instansi Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="material-icons mr-2">business</span>
                        Profil Instansi
                    </h3>

                    <!-- Logo Upload Section -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo Instansi</label>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                @if($instansi && $instansi->logo)
                                <img id="logo-preview" src="{{ $instansi->logo_url }}" alt="Logo" class="w-24 h-24 object-cover rounded-lg border-2 border-gray-300">
                                <button type="button" onclick="removeLogo()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                    <span class="material-icons text-sm">close</span>
                                </button>
                                @else
                                <div id="logo-placeholder" class="w-24 h-24 bg-gray-200 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <span class="material-icons text-gray-400 text-3xl">image</span>
                                </div>
                                <img id="logo-preview" src="" alt="Logo" class="w-24 h-24 object-cover rounded-lg border-2 border-gray-300 hidden">
                                @endif
                            </div>
                            <div>
                                <input type="file" id="logo" name="logo" accept="image/*" class="hidden" onchange="previewLogo(this)">
                                <label for="logo" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg cursor-pointer transition-colors inline-flex items-center">
                                    <span class="material-icons mr-2">upload</span>
                                    Pilih Logo
                                </label>
                                <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF (Max: 2MB)</p>
                            </div>
                        </div>
                        @error('logo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Instansi -->
                    <div class="mb-4">
                        <label for="nama_instansi" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Instansi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_instansi" name="nama_instansi"
                            value="{{ old('nama_instansi', $instansi ? $instansi->nama_instansi : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_instansi') border-red-500 @enderror"
                            placeholder="Masukkan nama instansi">
                        @error('nama_instansi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Ketua -->
                    <div class="mb-4">
                        <label for="nama_ketua" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Ketua (Pimpinan) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_ketua" name="nama_ketua"
                            value="{{ old('nama_ketua', $instansi ? $instansi->nama_ketua : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_ketua') border-red-500 @enderror"
                            placeholder="Masukkan nama ketua/pimpinan">
                        @error('nama_ketua')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Pembina -->
                    <div class="mb-4">
                        <label for="nama_pembina" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pembina Magang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_pembina" name="nama_pembina"
                            value="{{ old('nama_pembina', $instansi ? $instansi->nama_pembina : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_pembina') border-red-500 @enderror"
                            placeholder="Masukkan nama pembina magang">
                        @error('nama_pembina')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="mb-4">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat" name="alamat" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('alamat') border-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap instansi">{{ old('alamat', $instansi ? $instansi->alamat : '') }}</textarea>
                        @error('alamat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No Telepon -->
                    <div class="mb-4">
                        <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-2">
                            No Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="no_telp" name="no_telp"
                            value="{{ old('no_telp', $instansi ? $instansi->no_telp : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_telp') border-red-500 @enderror"
                            placeholder="Contoh: (0711) 352-282">
                        @error('no_telp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Website -->
                    <div class="mb-4">
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                            Website (Opsional)
                        </label>
                        <input type="url" id="website" name="website"
                            value="{{ old('website', $instansi ? $instansi->website : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('website') border-red-500 @enderror"
                            placeholder="https://example.com">
                        @error('website')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-green-600">location_on</span>
                            Pengaturan Lokasi Absensi
                        </h3>
                        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">
                            <div class="flex items-center">
                                <span class="material-icons mr-2">info</span>
                                <span class="text-sm">Aktifkan pengecekan lokasi untuk memastikan peserta magang berada di dalam radius yang ditentukan saat melakukan absensi.</span>
                            </div>
                        </div>

                        <!-- Enable Location Check -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="enable_location_check" name="enable_location_check"
                                    value="1" {{ old('enable_location_check', $instansi->enable_location_check ?? true) ? 'checked' : '' }}
                                    class="h-5 w-5 text-green-600 rounded focus:ring-green-500">
                                <label for="enable_location_check" class="ml-2 block text-sm font-medium text-gray-700">
                                    Aktifkan Pengecekan Lokasi
                                </label>
                            </div>
                            <p class="text-sm text-gray-500 mt-1 ml-7">Jika diaktifkan, sistem akan memeriksa lokasi pengguna saat absensi</p>
                        </div>

                        <!-- Latitude dan Longitude -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Latitude -->
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="material-icons text-sm align-middle mr-1">gps_fixed</span>
                                    Latitude <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="latitude" name="latitude"
                                    value="{{ old('latitude', $instansi->latitude ?? '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('latitude') border-red-500 @enderror"
                                    placeholder="Contoh: -6.200000">
                                @error('latitude')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500 mt-1">Koordinat garis lintang lokasi kantor</p>
                            </div>

                            <!-- Longitude -->
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="material-icons text-sm align-middle mr-1">gps_fixed</span>
                                    Longitude <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="longitude" name="longitude"
                                    value="{{ old('longitude', $instansi->longitude ?? '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('longitude') border-red-500 @enderror"
                                    placeholder="Contoh: 106.816666">
                                @error('longitude')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500 mt-1">Koordinat garis bujur lokasi kantor</p>
                            </div>
                        </div>

                        <!-- Radius Absensi -->
                        <div class="mb-4">
                            <label for="radius_absensi" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="material-icons text-sm align-middle mr-1">distance</span>
                                Radius Absensi (meter) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="radius_absensi" name="radius_absensi"
                                value="{{ old('radius_absensi', $instansi->radius_absensi ?? 1000) }}"
                                min="1" max="10000"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('radius_absensi') border-red-500 @enderror"
                                placeholder="Contoh: 1000">
                            @error('radius_absensi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">Radius area absensi valid (1-10.000 meter)</p>
                        </div>

                        <!-- Preview Lokasi -->
                        <div id="location-preview" class="mt-4 p-4 bg-white rounded-lg border {{ $instansi->hasLocation() ? '' : 'hidden' }}">
                            <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <span class="material-icons text-sm mr-1">preview</span>
                                Preview Lokasi
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center">
                                    <span class="material-icons text-green-600 text-sm mr-1">location_on</span>
                                    <span class="text-gray-600">Koordinat:</span>
                                    <span id="preview-coordinates" class="ml-1 font-mono">{{ $instansi->hasLocation() ? $instansi->latitude . ', ' . $instansi->longitude : '' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons text-blue-600 text-sm mr-1">place</span>
                                    <span class="text-gray-600">Radius:</span>
                                    <span id="preview-radius" class="ml-1 font-semibold text-blue-600">{{ $instansi->hasLocation() ? $instansi->formatted_radius : '' }}</span>
                                </div>
                                @if($instansi->hasLocation())
                                <div class="mt-2">
                                    <a href="{{ $instansi->google_maps_url }}" target="_blank"
                                        class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                        <span class="material-icons text-xs mr-1">map</span>
                                        Lihat di Google Maps
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Pencarian Lokasi -->
                        <div class="mt-4">
                            <button type="button" id="search-location-btn"
                                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                                <span class="material-icons text-sm mr-1">search</span>
                                Cari Lokasi di Peta
                            </button>
                            <p class="text-sm text-gray-500 mt-1">Gunakan alat ini untuk mencari koordinat lokasi kantor Anda</p>
                        </div>
                    </div>

                    <!-- Modal Peta -->
                    <div id="map-modal" class="fixed inset-0 backdrop-blur bg-opacity-50 hidden flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg w-11/12 max-w-4xl h-5/6 max-h-[600px] flex flex-col">
                            <div class="flex justify-between items-center p-4 border-b">
                                <h3 class="text-lg font-semibold">Pilih Lokasi Kantor</h3>
                                <button type="button" id="close-map-modal" class="text-gray-500 hover:text-gray-700">
                                    <span class="material-icons">close</span>
                                </button>
                            </div>
                            <div class="flex-1 p-4 flex flex-col">
                                <div id="map" class="flex-1 rounded-lg mb-4" style="height: 400px;"></div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                        <input type="text" id="map-latitude" class="w-full px-3 py-2 border border-gray-300 rounded" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                        <input type="text" id="map-longitude" class="w-full px-3 py-2 border border-gray-300 rounded" readonly>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end space-x-3">
                                    <button type="button" id="cancel-map-btn" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                                        Batal
                                    </button>
                                    <button type="button" id="save-location-btn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                        Simpan Lokasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengaturan Absensi Section -->
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="material-icons mr-2 text-blue-600">access_time</span>
                        Pengaturan Batasan Waktu Absensi
                    </h3>

                    <div class="bg-blue-100 border border-blue-300 text-blue-800 px-4 py-3 rounded mb-4">
                        <div class="flex items-center">
                            <span class="material-icons mr-2">info</span>
                            <span class="text-sm">Tentukan waktu mulai dan akhir untuk sistem absensi. Peserta magang hanya dapat melakukan absensi dalam rentang waktu yang ditentukan.</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jam Mulai Absensi -->
                        <div>
                            <label for="jam_mulai_absensi" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="material-icons text-sm align-middle mr-1">schedule</span>
                                Jam Mulai Absensi <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="jam_mulai_absensi" name="jam_mulai_absensi"
                                value="{{ old('jam_mulai_absensi', $instansi && $instansi->jam_mulai_absensi ? date('H:i', strtotime($instansi->jam_mulai_absensi)) : '07:00') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent time-input @error('jam_mulai_absensi') border-red-500 @enderror">
                            @error('jam_mulai_absensi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">Waktu mulai untuk melakukan absensi masuk</p>
                        </div>

                        <!-- Jam Akhir Absensi -->
                        <div>
                            <label for="jam_akhir_absensi" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="material-icons text-sm align-middle mr-1">schedule</span>
                                Jam Akhir Absensi <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="jam_akhir_absensi" name="jam_akhir_absensi"
                                value="{{ old('jam_akhir_absensi', $instansi && $instansi->jam_akhir_absensi ? date('H:i', strtotime($instansi->jam_akhir_absensi)) : '17:00') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent time-input @error('jam_akhir_absensi') border-red-500 @enderror">
                            @error('jam_akhir_absensi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">Waktu akhir untuk melakukan absensi keluar</p>
                        </div>
                    </div>

                    <!-- Preview Waktu Absensi -->
                    <div class="mt-4 p-4 bg-white rounded-lg border">
                        <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <span class="material-icons text-sm mr-1">preview</span>
                            Preview Waktu Absensi
                        </h4>
                        <div class="flex items-center space-x-4 text-sm">
                            <div class="flex items-center">
                                <span class="material-icons text-green-600 text-sm mr-1">login</span>
                                <span class="text-gray-600">Mulai:</span>
                                <span id="preview-start" class="font-semibold text-green-600 ml-1">08:00</span>
                            </div>
                            <div class="text-gray-400">—</div>
                            <div class="flex items-center">
                                <span class="material-icons text-red-600 text-sm mr-1">logout</span>
                                <span class="text-gray-600">Akhir:</span>
                                <span id="preview-end" class="font-semibold text-red-600 ml-1">09:00</span>
                            </div>
                            <div class="flex items-center ml-4 px-3 py-1 bg-blue-100 rounded-full">
                                <span class="material-icons text-blue-600 text-sm mr-1">timelapse</span>
                                <span class="text-blue-600 text-xs font-medium">Durasi: <span id="preview-duration">1 jam</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="resetForm()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <span class="material-icons text-sm align-middle mr-1">refresh</span>
                        Reset
                    </button>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors inline-flex items-center">
                        <span class="material-icons mr-2">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <script>
            // Fungsi untuk mengupdate preview lokasi
            function updateLocationPreview() {
                const latitude = document.getElementById('latitude').value;
                const longitude = document.getElementById('longitude').value;
                const radius = document.getElementById('radius_absensi').value;
                const preview = document.getElementById('location-preview');
                const previewCoordinates = document.getElementById('preview-coordinates');
                const previewRadius = document.getElementById('preview-radius');

                if (latitude && longitude) {
                    preview.classList.remove('hidden');
                    previewCoordinates.textContent = `${latitude}, ${longitude}`;

                    // Format radius
                    let radiusText = radius;
                    if (radius >= 1000) {
                        radiusText = (radius / 1000).toFixed(1) + ' km';
                    } else {
                        radiusText = radius + ' m';
                    }
                    previewRadius.textContent = radiusText;
                } else {
                    preview.classList.add('hidden');
                }
            }

            // Event listeners untuk input lokasi
            document.getElementById('latitude').addEventListener('change', updateLocationPreview);
            document.getElementById('longitude').addEventListener('change', updateLocationPreview);
            document.getElementById('radius_absensi').addEventListener('change', updateLocationPreview);
            document.getElementById('enable_location_check').addEventListener('change', function() {
                const locationFields = document.querySelectorAll('#latitude, #longitude, #radius_absensi');
                if (this.checked) {
                    locationFields.forEach(field => {
                        field.disabled = false;
                        field.closest('.mb-4, .mb-6').classList.remove('opacity-50');
                    });
                } else {
                    locationFields.forEach(field => {
                        field.disabled = true;
                        field.closest('.mb-4, #longitude, #radius_absensi').classList.add('opacity-50');
                    });
                }
            });

            // Inisialisasi status awal lokasi
            document.addEventListener('DOMContentLoaded', function() {
                updateLocationPreview();

                const enableLocationCheck = document.getElementById('enable_location_check');
                if (enableLocationCheck && !enableLocationCheck.checked) {
                    const locationFields = document.querySelectorAll('#latitude, #longitude, #radius_absensi');
                    locationFields.forEach(field => {
                        field.disabled = true;
                        field.closest('.mb-4, .mb-6').classList.add('opacity-50');
                    });
                }

                // Tambahkan event listener untuk pencarian lokasi
                const searchLocationBtn = document.getElementById('search-location-btn');
                if (searchLocationBtn) {
                    searchLocationBtn.addEventListener('click', function() {
                        document.getElementById('map-modal').classList.remove('hidden');
                        initMap();
                    });
                }

                // Event listener untuk tombol tutup modal
                document.getElementById('close-map-modal').addEventListener('click', function() {
                    document.getElementById('map-modal').classList.add('hidden');
                });

                document.getElementById('cancel-map-btn').addEventListener('click', function() {
                    document.getElementById('map-modal').classList.add('hidden');
                });

                // Event listener untuk tombol simpan lokasi
                document.getElementById('save-location-btn').addEventListener('click', function() {
                    document.getElementById('latitude').value = document.getElementById('map-latitude').value;
                    document.getElementById('longitude').value = document.getElementById('map-longitude').value;
                    document.getElementById('map-modal').classList.add('hidden');
                    updateLocationPreview();
                });
            });

            // Fungsi inisialisasi peta (hanya contoh, perlu implementasi dengan Google Maps API)
            function initMap() {
                const latitudeInput = document.getElementById('latitude');
                const longitudeInput = document.getElementById('longitude');
                const mapLatitude = document.getElementById('map-latitude');
                const mapLongitude = document.getElementById('map-longitude');

                // Gunakan nilai yang ada atau default
                const lat = latitudeInput.value ? parseFloat(latitudeInput.value) : -6.200000;
                const lng = longitudeInput.value ? parseFloat(longitudeInput.value) : 106.816666;

                mapLatitude.value = lat.toFixed(6);
                mapLongitude.value = lng.toFixed(6);

                // Buat peta
                const map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: lat,
                        lng: lng
                    },
                    zoom: 15
                });

                // Buat marker
                const marker = new google.maps.Marker({
                    position: {
                        lat: lat,
                        lng: lng
                    },
                    map: map,
                    draggable: true
                });

                // Update koordinat saat marker dipindahkan
                google.maps.event.addListener(marker, 'dragend', function(event) {
                    mapLatitude.value = event.latLng.lat().toFixed(6);
                    mapLongitude.value = event.latLng.lng().toFixed(6);
                });

                // Update marker saat peta diklik
                map.addListener('click', function(event) {
                    marker.setPosition(event.latLng);
                    mapLatitude.value = event.latLng.lat().toFixed(6);
                    mapLongitude.value = event.latLng.lng().toFixed(6);
                });
            }

            function previewLogo(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('logo-preview');
                        const placeholder = document.getElementById('logo-placeholder');

                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        if (placeholder) {
                            placeholder.classList.add('hidden');
                        }
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function removeLogo() {
                if (confirm('Apakah Anda yakin ingin menghapus logo?')) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        alert('CSRF token tidak ditemukan');
                        return;
                    }

                    fetch('/admin/instansi/remove-logo', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Gagal menghapus logo: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghapus logo');
                        });
                }
            }

            function resetForm() {
                if (confirm('Apakah Anda yakin ingin mereset form? Semua perubahan yang belum disimpan akan hilang.')) {
                    document.querySelector('form').reset();
                    const preview = document.getElementById('logo-preview');
                    const placeholder = document.getElementById('logo-placeholder');

                    // Reset logo preview
                    if (!instansi || !instansi.logo) {
                        preview.classList.add('hidden');
                        if (placeholder) {
                            placeholder.classList.remove('hidden');
                        }
                    }

                    // Reset time preview
                    updateTimePreview();
                }
            }

            // Function to update time preview
            function updateTimePreview() {
                const startTime = document.getElementById('jam_mulai_absensi').value || '07:00';
                const endTime = document.getElementById('jam_akhir_absensi').value || '17:00';

                document.getElementById('preview-start').textContent = startTime;
                document.getElementById('preview-end').textContent = endTime;

                // Calculate duration
                const start = new Date('2000-01-01 ' + startTime);
                const end = new Date('2000-01-01 ' + endTime);
                const diff = end - start;
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                let durationText = hours + ' jam';
                if (minutes > 0) {
                    durationText += ' ' + minutes + ' menit';
                }

                document.getElementById('preview-duration').textContent = durationText;
            }

            // Auto-resize textarea
            document.getElementById('alamat').addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });

            // Update time preview when time inputs change
            document.getElementById('jam_mulai_absensi').addEventListener('change', updateTimePreview);
            document.getElementById('jam_akhir_absensi').addEventListener('change', updateTimePreview);

            // Initialize time preview on page load
            document.addEventListener('DOMContentLoaded', function() {
                updateTimePreview();
            });

            // Validate time inputs
            document.getElementById('jam_akhir_absensi').addEventListener('change', function() {
                const startTime = document.getElementById('jam_mulai_absensi').value;
                const endTime = this.value;

                if (startTime && endTime && endTime <= startTime) {
                    alert('Jam akhir absensi harus lebih besar dari jam mulai absensi');
                    this.focus();
                }
            });
        </script>
        @endsection

    </div>
</body>

</html>
