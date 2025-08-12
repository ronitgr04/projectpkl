<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
{{-- 60 --}} 

{{-- 136 --}} 

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>Kegiatan Harian</title>

    @vite('resources/css/app.css')
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <style>
        /* Modal Animation */
        .modal-backdrop {
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }

        .modal-content {
            transform: scale(0.7) translateY(-100px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal-backdrop.show .modal-content {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .modal-backdrop.hide .modal-content {
            transform: scale(0.7) translateY(100px);
            opacity: 0;
        }
    </style>
</head>

<body class="">
    <div className="">

        @extends('layouts.contentMahasiswa')

        @section('content')
        <div class="space-y-6 m-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Kegiatan PKL</h2>
                        <p class="text-gray-600 mt-1">Kelola dan dokumentasikan kegiatan PKL Anda</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('mahasiswa.kegiatan.export.form') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                            <span class="material-icons text-sm">picture_as_pdf</span>
                            <span>Cetak PDF</span>
                        </a>
                        <a href="{{ route('mahasiswa.kegiatan.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                            <span class="material-icons text-sm">add</span>
                            <span>Tambah Kegiatan</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-center">
                    <span class="material-icons text-green-600 mr-2">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-center">
                    <span class="material-icons text-red-600 mr-2">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <!-- Kegiatan List -->
            <div class="bg-white rounded-lg shadow">
                @if($kegiatan->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($kegiatan as $item)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4 mb-3">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span class="material-icons text-sm mr-1">calendar_today</span>
                                        <span>{{ $item->formatted_tanggal }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span class="material-icons text-sm mr-1">schedule</span>
                                        <span>{{ $item->jam }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span class="material-icons text-sm mr-1">today</span>
                                        <span>{{ $item->formatted_hari }}</span>
                                    </div>
                                </div>

                                <h3 class="text-lg font-medium text-gray-900 mb-2">
                                    {{ Str::limit($item->kegiatan, 100) }}
                                </h3>

                                @if($item->foto_dokumentasi)
                                <div class="flex items-center text-sm text-green-600 mb-2">
                                    <span class="material-icons text-sm mr-1">photo_camera</span>
                                    <span>Memiliki foto dokumentasi</span>
                                </div>
                                @endif

                                <p class="text-gray-600 text-sm">
                                    {{ Str::limit($item->kegiatan, 200) }}
                                </p>
                            </div>

                            <div class="flex items-center space-x-2 ml-4">
                                <a href="{{ route('mahasiswa.kegiatan.show', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                    title="Lihat Detail">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                <a href="{{ route('mahasiswa.kegiatan.edit', $item->id) }}"
                                    class="text-yellow-600 hover:text-yellow-800 p-2 rounded-lg hover:bg-yellow-50 transition-colors"
                                    title="Edit">
                                    <span class="material-icons text-sm">edit</span>
                                </a>
                                <button type="button"
                                    onclick="showDeleteModal('{{ $item->id }}', '{{ Str::limit($item->kegiatan, 50) }}')"
                                    class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                    title="Hapus">
                                    <span class="material-icons text-sm">delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $kegiatan->links() }}
                </div>
                @else
                <div class="p-12 text-center">
                    <div class="mb-4">
                        <span class="material-icons text-6xl text-gray-300">assignment</span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kegiatan</h3>
                    <p class="text-gray-500 mb-6">Mulai dokumentasikan kegiatan PKL Anda</p>
                    <a href="{{ route('mahasiswa.kegiatan.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 transition-colors">
                        <span class="material-icons text-sm">add</span>
                        <span>Tambah Kegiatan Pertama</span>
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
                <!-- Modal Header -->
                <div class="flex items-center justify-center pt-8 pb-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <span class="material-icons text-red-600 text-3xl">delete_forever</span>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-8 pb-6 text-center">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                        Hapus Kegiatan?
                    </h3>
                    <p class="text-gray-600 mb-2">
                        Anda akan menghapus kegiatan:
                    </p>
                    <p class="text-gray-800 font-medium mb-4" id="kegiatanTitle">
                        <!-- Kegiatan title will be inserted here -->
                    </p>
                    <p class="text-sm text-gray-500">
                        Tindakan ini tidak dapat dibatalkan. Data kegiatan akan dihapus secara permanen.
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 px-8 py-6 bg-gray-50 rounded-b-2xl">
                    <button type="button"
                        onclick="hideDeleteModal()"
                        class="px-6 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors font-medium">
                        Batal
                    </button>
                    <button type="button"
                        onclick="confirmDelete()"
                        class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors font-medium">
                        <span class="flex items-center space-x-2">
                            <span class="material-icons text-sm">delete</span>
                            <span>Ya, Hapus</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Hidden Delete Forms (will be created dynamically) -->
        <div id="deleteForms"></div>

        @endsection

    </div>

    <script>
        let currentDeleteId = null;

        function showDeleteModal(id, title) {
            currentDeleteId = id;
            document.getElementById('kegiatanTitle').textContent = title;

            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Trigger animation
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);

            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hide');
            modal.classList.remove('show');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'hide');
                currentDeleteId = null;

                // Restore body scroll
                document.body.style.overflow = 'auto';
            }, 300);
        }

        function confirmDelete() {
            if (!currentDeleteId) return;

            // Create and submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('mahasiswa/kegiatan') }}/${currentDeleteId}`;

            const csrfField = document.createElement('input');
            csrfField.type = 'hidden';
            csrfField.name = '_token';
            csrfField.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfField);
            form.appendChild(methodField);
            document.body.appendChild(form);

            form.submit();
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
                hideDeleteModal();
            }
        });
    </script>
</body>

</html>
