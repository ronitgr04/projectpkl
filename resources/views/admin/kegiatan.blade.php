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
                    Kegiatan Selama PKL
            </p>
        </div>
        @endsection --}}
     @extends('layouts.content')

@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-2xl font-semibold mb-4">Data Kegiatan</h2>

    <!-- Filter -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
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
                <span class="material-icons text-sm mr-1">search</span> Cari
            </button> --}}
            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
                <span class="material-icons text-sm mr-1">search</span> Cari
            </a>

        </div>
    </div>

    <!-- Tombol Tambah -->
    <div class="mb-4">
        {{-- <button class="bg-lime-500 hover:bg-lime-600 text-white px-4 py-2 rounded inline-flex items-center">
            <span class="material-icons text-sm mr-2">add</span> Tambah
        </button> --}}
        <a href="#" class="bg-lime-500 hover:bg-lime-600 text-white px-4 py-2 rounded inline-flex items-center">
            <span class="material-icons text-sm mr-2">add</span> Tambah
        </a>
    </div>

    <!-- Tabel Kegiatan -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">No</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">Hari</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">Jam</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">Kegiatan</th>
                    <th class="px-4 py-3 text-center text-gray-500 font-medium uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3 text-center">
                        {{-- <div class="flex justify-center gap-1">
                            <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded text-xs inline-flex items-center">
                                <span class="material-icons text-sm">edit</span>
                            </button>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs inline-flex items-center">
                                <span class="material-icons text-sm">delete</span>
                            </button> 
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs inline-flex items-center">
                                <span class="material-icons text-sm">visibility</span>
                            </button>
                        </div> --}}
                    </td>
                </tr>
                <!-- Duplikasikan baris ini sesuai kebutuhan -->
            </tbody>
        </table>
    </div>
</div>
@endsection




    </div>
</body>

</html>
