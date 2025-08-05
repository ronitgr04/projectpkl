<div class="w-64 bg-white border-r shadow-sm flex flex-col">
    <div class="p-6 border-b">
        <h1 class="text-lg font-bold text-blue-600">ABSENSI | KEGIATAN</h1>
    </div>
    <div class="p-4 border-b flex items-center gap-3">
        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-white text-xl">
            <span class="material-icons">person</span>
        </div>
        <div>
            <p class="font-semibold">{{ Auth::user()->username ?? 'Roni Tumangger' }}</p>
            <p class="text-sm text-gray-500">{{ ucfirst(Auth::user()->level ?? 'Administrator') }}</p>
        </div>
    </div>
    <nav class="flex-1 p-4 space-y-2 text-gray-700">
        <a href="/dashboard"
           class="flex items-center gap-2 p-2 rounded-md transition-colors {{ request()->is('dashboard') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:text-blue-600 hover:bg-gray-50' }}">
            <span class="material-icons">home</span> Beranda
        </a>

        <a href="/mahasiswa/absensi"
           class="flex items-center gap-2 p-2 rounded-md transition-colors {{ request()->is('mahasiswa/absensi*') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:text-blue-600 hover:bg-gray-50' }}">
            <span class="material-icons">group</span> Absensi
        </a>

        <a href="/mahasiswa/riwayatabsensi"
           class="flex items-center gap-2 p-2 rounded-md transition-colors {{ request()->is('mahasiswa/riwayatabsensi*') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:text-blue-600 hover:bg-gray-50' }}">
            <span class="material-icons">event_note</span> Riwayat Absensi
        </a>

        <a href="/mahasiswa/kegiatan"
           class="flex items-center gap-2 p-2 rounded-md transition-colors {{ request()->is('mahasiswa/kegiatan*') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:text-blue-600 hover:bg-gray-50' }}">
            <span class="material-icons">assignment</span> Kegiatan Harian
        </a>

        <a href="/mahasiswa/profil"
           class="flex items-center gap-2 p-2 rounded-md transition-colors {{ request()->is('mahasiswa/profil*') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:text-blue-600 hover:bg-gray-50' }}">
            <span class="material-icons">admin_panel_settings</span> Profil
        </a>



        <!-- METODE 1: Menggunakan Form tersembunyi dengan JavaScript -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center gap-2 p-2 rounded-md text-red-600 hover:text-red-800 hover:bg-red-50 transition-colors">
            <span class="material-icons">logout</span> Keluar
        </a>
    </nav>
</div>
