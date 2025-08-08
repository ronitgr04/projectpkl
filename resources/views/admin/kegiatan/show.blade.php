<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head> 
    <meta charset="utf-8">{{-- 26 --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Detail Kegiatan - Admin</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>
<body class="">
    @extends('layouts.content')
    @section('content')
    <div class="bg-white rounded-lg m-6 shadow"> 
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Detail Kegiatan</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap kegiatan mahasiswa</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.kegiatan.edit', $kegiatan->id) }}"
                     {{-- <a href="#" --}}
                       class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <span class="material-icons text-sm mr-2">edit</span>
                        Edit
                    </a>
                    <a href="{{ route('admin.kegiatan.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <span class="material-icons text-sm mr-2">arrow_back</span>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Kegiatan Card -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-blue-600 mr-2">event_note</span>
                            Deskripsi Kegiatan
                        </h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $kegiatan->kegiatan }}</p>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-green-600 mr-2">history</span>
                            Riwayat
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dibuat pada</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $kegiatan->created_at ? $kegiatan->created_at->format('d M Y, H:i') : '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Terakhir diupdate</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $kegiatan->updated_at ? $kegiatan->updated_at->format('d M Y, H:i') : '-' }}
                                </dd>
                            </div>
                        </div>
                    </div>

                    <!-- Foto Dokumentasi -->
                    <div class="bg-purple-50 w-1/2 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-purple-600 mr-2">photo_camera</span>
                            Foto Dokumentasi
                        </h3>
                        @if($kegiatan->foto_dokumentasi)
                            <div class="space-y-4">
                                <!-- Foto utama -->
                                <div class="relative group">
                                    <img src="{{ $kegiatan->foto_url }}"
                                         alt="Dokumentasi {{ $kegiatan->kegiatan }}"
                                         class="w-full h-64 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 cursor-pointer"
                                         onclick="openImageModal('{{ $kegiatan->foto_url }}', 'Dokumentasi {{ $kegiatan->kegiatan }}')">

                                    <!-- Overlay hover effect -->
                                    <!-- <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                        <span class="material-icons text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-3xl">
                                            zoom_in
                                        </span>
                                    </div> -->
                                </div>

                                <!-- Info foto -->
                                <div class="flex items-center justify-between text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <span class="material-icons text-sm mr-1">info</span>
                                        Klik foto untuk memperbesar
                                    </span>
                                    <a href="{{ $kegiatan->foto_url }}"
                                       download="dokumentasi_{{ $kegiatan->id }}.jpg"
                                       class="flex items-center hover:text-purple-600 transition-colors">
                                        <span class="material-icons text-sm mr-1">download</span>
                                        Download
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-center p-8 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300">
                                <span class="material-icons text-gray-400 text-6xl mb-4">image_not_supported</span>
                                <p class="text-gray-500 text-sm">Tidak ada foto dokumentasi untuk kegiatan ini</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar Information -->
                <div class="space-y-6">
                    <!-- Mahasiswa Info -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-blue-600 mr-2">person</span>
                            Informasi Mahasiswa
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $kegiatan->mahasiswa->nama ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">NIM</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $kegiatan->mahasiswa->nim ?? '-' }}</dd>
                            </div>
                            @if($kegiatan->mahasiswa && $kegiatan->mahasiswa->email)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $kegiatan->mahasiswa->email }}</dd>
                            </div>
                            @endif
                            @if($kegiatan->mahasiswa && $kegiatan->mahasiswa->telepon)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $kegiatan->mahasiswa->telepon }}</dd>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Waktu Info -->
                    <div class="bg-yellow-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-yellow-600 mr-2">schedule</span>
                            Waktu Kegiatan
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Hari</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $kegiatan->formatted_hari }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $kegiatan->formatted_tanggal }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jam</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $kegiatan->jam }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-red-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-red-600 mr-2">warning</span>
                            Aksi Berbahaya
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Hapus kegiatan ini secara permanen. Tindakan ini tidak dapat dibatalkan.
                        </p>
                        <button onclick="confirmDelete()"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <span class="material-icons text-sm mr-2">delete</span>
                            Hapus Kegiatan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md mx-4">
            <div class="flex items-center mb-4">
                <span class="material-icons text-red-600 mr-2">warning</span>
                <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus kegiatan ini?
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                    Batal
                </button>
                <form action="{{ route('admin.kegiatan.destroy', $kegiatan->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50">
        <div class="relative max-w-4xl max-h-screen m-4">
            <!-- Close button -->
            <button onclick="closeImageModal()"
                    class="absolute top-4 right-4 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-2">
                <span class="material-icons">close</span>
            </button>

            <!-- Image -->
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">

            <!-- Caption -->
            <div class="absolute bottom-4 left-4 right-4 text-white text-center bg-black bg-opacity-50 rounded-lg p-2">
                <p id="modalCaption" class="text-sm"></p>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        function openImageModal(imageSrc, caption) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalCaption = document.getElementById('modalCaption');

            modalImage.src = imageSrc;
            modalImage.alt = caption;
            modalCaption.textContent = caption;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        // Close modals when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close image modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
    @endsection
</body>
</html>
