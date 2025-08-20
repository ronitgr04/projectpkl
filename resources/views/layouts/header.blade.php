@php
    try {
        $instansi = \App\Models\Instansi::first();
    } catch (\Exception $e) {
        $instansi = null;
    }
@endphp

<header class="bg-blue-500 text-white p-4 shadow">
    <div class="flex items-center space-x-4">
        @if($instansi && $instansi->logo)
            <div class="flex-shrink-0">
                <img src="{{ asset('storage/logos/' . $instansi->logo) }}"
                     alt="Logo {{ $instansi->nama_instansi }}"
                     class="h-12 w-12 object-contain bg-white rounded-lg p-1">
            </div>
        @endif

        <div class="flex-1">
            <h1 class="text-lg font-semibold">
                {{ $instansi ? $instansi->nama_instansi : 'DISKOMINFO SIDIKALANG' }}
            </h1>
            <p class="text-blue-100 text-sm">
                Sistem Informasi Absensi & Kegiatan
            </p>
        </div>

        <!-- User info di kanan (opsional) -->
        @auth
            <div class="flex items-center space-x-2 text-sm">
                <span class="material-icons text-lg">account_circle</span>
                <div class="text-right">
                    <div>{{ auth()->user()->username }}</div>
                    <div class="text-xs text-blue-200">{{ ucfirst(auth()->user()->level) }}</div>
                </div>
            </div>
        @endauth
    </div>
</header>
