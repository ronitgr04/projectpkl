<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
{{-- 40 --}} 

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
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('mahasiswa.kegiatan.index') }}"
                            class="text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="material-icons">arrow_back</span>
                        </a>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">Detail Kegiatan PKL</h2>
                            <p class="text-gray-600 mt-1">{{ $kegiatan->formatted_tanggal }} - {{ $kegiatan->formatted_hari }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('mahasiswa.kegiatan.edit', $kegiatan->id) }}"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                            <span class="material-icons text-sm">edit</span>
                            <span>Edit</span>
                        </a>
                        <form action="{{ route('mahasiswa.kegiatan.destroy', $kegiatan->id) }}"
                            method="POST"
                            class="inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                                <span class="material-icons text-sm">delete</span>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Informasi Waktu -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-3 flex items-center">
                                <span class="material-icons text-blue-600 mr-2">schedule</span>
                                Informasi Waktu
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="flex items-center space-x-2">
                                    <span class="material-icons text-gray-500 text-sm">calendar_today</span>
                                    <div>
                                        <p class="text-gray-500">Tanggal</p>
                                        <p class="font-medium">{{ $kegiatan->formatted_tanggal }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="material-icons text-gray-500 text-sm">today</span>
                                    <div>
                                        <p class="text-gray-500">Hari</p>
                                        <p class="font-medium">{{ $kegiatan->formatted_hari }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="material-icons text-gray-500 text-sm">access_time</span>
                                    <div>
                                        <p class="text-gray-500">Jam</p>
                                        <p class="font-medium">{{ $kegiatan->jam }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi Kegiatan -->
                        <div>
                            <h3 class="font-medium text-gray-900 mb-3 flex items-center">
                                <span class="material-icons text-blue-600 mr-2">assignment</span>
                                Deskripsi Kegiatan
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $kegiatan->kegiatan }}</p>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="font-medium text-blue-900 mb-2 flex items-center">
                                <span class="material-icons text-blue-600 mr-2">info</span>
                                Informasi Tambahan
                            </h3>
                            <div class="text-sm text-blue-700 space-y-1">
                                <p><strong>Dibuat pada:</strong> {{ $kegiatan->created_at->format('d M Y H:i') }}</p>
                                @if($kegiatan->updated_at != $kegiatan->created_at)
                                <p><strong>Terakhir diupdate:</strong> {{ $kegiatan->updated_at->format('d M Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar - Foto Dokumentasi -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-6">
                            <h3 class="font-medium text-gray-900 mb-4 flex items-center">
                                <span class="material-icons text-blue-600 mr-2">photo_camera</span>
                                Foto Dokumentasi
                            </h3>

                            @if($kegiatan->foto_dokumentasi)
                            <div class="space-y-4">
                                <div class="relative group">
                                    <?php
                                    // dd($kegiatan->foto_dokumentasi);
                                    ?>
                                    <img src="{{ Storage::url($kegiatan->foto_dokumentasi) }}"
                                        alt="Dokumentasi Kegiatan"

                                        onclick="openImageModal('{{ Storage::url($kegiatan->foto_dokumentasi) }}')">
                                    <!-- <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all flex items-center justify-center"> -->
                                        <!-- <span class="material-icons text-white opacity-0 group-hover:opacity-100 text-2xl">zoom_in</span> -->
                                    </div>
                                </div>
                                <div class="text-center mt-4">
                                    <a href="{{ Storage::url($kegiatan->foto_dokumentasi) }}"
                                        download="dokumentasi_{{ $kegiatan->formatted_tanggal }}.jpg"
                                        class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800 text-sm">
                                        <span class="material-icons text-sm">download</span>
                                        <span>Download Foto</span>
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-8 px-4 border-2 border-dashed border-gray-300 rounded-lg">
                                <span class="material-icons text-4xl text-gray-300 mb-2">image</span>
                                <p class="text-gray-500 text-sm">Tidak ada foto dokumentasi</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk zoom gambar -->
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
            <div class="relative max-w-4xl max-h-full">
                <button onclick="closeImageModal()"
                    class="absolute -top-4 -right-4 bg-white text-gray-800 rounded-full p-2 hover:bg-gray-100 transition-colors z-10">
                    <span class="material-icons">close</span>
                </button>
                <img id="modalImage" src="" alt="Dokumentasi Kegiatan" class="max-w-full max-h-full rounded-lg">
            </div>
        </div>

        <script>
            function openImageModal(imageSrc) {
                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('imageModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeImageModal() {
                document.getElementById('imageModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Close modal when clicking outside the image
            document.getElementById('imageModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeImageModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImageModal();
                }
            });
        </script>
        @endsection
    </div>
</body>

</html>
