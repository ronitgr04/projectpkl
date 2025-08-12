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


        @endsection

    </div>
</body>

</html>
