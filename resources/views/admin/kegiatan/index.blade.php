<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head> {{-- 97 --}}
    {{-- exportData() --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Data Kegiatan - Admin</title>
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
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Data Kegiatan</h2>
                    <p class="text-sm text-gray-600 mt-1">Kelola data kegiatan mahasiswa PKL</p>
                </div>
                <a href="{{ route('admin.kegiatan.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <span class="material-icons text-sm mr-2">add</span>
                    Tambah Kegiatan
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('admin.kegiatan.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari kegiatan atau nama mahasiswa..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Mahasiswa Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mahasiswa</label>
                        <select name="mahasiswa_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Mahasiswa</option>
                            @foreach($mahasiswa as $mhs)
                                <option value="{{ $mhs->id }}" {{ request('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                    {{ $mhs->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date"
                               name="tanggal"
                               value="{{ request('tanggal') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Month Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                            <span class="material-icons text-sm mr-2">search</span>
                            Filter
                        </button>
                        <a href="{{ route('admin.kegiatan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg">
                            <span class="material-icons text-sm mr-2">refresh</span>
                            Reset
                        </a>
                    </div>
                    <button type="button" onclick="#" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                        <span class="material-icons text-sm mr-2">download</span>
                        Export
                    </button>
                  
                </div>
            </form>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="m-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex">
                    <span class="material-icons text-green-600 mr-2">check_circle</span>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="m-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <span class="material-icons text-red-600 mr-2">error</span>
                    <span class="text-red-800">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kegiatan as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $kegiatan->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $item->mahasiswa->nama ?? '-' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $item->mahasiswa->nim ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->formatted_hari }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->formatted_tanggal }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->jam }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs truncate" title="{{ $item->kegiatan }}">
                                    {{ Str::limit($item->kegiatan, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center space-x-2">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.kegiatan.show', $item->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                                       title="Lihat Detail">
                                        <span class="material-icons text-sm">visibility</span>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.kegiatan.edit', $item->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors"
                                       title="Edit">
                                        <span class="material-icons text-sm">edit</span>
                                    </a>

                                    <!-- Delete Button -->
                                    <button onclick="confirmDelete({{ $item->id }} , '{{ $item->mahasiswa->nama}}')"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                                            title="Hapus">
                                        <span class="material-icons text-sm">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-4xl text-gray-300 mb-2">event_note</span>
                                    <p class="text-lg font-medium">Tidak ada data kegiatan</p>
                                    <p class="text-sm">Belum ada kegiatan yang tercatat</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($kegiatan->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $kegiatan->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 backdrop-blur bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white border-1 rounded-lg p-6 max-w-md mx-4">
            <div class="flex items-center mb-4">
                <span class="material-icons text-red-600 mr-2">warning</span>
                <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus kegiatan untuk <span id="deleteName" class="font-medium"></span>?
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, nama) {
            document.getElementById('deleteName').textContent = nama;
            document.getElementById('deleteForm').action = `/admin/kegiatan/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        function exportData() {
            const form = document.querySelector('form');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            window.open(`{{ route('admin.kegiatan.export') }}?${params.toString()}`, '_blank');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
    @endsection
</body>
</html>
