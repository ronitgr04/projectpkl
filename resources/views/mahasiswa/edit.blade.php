@extends('layouts.contentMahasiswa')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-semibold mb-4">Edit Profil</h2>

    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf




        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label>Nama</label>
                <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" 
                       class="w-full border rounded p-2" required>
            </div>

            <div>
                <label>NIM</label>
                <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" 
                       class="w-full border rounded p-2" required>
            </div>

            <div>
                <label>Universitas</label>
                <input type="text" name="universitas" value="{{ old('universitas', $mahasiswa->universitas) }}" 
                       class="w-full border rounded p-2" required>
            </div>

            <div>
                <label>Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $mahasiswa->tanggal_masuk) }}" 
                       class="w-full border rounded p-2" required>
            </div>

            <div>
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $mahasiswa->tanggal_selesai) }}" 
                       class="w-full border rounded p-2" required>
            </div>

            <div class="sm:col-span-2">
                <label>Foto</label>
                <input type="file" name="foto" class="w-full border rounded p-2">
                @if($mahasiswa->foto)
                    <img src="{{ asset('storage/mahasiswa_photos/' . $mahasiswa->foto) }}" class="mt-2 w-24 h-24 rounded-full object-cover">
                @endif
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
