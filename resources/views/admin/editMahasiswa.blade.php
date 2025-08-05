<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Data Mahasiswa</title>

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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center text-primary-700 hover:text-primary-800 mb-4">
                <span class="material-icons mr-2">arrow_back</span>
                Kembali ke Daftar Mahasiswa
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Edit Data Mahasiswa</h1>
            <p class="text-gray-600 mt-2">Perbarui informasi mahasiswa magang</p>
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

        <!-- Main Form Container -->
        <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                                        @if($mahasiswa->foto)
                                            <img src="{{ $mahasiswa->foto_url }}" alt="Foto Profil" class="w-full h-full object-cover profile-img" id="preview-image">
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center" id="preview-placeholder">
                                                <span class="material-icons text-4xl text-gray-400">person</span>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="absolute bottom-0 right-0 bg-primary-500 text-white rounded-full p-2 shadow-md hover:bg-primary-600 transition" onclick="document.getElementById('foto').click()">
                                        <span class="material-icons text-sm">camera_alt</span>
                                    </button>
                                </div>

                                <!-- Input file foto INSIDE the form -->
                                <input type="file" name="foto" id="foto" accept="image/*" class="hidden" onchange="previewImage(event)">
                                <label for="foto" class="cursor-pointer bg-white border border-primary-300 text-primary-600 hover:bg-primary-50 px-4 py-2 rounded-lg text-sm font-medium transition">
                                    <span class="material-icons text-xs align-middle mr-1">image</span> Ganti Foto
                                </label>
                                <p class="text-xs text-gray-500 mt-2 text-center">Kosongkan jika tidak ingin mengganti foto</p>
                            </div>

                            <!-- Status Section -->
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
                        </div>
                    </div>

                    <!-- Account Status -->
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
                </div>

                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white card">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">Informasi Mahasiswa</h2>
                            <p class="text-gray-600 text-sm mt-1">Isi data mahasiswa dengan lengkap dan akurat</p>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 material-icons text-gray-400 text-sm">person</span>
                                        <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-field"
                                               placeholder="Masukkan nama lengkap" required>
                                    </div>
                                </div>

                                <!-- NIM -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        NIM <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 material-icons text-gray-400 text-sm">badge</span>
                                        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-field"
                                               placeholder="Masukkan NIM" required>
                                    </div>
                                </div>

                                <!-- Universitas -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Universitas <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 material-icons text-gray-400 text-sm">school</span>
                                        <input type="text" name="universitas" value="{{ old('universitas', $mahasiswa->universitas) }}"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-field"
                                               placeholder="Masukkan universitas" required>
                                    </div>
                                </div>

                                <!-- Tanggal Mulai -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Tanggal Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 material-icons text-gray-400 text-sm">event</span>
                                        <input type="date" name="mulai_magang" value="{{ old('mulai_magang', $mahasiswa->mulai_magang->format('Y-m-d')) }}"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-field" required>
                                    </div>
                                </div>

                                <!-- Tanggal Akhir -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Tanggal Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 material-icons text-gray-400 text-sm">event</span>
                                        <input type="date" name="akhir_magang" value="{{ old('akhir_magang', $mahasiswa->akhir_magang->format('Y-m-d')) }}"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-field" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 border-t flex flex-col sm:flex-row justify-end gap-3">
                            <a href="{{ route('admin.mahasiswa.index') }}"
                               class="btn-secondary px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium text-center">
                                <span class="material-icons align-middle text-sm mr-1">arrow_back</span> Batal
                            </a>
                            <button type="submit"
                                    class="btn-primary text-white px-5 py-2.5 rounded-lg font-medium flex items-center justify-center">
                                <span class="material-icons align-middle text-sm mr-1">save</span> Simpan Perubahan
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
                                        <li>Data yang diubah akan langsung tersimpan setelah klik "Simpan Perubahan"</li>
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
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Check if preview image already exists
                    let previewImg = document.getElementById('preview-image');
                    const placeholder = document.getElementById('preview-placeholder');

                    if (previewImg) {
                        // Update existing image
                        previewImg.src = e.target.result;
                    } else {
                        // Create new image element and replace placeholder
                        const container = placeholder.parentElement;
                        previewImg = document.createElement('img');
                        previewImg.src = e.target.result;
                        previewImg.className = 'w-full h-full object-cover profile-img';
                        previewImg.id = 'preview-image';
                        previewImg.alt = 'Preview Foto';

                        // Replace placeholder with image
                        container.replaceChild(previewImg, placeholder);
                    }
                }
                reader.readAsDataURL(file);
            }
        }

        // Date validation to ensure akhir_magang > mulai_magang
        document.addEventListener('DOMContentLoaded', function() {
            const mulaiMagangInput = document.querySelector('input[name="mulai_magang"]');
            const akhirMagangInput = document.querySelector('input[name="akhir_magang"]');

            if (mulaiMagangInput && akhirMagangInput) {
                // Set initial min value for akhir_magang
                const updateMinDate = () => {
                    const startDate = new Date(mulaiMagangInput.value);
                    startDate.setDate(startDate.getDate() + 1);
                    akhirMagangInput.min = startDate.toISOString().split('T')[0];
                };

                // Update min date when start date changes
                mulaiMagangInput.addEventListener('change', function() {
                    updateMinDate();

                    const startDate = new Date(this.value);
                    const endDate = new Date(akhirMagangInput.value);

                    if (endDate <= startDate) {
                        // Set akhir_magang to one day after mulai_magang
                        startDate.setDate(startDate.getDate() + 1);
                        akhirMagangInput.value = startDate.toISOString().split('T')[0];
                    }
                });

                // Set initial min date
                if (mulaiMagangInput.value) {
                    updateMinDate();
                }
            }
        });

        // Form validation before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const mulaiMagang = new Date(document.querySelector('input[name="mulai_magang"]').value);
            const akhirMagang = new Date(document.querySelector('input[name="akhir_magang"]').value);

            if (akhirMagang <= mulaiMagang) {
                e.preventDefault();
                alert('Tanggal selesai magang harus setelah tanggal mulai magang!');
                return false;
            }
        });
    </script>
</body>
</html>
