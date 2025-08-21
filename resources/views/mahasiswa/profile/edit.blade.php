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
        <div class="space-y-6 m-6">
            {{-- Header --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Profil</h1>
                        <p class="text-gray-600 mt-1">Perbarui informasi profil Anda</p>
                    </div>
                    <a href="{{ route('mahasiswa.profil.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="material-icons text-sm mr-1">arrow_back</i>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('mahasiswa.profil.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Personal Information --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="material-icons mr-2">person</i>
                        Informasi Pribadi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="tel" id="no_telp" name="no_telp"
                                value="{{ old('no_telp', $profil->no_telp) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('no_telp') border-red-500 @enderror">
                            @error('no_telp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $profil->tanggal_lahir?->format('Y-m-d')) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('tanggal_lahir') border-red-500 @enderror">
                            @error('tanggal_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('jenis_kelamin') border-red-500 @enderror">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $profil->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $profil->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                            <input type="text" id="jurusan" name="jurusan"
                                value="{{ old('jurusan', $profil->jurusan) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('jurusan') border-red-500 @enderror">
                            @error('jurusan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('alamat') border-red-500 @enderror">{{ old('alamat', $profil->alamat) }}</textarea>
                            @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
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
                            <label for="nama_pembimbing" class="block text-sm font-medium text-gray-700 mb-1">Nama Pembimbing</label>
                            <input type="text" id="nama_pembimbing" name="nama_pembimbing"
                                value="{{ old('nama_pembimbing', $profil->nama_pembimbing) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('nama_pembimbing') border-red-500 @enderror">
                            @error('nama_pembimbing')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="no_telp_pembimbing" class="block text-sm font-medium text-gray-700 mb-1">No. Telp Pembimbing</label>
                            <input type="tel" id="no_telp_pembimbing" name="no_telp_pembimbing"
                                value="{{ old('no_telp_pembimbing', $profil->no_telp_pembimbing) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('no_telp_pembimbing') border-red-500 @enderror">
                            @error('no_telp_pembimbing')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="email_pembimbing" class="block text-sm font-medium text-gray-700 mb-1">Email Pembimbing</label>
                            <input type="email" id="email_pembimbing" name="email_pembimbing"
                                value="{{ old('email_pembimbing', $profil->email_pembimbing) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('email_pembimbing') border-red-500 @enderror">
                            @error('email_pembimbing')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
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
                            <label for="deskripsi_diri" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Diri</label>
                            <textarea id="deskripsi_diri" name="deskripsi_diri" rows="4"
                                placeholder="Ceritakan tentang diri Anda..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('deskripsi_diri') border-red-500 @enderror">{{ old('deskripsi_diri', $profil->deskripsi_diri) }}</textarea>
                            @error('deskripsi_diri')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Keahlian</label>
                            <input type="text" id="skills" name="skills"
                                value="{{ old('skills', $profil->skills_string) }}"
                                placeholder="Contoh: PHP, Laravel, JavaScript, MySQL (pisahkan dengan koma)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('skills') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Pisahkan setiap keahlian dengan koma</p>
                            @error('skills')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Social Media --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="material-icons mr-2">link</i>
                        Media Sosial
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    @
                                </span>
                                <input type="text" id="instagram" name="instagram"
                                    value="{{ old('instagram', $profil->instagram) }}"
                                    placeholder="username"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-blue-500 focus:border-blue-500 @error('instagram') border-red-500 @enderror">
                            </div>
                            @error('instagram')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    in/
                                </span>
                                <input type="text" id="linkedin" name="linkedin"
                                    value="{{ old('linkedin', $profil->linkedin) }}"
                                    placeholder="username"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-blue-500 focus:border-blue-500 @error('linkedin') border-red-500 @enderror">
                            </div>
                            @error('linkedin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="github" class="block text-sm font-medium text-gray-700 mb-1">GitHub</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    github.com/
                                </span>
                                <input type="text" id="github" name="github"
                                    value="{{ old('github', $profil->github) }}"
                                    placeholder="username"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-blue-500 focus:border-blue-500 @error('github') border-red-500 @enderror">
                            </div>
                            @error('github')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('mahasiswa.profil.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center">
                            <i class="material-icons text-sm mr-1">save</i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endsection

    </div>
</body>

</html>