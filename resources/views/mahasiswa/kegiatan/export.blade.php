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
            <!-- Header -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Cetak Laporan Kegiatan PKL</h2>
                        <p class="text-gray-600 mt-1">Pilih periode kegiatan yang ingin dicetak</p>
                    </div>
                    <a href="{{ route('mahasiswa.kegiatan.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                        <span class="material-icons text-sm">arrow_back</span>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-center">
                    <span class="material-icons text-red-600 mr-2">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-center mb-2">
                    <span class="material-icons text-red-600 mr-2">error</span>
                    <span class="font-medium">Terjadi kesalahan:</span>
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Export Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('mahasiswa.kegiatan.export.pdf') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Info Mahasiswa -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-blue-900 mb-2">Informasi Mahasiswa</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
                            <div>
                                <span class="font-medium">Nama:</span> {{ $mahasiswa->nama }}
                            </div>
                            <div>
                                <span class="font-medium">NIM:</span> {{ $mahasiswa->nim }}
                            </div>
                            @if($mahasiswa->program_studi)
                            <div>
                                <span class="font-medium">Program Studi:</span> {{ $mahasiswa->program_studi }}
                            </div>
                            @endif
                            @if($mahasiswa->fakultas)
                            <div>
                                <span class="font-medium">Fakultas:</span> {{ $mahasiswa->fakultas }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Filter Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Mulai
                            </label>
                            <input type="date"
                                id="start_date"
                                name="start_date"
                                value="{{ old('start_date', $firstKegiatan ? $firstKegiatan->tanggal : '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">
                                @if($firstKegiatan)
                                Kegiatan pertama: {{ \Carbon\Carbon::parse($firstKegiatan->tanggal)->format('d M Y') }}
                                @else
                                Belum ada kegiatan
                                @endif
                            </p>
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Selesai
                            </label>
                            <input type="date"
                                id="end_date"
                                name="end_date"
                                value="{{ old('end_date', $lastKegiatan ? $lastKegiatan->tanggal : '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">
                                @if($lastKegiatan)
                                Kegiatan terakhir: {{ \Carbon\Carbon::parse($lastKegiatan->tanggal)->format('d M Y') }}
                                @else
                                Belum ada kegiatan
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Info Tambahan -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <span class="material-icons text-yellow-600 mr-2">info</span>
                            <div class="text-sm text-yellow-800">
                                <p class="font-medium mb-1">Catatan:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Jika tanggal tidak diisi, semua kegiatan akan dicetak</li>
                                    <li>PDF akan mencakup detail kegiatan dan foto dokumentasi (jika ada)</li>
                                    <li>Format PDF akan otomatis diatur untuk pencetakan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('mahasiswa.kegiatan.index') }}"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                            <span class="material-icons text-sm">picture_as_pdf</span>
                            <span>Generate PDF</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview Section -->
            @if($firstKegiatan)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Preview Kegiatan</h3>
                <div class="text-sm text-gray-600">
                    <p>Total kegiatan yang tersedia untuk dicetak:
                        <span class="font-medium text-gray-900">
                            {{ \App\Models\Kegiatan::where('mahasiswa_id', $mahasiswa->id)->count() }} kegiatan
                        </span>
                    </p>
                    <p class="mt-1">
                        Periode:
                        <span class="font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($firstKegiatan->tanggal)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($lastKegiatan->tanggal)->format('d M Y') }}
                        </span>
                    </p>
                </div>
            </div>
            @endif
        </div>
        @endsection

        @push('scripts')
        <script>
            // Auto-adjust end date when start date changes
            document.getElementById('start_date').addEventListener('change', function() {
                const startDate = this.value;
                const endDateInput = document.getElementById('end_date');

                if (startDate && (!endDateInput.value || endDateInput.value < startDate)) {
                    endDateInput.value = startDate;
                }
                endDateInput.min = startDate;
            });

            // Validate dates before submit
            document.querySelector('form').addEventListener('submit', function(e) {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;

                if (startDate && endDate && startDate > endDate) {
                    e.preventDefault();
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                }
            });
        </script>
        @endpush

    </div>
</body>

</html>
