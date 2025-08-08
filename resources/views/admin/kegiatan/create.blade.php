<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Tambah Kegiatan - Admin</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>
<body class="">
    @extends('layouts.content')
    @section('content')
    <div class="bg-white m-6 rounded-lg shadow">
        <!-- Header -->
        <div class="p-6 border-b  border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Tambah Kegiatan</h2>
                    <p class="text-sm text-gray-600 mt-1">Tambahkan kegiatan baru untuk mahasiswa PKL</p>
                </div>
                <a href="{{ route('admin.kegiatan.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <span class="material-icons text-sm mr-2">arrow_back</span>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="p-6">
            <form action="{{ route('admin.kegiatan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Alert Errors -->
                @if($errors->any())
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex">
                            <span class="material-icons text-red-600 mr-2">error</span>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mahasiswa -->
                    <div class="md:col-span-2">
                        <label for="mahasiswa_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <select name="mahasiswa_id" id="mahasiswa_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mahasiswa_id') border-red-500 @enderror">
                            <option value="">Pilih Mahasiswa</option>
                            @foreach($mahasiswa as $mhs)
                                <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                    {{ $mhs->nama }} - {{ $mhs->nim }}
                                </option>
                            @endforeach
                        </select>
                        @error('mahasiswa_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hari -->
                    <div>
                        <label for="hari" class="block text-sm font-medium text-gray-700 mb-2">
                            Hari <span class="text-red-500">*</span>
                        </label>
                        <select name="hari" id="hari" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('hari') border-red-500 @enderror">
                            <option value="">Pilih Hari</option>
                            <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                            <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                        </select>
                        @error('hari')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal" required
                               value="{{ old('tanggal') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal') border-red-500 @enderror">
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam -->
                    <div>
                        <label for="jam" class="block text-sm font-medium text-gray-700 mb-2">
                            Jam <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="jam" id="jam" required
                               value="{{ old('jam') }}"
                               placeholder="Contoh: 08:00 - 12:00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jam') border-red-500 @enderror">
                        @error('jam')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Format: jam mulai - jam selesai</p>
                    </div>

                    <!-- Kegiatan -->
                    <div class="md:col-span-2">
                        <label for="kegiatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="kegiatan" id="kegiatan" rows="4" required
                                  placeholder="Deskripsi kegiatan yang dilakukan..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kegiatan') border-red-500 @enderror">{{ old('kegiatan') }}</textarea>
                        @error('kegiatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Jelaskan kegiatan yang dilakukan dengan detail</p>
                    </div>

                    <!-- Foto Dokumentasi -->
                    <div class="md:col-span-2">
                        <label for="foto_dokumentasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Dokumentasi
                        </label>

                        <!-- Upload Area -->
                        <div class="relative">
                            <input type="file"
                                   name="foto_dokumentasi"
                                   id="foto_dokumentasi"
                                   accept="image/*"
                                   class="hidden"
                                   onchange="previewImage(this)">

                            <!-- Upload Button/Drop Zone -->
                            <div id="uploadArea"
                                 onclick="document.getElementById('foto_dokumentasi').click()"
                                 class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200 @error('foto_dokumentasi') border-red-500 @enderror">
                                <div id="uploadContent">
                                    <span class="material-icons text-4xl text-gray-400 mb-2">cloud_upload</span>
                                    <p class="text-sm text-gray-600 mb-1">Klik untuk upload foto dokumentasi</p>
                                    <p class="text-xs text-gray-500">Format: JPG, PNG, JPEG. Maksimal 5MB</p>
                                </div>

                                <!-- Preview Area (Hidden by default) -->
                                <div id="previewArea" class="hidden">
                                    <img id="imagePreview" src="" alt="Preview" class="max-w-full h-48 object-cover rounded-lg mx-auto mb-3">
                                    <p id="fileName" class="text-sm text-gray-600 mb-2"></p>
                                    <button type="button"
                                            onclick="removeImage()"
                                            class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs rounded-lg transition-colors">
                                        <span class="material-icons text-sm mr-1">delete</span>
                                        Hapus Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        @error('foto_dokumentasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Upload foto dokumentasi kegiatan (opsional)</p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.kegiatan.index') }}"
                       class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <span class="material-icons text-sm mr-2 inline">save</span>
                        Simpan Kegiatan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto fill hari when tanggal is selected
        document.getElementById('tanggal').addEventListener('change', function() {
            const date = new Date(this.value);
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dayName = days[date.getDay()];

            document.getElementById('hari').value = dayName;
        });

        // Time input helper
        document.getElementById('jam').addEventListener('focus', function() {
            if (!this.value) {
                this.placeholder = 'Contoh: 08:00 - 16:00';
            }
        });

        // Image preview function
        function previewImage(input) {
            const uploadContent = document.getElementById('uploadContent');
            const previewArea = document.getElementById('previewArea');
            const imagePreview = document.getElementById('imagePreview');
            const fileName = document.getElementById('fileName');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Validate file size (5MB = 5 * 1024 * 1024 bytes)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    input.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    fileName.textContent = file.name;

                    uploadContent.classList.add('hidden');
                    previewArea.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Remove image function
        function removeImage() {
            const uploadContent = document.getElementById('uploadContent');
            const previewArea = document.getElementById('previewArea');
            const fileInput = document.getElementById('foto_dokumentasi');

            fileInput.value = '';
            uploadContent.classList.remove('hidden');
            previewArea.classList.add('hidden');
        }

        // Drag and drop functionality
        const uploadArea = document.getElementById('uploadArea');

        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-blue-400', 'bg-blue-50');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('foto_dokumentasi').files = files;
                previewImage(document.getElementById('foto_dokumentasi'));
            }
        });
    </script>
    @endsection
</body>
</html>
