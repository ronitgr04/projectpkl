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

        <a href="/admin/mahasiswa"
           class="flex items-center gap-2 p-2 rounded-md transition-colors {{ request()->is('admin/mahasiswa*') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:text-blue-600 hover:bg-gray-50' }}">
            <span class="material-icons">group</span> Data Mahasiswa
        </a>

        <a href="/admin/absensi"
           class="flex items-center gap-2 p-2 rounded-md transition-colors {{ request()->is('admin/absensi*') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:text-blue-600 hover:bg-gray-50' }}">
            <span class="material-icons">event_note</span> Data Absensi
        </a>

        <a href="/admin/kegiatan"
           class="flex items-center gap-2 p-2 rounded-md transition-colors {{ request()->is('admin/kegiatan*') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:text-blue-600 hover:bg-gray-50' }}">
            <span class="material-icons">assignment</span> Data Kegiatan
        </a>

        <a href="/admin/administrator"
           class="flex items-center gap-2 p-2 rounded-md transition-colors {{ request()->is('admin/administrator*') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:text-blue-600 hover:bg-gray-50' }}">
            <span class="material-icons">admin_panel_settings</span> Administrator
        </a>

        <a href="/admin/pengaturan"
           class="flex items-center gap-2 p-2 rounded-md transition-colors {{ request()->is('admin/pengaturan*') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:text-blue-600 hover:bg-gray-50' }}">
            <span class="material-icons">settings</span> Pengaturan
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
