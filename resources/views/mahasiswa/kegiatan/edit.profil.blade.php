@extends('layouts.contentMahasiswa')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Edit Profil</h2>

    <form action="{{ route('profil.mahasiswa.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label class="block mb-2 font-medium">Nama</label>
        <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" class="w-full border rounded-lg p-2 mb-4">

        <label class="block mb-2 font-medium">NIM</label>
        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" class="w-full border rounded-lg p-2 mb-4">

        <label class="block mb-2 font-medium">Universitas</label>
        <input type="text" name="universitas" value="{{ old('universitas', $mahasiswa->universitas) }}" class="w-full border rounded-lg p-2 mb-4">

        <label class="block mb-2 font-medium">Foto</label>
        <input type="file" name="foto" class="w-full border rounded-lg p-2 mb-4">
        @if($mahasiswa->foto)
            <img src="{{ asset('storage/foto/' . $mahasiswa->foto) }}" class="w-20 h-20 rounded-full object-cover">
        @endif

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
    </form>
</div>
@endsection
