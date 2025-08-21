@extends('layouts.contentMahasiswa')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-semibold mb-2">Selamat Datang, {{ auth()->user()->username }}.</h2>
    <p class="text-gray-600">Profil Mahasiswa</p>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-semibold mb-4">Profil</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div><strong>Nama</strong>: {{ $mahasiswa->nama }}</div>
            <div><strong>NIM</strong>: {{ $mahasiswa->nim }}</div>
            <div><strong>Universitas</strong>: {{ $mahasiswa->universitas }}</div>
            <div><strong>Tanggal Masuk</strong>: {{ \Carbon\Carbon::parse($mahasiswa->tanggal_masuk)->format('d/m/Y') }}</div>
            <div><strong>Tanggal Selesai</strong>: {{ \Carbon\Carbon::parse($mahasiswa->tanggal_selesai)->format('d/m/Y') }}</div>
            <div class="sm:col-span-2">
                <strong>Foto</strong>:
                @if($mahasiswa->foto)
                    <img src="{{ asset('storage/mahasiswa_photos/' . $mahasiswa->foto) }}" alt="Foto Profil"
                         class="mt-2 w-24 h-24 object-cover rounded-full border">
                @else
                    <p class="text-gray-500 mt-2">Belum ada foto</p>
                @endif
            </div>
        </div>

        <!-- Tombol Edit -->
        <div class="mt-6 flex gap-3">
            <button onclick="document.getElementById('editModal').classList.remove('hidden')" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                Edit Profil
            </button>
        </div>
    </div>
</div>

<!-- Modal Edit Profil -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Edit Profil</h2>

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
                        <img src="{{ asset('storage/mahasiswa_photos/' . $mahasiswa->foto) }}" class="mt-2 w-16 h-16 rounded-full object-cover">
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" 
                        class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
