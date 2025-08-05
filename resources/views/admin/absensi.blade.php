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

        {{-- @extends('layouts.content')

        @section('content')
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-semibold mb-2">Selamat Datang, {{ auth()->user()->username }}.</h2>
            <p class="text-gray-600">
                    Absensi Selama PKL
            </p>
        </div>
        @endsection --}}
        @extends('layouts.content')

@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-2xl font-semibold mb-4">Data Absensi</h2>

    <!-- Form Filter -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mahasiswa:</label>
            <input type="text" class="w-full border-gray-300 rounded-md px-3 py-2" placeholder="Cari Mahasiswa">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Awal:</label>
            <input type="date" class="w-full border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir:</label>
            <input type="date" class="w-full border-gray-300 rounded-md px-3 py-2">
        </div>
        <div class="flex items-end">
            {{-- <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
                <span class="material-icons mr-1 text-sm">search</span> Cari
            </button> --}}
            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
                <span class="material-icons mr-1 text-sm">search</span> Cari
            </a>
        </div>
    </div>

    <!-- Tombol Tambah -->
    <div class="mb-3">
        {{-- <button class="bg-lime-500 hover:bg-lime-600 text-white px-4 py-2 rounded inline-flex items-center">
            <span class="material-icons mr-1 text-sm">add</span> Absensi
        </button> --}}
        <a href="#" class="bg-lime-500 hover:bg-lime-600 text-white px-4 py-2 rounded inline-flex items-center">
    <span class="material-icons mr-1 text-sm">add</span> Absensi
</a>
    </div>
      <!-- Tombol Cetak di sebelah kanan -->
    {{-- <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
        <span class="material-icons text-sm mr-2">print</span> Cetak
    </button> --}}
    <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
    <span class="material-icons text-sm mr-2">print</span> Cetak
</a>
</div>
    {{-- <div class="flex justify-center gap-2">
                            <button class="bg-green-400 hover:bg-green-500 text-white px-3 py-1 rounded text-xs inline-flex items-center shadow-sm">
                                <span class="material-icons text-sm mr-1">schedule</span> Absensi
                            </button>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs inline-flex items-center">
                                <span class="material-icons text-sm mr-1">print</span> Cetak
                            </button>
                        </div> --}}

    <!-- Tabel Data Absensi -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Universitas</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Waktu</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Hari</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3 text-yellow-600 font-medium"></td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3 text-center">
                        
                    </td>
                </tr>
                <!-- Tambahkan baris dummy lain di sini jika perlu -->
            </tbody>
        </table>
    </div>
</div>
@endsection

    </div>
</body>

</html>
