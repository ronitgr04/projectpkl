<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($mahasiswa) ? 'Edit' : 'Tambah' }} Data Mahasiswa</title>

    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            200: '#e5e7eb',
                            300: '#d1d5db',
                            400: '#9ca3af',
                            500: '#6b7280',
                            600: '#4b5563',
                            700: '#374151',
                            800: '#1f2937',
                            900: '#111827',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        .card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            overflow: hidden;
        }

        .input-field {
            transition: all 0.2s ease;
        }

        .input-field:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
        }

        .btn-primary {
            transition: all 0.2s ease;
            background-color: #0ea5e9;
        }

        .btn-primary:hover {
            background-color: #0284c7;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
            transform: translateY(-1px);
        }

        .profile-img {
            transition: all 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.05);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .required::after {
            content: " *";
            color: #ef4444;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center text-primary-700 hover:text-primary-800 mb-4">
                <span class="material-icons mr-2">arrow_back</span>
                Kembali ke Daftar Mahasiswa
            </a>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        {{ isset($mahasiswa) ? 'Edit Data Mahasiswa' : 'Tambah Data Mahasiswa Baru' }}
                    </h1>
                    <p class="text-gray-600 mt-2">
                        {{ isset($mahasiswa) ? 'Perbarui informasi mahasiswa magang' : 'Isi data mahasiswa magang baru dengan lengkap dan akurat' }}
                    </p>
                </div>
                @if(isset($mahasiswa))
                <div class="mt-4 md:mt-0">
                    <span class="material-icons text-2xl text-primary-500 mr-2 align-middle">info</span>
                    <span class="font-medium text-gray-700">ID: #{{ str_pad($mahasiswa->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-6">
            <div class="flex items-start">
                <span class="material-icons text-red-500 mt-1 mr-3">error</span>
                <div>
                    <h3 class="font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <form action="{{ isset($mahasiswa) ? route('admin.mahasiswa.update', $mahasiswa->id) : route('admin.mahasiswa.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($mahasiswa))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Section -->
                <div class="lg:col-span-1">
                    <div class="bg-white card mb-6">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">Foto Profil</h2>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col items-center">
                                <div class="relative mb-4">
                                    <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-primary-100">
                                        @if(isset($mahasiswa) && $mahasiswa->foto)
                                            <img src="{{ $mahasiswa->foto_url }}" alt="Foto Profil" class="w-full h-full object-cover profile-img" id="currentPhoto">
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                                <span class="material-icons text-4xl text-gray-400">person</span>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="absolute bottom-0 right-0 bg-primary-500 text-white rounded-full p-2 shadow-md hover:bg-primary-600 transition">
                                        <span class="material-icons text-sm">camera_alt</span>
                                    </button>
                                </div>

                                <input type="file" name="foto" id="foto" accept="image/*" class="hidden" onchange="previewImage(event)">
                                <label for="foto" class="cursor-pointer bg-white border border-primary-300 text-primary-600 hover:bg-primary-50 px-4 py-2 rounded-lg text-sm font-medium transition">
                                    <span class="material-icons text-xs align-middle mr-1">image</span> Pilih Foto
                                </label>
                                @error('foto')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-2 text-center">Kosongkan jika tidak ingin mengganti foto</p>

                                <div id="imagePreviewContainer" class="mt-4" style="display: none;">
                                    <p class="text-xs text-gray-500 mb-1">Preview:</p>
                                    <img id="imagePreview" class="w-32 h-32 rounded-full object-cover border-2 border-primary-100">
                                </div>
                            </div>

                            <!-- Status Section -->
                            @if(isset($mahasiswa))
                            <div class="mt-8 pt-6 border-t">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="material-icons mr-2 text-primary-500">info</span>
                                    Status Magang
                                </h3>

                                @php
                                    $status = $mahasiswa->getStatusMagangAttribute();
                                    $statusText = '';
                                    $statusBg = '';
                                    $statusIcon = '';

                                    switch($status) {
                                        case 'belum_mulai':
                                            $statusText = 'Belum Mulai';
                                            $statusBg = 'bg-blue-100 text-blue-800';
                                            $statusIcon = 'schedule';
                                            break;
                                        case 'sedang_magang':
                                            $statusText = 'Sedang Magang';
                                            $statusBg = 'bg-green-100 text-green-800';
                                            $statusIcon = 'work';
                                            break;
                                        case 'selesai':
                                            $statusText = 'Selesai';
                                            $statusBg = 'bg-gray-100 text-gray-800';
                                            $statusIcon = 'check_circle';
                                            break;
                                    }
                                @endphp

                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="material-icons text-2xl mr-3 {{
                                        $status == 'belum_mulai' ? 'text-blue-500' :
                                        ($status == 'sedang_magang' ? 'text-green-500' : 'text-gray-500')
                                    }}">
                                        {{ $statusIcon }}
                                    </span>
                                    <div>
                                        <span class="status-badge {{ $statusBg }}">
                                            {{ $statusText }}
                                        </span>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ \Carbon\Carbon::parse($mahasiswa->mulai_magang)->format('d M Y') }}
                                            hingga
                                            {{ \Carbon\Carbon::parse($mahasiswa->akhir_magang)->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Account Status -->
                    @if(isset($mahasiswa))
                    <div class="bg-white card">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">Status Akun</h2>
                        </div>
                        <div class="p-6">
                            @if($mahasiswa->hasUserAccount())
                            <div class="flex items-start p-4 bg-green-50 rounded-lg">
                                <span class="material-icons text-green-500 mt-1 mr-3">check_circle</span>
                                <div>
                                    <h3 class="font-medium text-green-800">Akun Sudah Dibuat</h3>
                                    <p class="text-sm text-green-700 mt-1">Mahasiswa ini sudah memiliki akun pengguna</p>
                                </div>
                            </div>
                            @else
                            <div class="flex items-start p-4 bg-yellow-50 rounded-lg">
                                <span class="material-icons text-yellow-500 mt-1 mr-3">warning</span>
                                <div>
                                    <h3 class="font-medium text-yellow-700">Akun Belum Dibuat</h3>
                                    <p class="text-sm text-yellow-700 mt-1">Mahasiswa ini belum memiliki akun pengguna</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white card">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">
                                {{ isset($mahasiswa) ? 'Informasi Mahasiswa' : 'Data Mahasiswa Baru' }}
                            </h2>
                            <p class="text-gray-600 text-sm mt-1">
                                {{ isset($mahasiswa) ? 'Isi data mahasiswa dengan lengkap dan akurat' : 'Isi data mahasiswa magang baru dengan lengkap dan akurat' }}
                            </p>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required">
                                        Nama Lengkap
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 material-icons text-gray-400 text-sm">person</span>
                                        <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama ?? '') }}"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-field"
                                               placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    @error('nama')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NIM -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required">
                                        NIM
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 material-icons text-gray-400 text-sm">badge</span>
                                        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim ?? '') }}"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-field"
                                               placeholder="Masukkan NIM" required>
                                    </div>
                                    @error('nim')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Universitas -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required">
                                        Universitas
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 material-icons text-gray-400 text-sm">school</span>
                                        <input type="text" name="universitas" value="{{ old('universitas', $mahasiswa->universitas ?? '') }}"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-field"
                                               placeholder="Masukkan universitas" required>
                                    </div>
                                    @error('universitas')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tanggal Mulai -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required">
                                        Tanggal Mulai Magang
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 material-icons text-gray-400 text-sm">event</span>
                                        <input type="date" name="mulai_magang" id="mulai_magang"
                                               value="{{ old('mulai_magang', isset($mahasiswa) ? $mahasiswa->mulai_magang->format('Y-m-d') : '') }}"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-field" required>
                                    </div>
                                    @error('mulai_magang')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tanggal Akhir -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required">
                                        Tanggal Akhir Magang
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 material-icons text-gray-400 text-sm">event</span>
                                        <input type="date" name="akhir_magang" id="akhir_magang"
                                               value="{{ old('akhir_magang', isset($mahasiswa) ? $mahasiswa->akhir_magang->format('Y-m-d') : '') }}"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-field" required>
                                    </div>
                                    @error('akhir_magang')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="flex items-start">
                                    <span class="material-icons text-blue-500 mt-1 mr-2">info</span>
                                    <div>
                                        <h3 class="font-medium text-blue-800">Informasi Tambahan</h3>
                                        <p class="text-sm text-blue-700 mt-1">
                                            Periode magang akan dihitung dari tanggal mulai hingga tanggal akhir.
                                            Pastikan tanggal akhir magang setelah tanggal mulai magang.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 border-t flex flex-col sm:flex-row justify-between gap-3">
                            <a href="{{ route('mahasiswa.index') }}"
                               class="btn-secondary px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium text-center flex items-center justify-center">
                                <span class="material-icons align-middle text-sm mr-1">arrow_back</span> Kembali
                            </a>
                            <button type="submit"
                                    class="btn-primary text-white px-5 py-2.5 rounded-lg font-medium flex items-center justify-center">
                                <span class="material-icons align-middle text-sm mr-1">save</span>
                                {{ isset($mahasiswa) ? 'Perbarui Data' : 'Simpan Data' }}
                            </button>
                        </div>
                    </div>

                    <!-- Info Section -->
                    <div class="mt-6 bg-white card">
                        <div class="p-6">
                            <div class="flex items-start">
                                <span class="material-icons text-blue-500 mt-1 mr-3">info</span>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Informasi Penting</h3>
                                    <ul class="mt-2 text-sm text-gray-600 list-disc pl-5 space-y-1">
                                        <li>Field dengan tanda <span class="text-red-500">*</span> wajib diisi</li>
                                        <li>Tanggal akhir magang harus setelah tanggal mulai magang</li>
                                        <li>Ukuran foto maksimal 2MB dengan format JPG, PNG, atau GIF</li>
                                        <li>Data yang diubah akan langsung tersimpan setelah klik "Simpan Data"</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Preview image before upload
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.querySelector('.profile-img');
                const previewContainer = document.getElementById('imagePreviewContainer');
                const imagePreview = document.getElementById('imagePreview');
                const currentPhoto = document.getElementById('currentPhoto');

                // Update profile image
                if (preview) {
                    preview.src = reader.result;
                } else {
                    const container = document.querySelector('.w-32.h-32');
                    const img = document.createElement('img');
                    img.src = reader.result;
                    img.className = 'w-full h-full object-cover profile-img';
                    img.id = 'currentPhoto';
                    container.innerHTML = '';
                    container.appendChild(img);
                }

                // Show the preview container
                imagePreview.src = reader.result;
                previewContainer.style.display = 'block';

                // Hide current photo if exists
                if (currentPhoto) {
                    currentPhoto.style.display = 'none';
                }
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        // Date validation to ensure akhir_magang > mulai_magang
        document.addEventListener('DOMContentLoaded', function() {
            const mulaiMagangInput = document.getElementById('mulai_magang');
            const akhirMagangInput = document.getElementById('akhir_magang');

            if (mulaiMagangInput && akhirMagangInput) {
                // Set initial min value for akhir_magang
                if (mulaiMagangInput.value) {
                    akhirMagangInput.min = mulaiMagangInput.value;
                }

                mulaiMagangInput.addEventListener('change', function() {
                    const startDate = new Date(this.value);
                    const endDate = new Date(akhirMagangInput.value);

                    if (endDate <= startDate) {
                        // Set akhir_magang to one day after mulai_magang
                        startDate.setDate(startDate.getDate() + 1);
                        akhirMagangInput.value = startDate.toISOString().split('T')[0];
                    }

                    // Update min value for akhir_magang
                    akhirMagangInput.min = this.value;
                });

                // Validate on form submit
                const form = document.querySelector('form');
                form.addEventListener('submit', function(e) {
                    const startDate = new Date(mulaiMagangInput.value);
                    const endDate = new Date(akhirMagangInput.value);

                    if (endDate <= startDate) {
                        e.preventDefault();
                        alert('Tanggal akhir magang harus setelah tanggal mulai magang!');
                        akhirMagangInput.focus();
                    }
                });
            }
        });
    </script>
</body>
</html>
