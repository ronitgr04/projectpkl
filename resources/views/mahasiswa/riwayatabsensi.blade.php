<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Riwayat Absensi </title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>

<body>
    <div>
        @extends('layouts.contentMahasiswa')
        @section('content')
            <div class="space-y-6 m-6">
                <!-- Header -->


                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="material-icons text-green-400">check_circle</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">Total Hadir</p>
                                <p class="text-2xl font-semibold text-green-900">{{ $totalHadir }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="material-icons text-yellow-400">schedule</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-800">Total Izin</p>
                                <p class="text-2xl font-semibold text-yellow-900">{{ $totalIzin }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="material-icons text-blue-400">local_hospital</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-800">Total Sakit</p>
                                <p class="text-2xl font-semibold text-blue-900">{{ $totalSakit }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="material-icons text-red-400">cancel</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">Total Alpha</p>
                                <p class="text-2xl font-semibold text-red-900">{{ $totalAlpha }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Filter Riwayat</h3>
                    <form method="GET" action="{{ route('mahasiswa.absensi.riwayat') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Tahun</option>
                                @for ($year = date('Y'); $year >= date('Y') - 3; $year--)
                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200 flex items-center">
                                <span class="material-icons mr-2">search</span>
                                Filter
                            </button>
                            <a href="{{ route('mahasiswa.absensi.riwayat') }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200 flex items-center">
                                <span class="material-icons mr-2">refresh</span>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Attendance History Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Data Riwayat Absensi</h3>
                    </div>

                    @if ($riwayatAbsensi->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Hari</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Waktu</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Level</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($riwayatAbsensi as $index => $absensi)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $riwayatAbsensi->firstItem() + $index }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $absensi->hari }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ Carbon\Carbon::parse($absensi->waktu)->format('H:i') }} WIB
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($absensi->status)
                                                    @case('Hadir')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <span class="material-icons text-xs mr-1">check_circle</span>
                                                            Hadir
                                                        </span>
                                                    @break

                                                    @case('Izin')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <span class="material-icons text-xs mr-1">schedule</span>
                                                            Izin
                                                        </span>
                                                    @break

                                                    @case('Sakit')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <span class="material-icons text-xs mr-1">local_hospital</span>
                                                            Sakit
                                                        </span>
                                                    @break

                                                    @case('Alpha')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <span class="material-icons text-xs mr-1">cancel</span>
                                                            Alpha
                                                        </span>
                                                    @break

                                                    @default
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            {{ $absensi->status }}
                                                        </span>
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $absensi->level }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                @if ($absensi->keterangan)
                                                    <div class="max-w-xs">
                                                        <p class="truncate" title="{{ $absensi->keterangan }}">
                                                            {{ $absensi->keterangan }}
                                                        </p>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 italic">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-3 border-t border-gray-200">
                            {{ $riwayatAbsensi->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center">
                                <span class="material-icons text-6xl text-gray-300 mb-4">assignment</span>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data absensi</h3>
                                <p class="text-gray-500">
                                    @if (request('bulan') || request('tahun'))
                                        Tidak ada data absensi untuk filter yang dipilih.
                                    @else
                                        Anda belum memiliki riwayat absensi.
                                    @endif
                                </p>
                                @if (request('bulan') || request('tahun'))
                                    <a href="{{ route('mahasiswa.absensi.riwayat') }}"
                                        class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                                        Lihat Semua Data
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('mahasiswa.absensi') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition duration-200 flex items-center">
                            <span class="material-icons mr-2">add_circle</span>
                            Absensi Hari Ini
                        </a>
                        <a href="{{ route('mahasiswa.dashboard') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-200 flex items-center">
                            <span class="material-icons mr-2">dashboard</span>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50"
                    id="success-message">
                    <div class="flex items-center">
                        <span class="material-icons mr-2">check_circle</span>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50"
                    id="error-message">
                    <div class="flex items-center">
                        <span class="material-icons mr-2">error</span>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <script>
                // Auto hide messages after 5 seconds
                setTimeout(function() {
                    const successMsg = document.getElementById('success-message');
                    const errorMsg = document.getElementById('error-message');
                    if (successMsg) successMsg.style.display = 'none';
                    if (errorMsg) errorMsg.style.display = 'none';
                }, 5000);
            </script>
        @endsection
    </div>
</body>

</html>