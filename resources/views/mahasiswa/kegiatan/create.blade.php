<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
        <div class="space-y-6 m-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('mahasiswa.kegiatan.index') }}"
                        class="text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Tambah Kegiatan PKL</h2>
                        <p class="text-gray-600 mt-1">Dokumentasikan kegiatan PKL yang telah dilakukan</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('mahasiswa.kegiatan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Date and Time Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date"
                                    id="tanggal"
                                    name="tanggal"
                                    value="{{ old('tanggal') }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal') border-red-500 @enderror">
                                <span class="material-icons absolute right-3 top-2 text-gray-400 pointer-events-none">calendar_today</span>
                            </div>
                            @error('tanggal')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
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
                    </div>

                    <!-- Kegiatan -->
                    <div>
                        <label for="kegiatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="kegiatan"
                            name="kegiatan"
                            rows="6"
                            placeholder="Jelaskan secara detail kegiatan yang telah dilakukan..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kegiatan') border-red-500 @enderror">{{ old('kegiatan') }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Minimal 10 karakter</p>
                        @error('kegiatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto Dokumentasi -->
                    <div>
                        <label for="foto_dokumentasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Dokumentasi
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <span class="material-icons text-4xl text-gray-400">cloud_upload</span>
                                <div class="flex text-sm text-gray-600">
                                    <label for="foto_dokumentasi" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500">
                                        <span>Upload foto</span>
                                        <input id="foto_dokumentasi" name="foto_dokumentasi" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                            </div>
                        </div>

                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <div class="relative inline-block">
                                <img id="preview" src="#" alt="Preview" class="max-w-xs h-48 object-cover rounded-lg border">
                                <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                    <span class="material-icons text-sm">close</span>
                                </button>
                            </div>
                        </div>

                        @error('foto_dokumentasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('mahasiswa.kegiatan.index') }}"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                            <span class="material-icons text-sm">save</span>
                            <span>Simpan Kegiatan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function previewImage(input) {
                const preview = document.getElementById('preview');
                const previewDiv = document.getElementById('imagePreview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        previewDiv.classList.remove('hidden');
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            function removeImage() {
                const input = document.getElementById('foto_dokumentasi');
                const preview = document.getElementById('preview');
                const previewDiv = document.getElementById('imagePreview');

                input.value = '';
                preview.src = '#';
                previewDiv.classList.add('hidden');
            }

            // Auto-resize textarea
            document.getElementById('kegiatan').addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        </script>
        @endsection

    </div>
</body>

</html>
