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
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-semibold mb-2">Selamat Datang, {{ auth()->user()->username }}.</h2>
            <p class="text-gray-600">
                Profil Mahasiswa
            </p>
            <div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-semibold mb-4">Profil</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div><strong>Nama</strong>: {{ $mahasiswa->nama }}</div>
        <div><strong>Nomor Induk Mahasiswa</strong>: {{ $mahasiswa->nim }}</div>
        <div><strong>Universitas</strong>: {{ $mahasiswa->universitas }}</div>
        <!-- <div><strong>Jurusan</strong>: {{ $mahasiswa->jurusan }}</div> -->
        <div><strong>Tanggal Masuk</strong>: {{ \Carbon\Carbon::parse($mahasiswa->tanggal_masuk)->format('d/m/Y') }}</div>
        <div><strong>Tanggal Selesai</strong>: {{ \Carbon\Carbon::parse($mahasiswa->tanggal_selesai)->format('d/m/Y') }}</div>
        <!-- <div><strong>No Telp</strong>: {{ $mahasiswa->no_telp }}</div>
        <div><strong>Alamat</strong>: {{ $mahasiswa->alamat }}</div> -->
        <div class="sm:col-span-2">
            <strong>Foto</strong>:
            @if($mahasiswa->foto)
                <img src="{{ asset('storage/foto/' . $mahasiswa->foto) }}" alt="Foto Profil" class="mt-2 w-24 h-24 object-cover rounded-full border">
            @else
                <p class="text-gray-500 mt-2">Belum ada foto</p>
            @endif
           
        </div>   
        <!-- Aksi --> 
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M17.414 2.586a2 2 0 010 2.828L8.414 14.414a1 1 0 01-.707.293H5a1 1 0 01-1-1v-2.707a1 1 0 01.293-.707l9-9a2 2 0 012.828 0z" />
                </svg>
                Edit Profil
</a>
    </div>
</div>

        </div>
        

        @endsection
        
    </div>
    
    

</body>


</html>
