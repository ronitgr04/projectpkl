<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>Profil</title>

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
            {{-- Header --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Profil Mahasiswa</h1>
                        <p class="text-gray-600 mt-1">Kelola informasi profil Anda</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        {{-- Progress Bar --}}
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Kelengkapan:</span>
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $profil->getCompletionPercentage() }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ $profil->getCompletionPercentage() }}%</span>
                        </div>
                        <a href="{{ route('mahasiswa.profil.edit') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
                            <i class="material-icons text-sm mr-1">edit</i>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Profile Card --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="text-center">
                            <div class="mx-auto h-32 w-32 rounded-full bg-gray-300 flex items-center justify-center mb-4">
                                @if($mahasiswa->foto)
                                <img src="{{ $mahasiswa->foto_url }}" alt="Foto Profil" class="h-32 w-32 rounded-full object-cover">
                                @else
                                <i class="material-icons text-6xl text-gray-500">person</i>
                                @endif
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $mahasiswa->nama }}</h3>
                            <p class="text-gray-600">{{ $mahasiswa->nim }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $mahasiswa->universitas }}</p>

                            {{-- Status Badge --}}
                            <div class="mt-3">
                                @php
                                $status = $mahasiswa->status_magang;
                                $statusColor = $status === 'sedang_magang' ? 'bg-green-100 text-green-800' :
                                ($status === 'belum_mulai' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800');
                                $statusText = $status === 'sedang_magang' ? 'Sedang Magang' :
                                ($status === 'belum_mulai' ? 'Belum Mulai' : 'Selesai');
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ $statusText }}
                                </span>
                            </div>

                            {{-- Social Links --}}
                            @if($profil->instagram || $profil->linkedin || $profil->github)
                            <div class="flex justify-center space-x-3 mt-4">
                                @if($profil->instagram)
                                <a href="https://instagram.com/{{ $profil->instagram }}" target="_blank"
                                    class="text-pink-600 hover:text-pink-700">
                                    <i class="material-icons">camera_alt</i>
                                </a>
                                @endif
                                @if($profil->linkedin)
                                <a href="https://linkedin.com/in/{{ $profil->linkedin }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-700">
                                    <i class="material-icons">work</i>
                                </a>
                                @endif
                                @if($profil->github)
                                <a href="https://github.com/{{ $profil->github }}" target="_blank"
                                    class="text-gray-800 hover:text-gray-900">
                                    <i class="material-icons">code</i>
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Personal Information --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="material-icons mr-2">person</i>
                            Informasi Pribadi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nomor Telepon</label>
                                <p class="text-gray-900">{{ $profil->no_telp ?: 'Belum diisi' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Lahir</label>
                                <p class="text-gray-900">{{ $profil->tanggal_lahir_formatted ?: 'Belum diisi' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Jenis Kelamin</label>
                                <p class="text-gray-900">{{ $profil->jenis_kelamin_lengkap ?: 'Belum diisi' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Jurusan</label>
                                <p class="text-gray-900">{{ $profil->jurusan ?: 'Belum diisi' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-500">Alamat</label>
                                <p class="text-gray-900">{{ $profil->alamat ?: 'Belum diisi' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Academic Information --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="material-icons mr-2">school</i>
                            Informasi Akademik
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Periode Magang</label>
                                <p class="text-gray-900">
                                    {{ $mahasiswa->mulai_magang_formatted }} - {{ $mahasiswa->akhir_magang_formatted }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Durasi</label>
                                <p class="text-gray-900">{{ $mahasiswa->durasi_magang }} hari</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Pembimbing</label>
                                <p class="text-gray-900">{{ $profil->nama_pembimbing ?: 'Belum diisi' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Kontak Pembimbing</label>
                                <p class="text-gray-900">{{ $profil->no_telp_pembimbing ?: 'Belum diisi' }}</p>
                                @if($profil->email_pembimbing)
                                <p class="text-gray-600 text-sm">{{ $profil->email_pembimbing }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- About & Skills --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="material-icons mr-2">info</i>
                            Tentang Saya
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Deskripsi Diri</label>
                                <p class="text-gray-900 mt-1">
                                    {{ $profil->deskripsi_diri ?: 'Belum ada deskripsi diri.' }}
                                </p>
                            </div>
                            @if($profil->skills)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Keahlian</label>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($profil->skills as $skill)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $skill }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection



    </div>
</body>

</html>