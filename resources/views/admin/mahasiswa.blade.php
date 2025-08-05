<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Data Mahasiswa</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .fade-enter-active,
        .fade-leave-active {
            transition: opacity 0.3s ease;
        }

        .fade-enter,
        .fade-leave-to {
            opacity: 0;
        }

        .scale-enter-active,
        .scale-leave-active {
            transition: all 0.3s ease;
        }

        .modal-overlay {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.3);
            /* Optional: semi-transparent white */
        }


        .scale-enter,
        .scale-leave-to {
            transform: scale(0.95);
            opacity: 0;
        }

        .modal-overlay {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .photo-container {
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="">
        @extends('layouts.content')

        @section('content')
        <div x-data="mahasiswaApp()">
            <!-- Delete Confirmation Modal -->
            <div x-show="showDeleteModal"
                x-transition:enter="fade-enter-active"
                x-transition:leave="fade-leave-active"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
                x-cloak>
                <div x-show="showDeleteModal"
                    x-transition:enter="scale-enter-active"
                    x-transition:leave="scale-leave-active"
                    class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-center mb-4">
                            <div class="bg-red-100 rounded-full p-3">
                                <span class="material-icons text-4xl text-red-500">warning</span>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-center text-gray-800 mb-2">Hapus Data Mahasiswa</h3>
                        <p class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus data mahasiswa ini?</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <p class="font-medium text-gray-700" x-text="selected.nama"></p>
                            <p class="text-sm text-gray-600" x-text="'NIM: ' + selected.nim"></p>
                        </div>
                        <p class="text-gray-600 mb-6 text-center">Data yang sudah dihapus tidak dapat dikembalikan.</p>
                        <div class="flex gap-3">
                            <button @click="showDeleteModal = false"
                                type="button"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2.5 px-4 rounded-lg transition duration-200">
                                Batal
                            </button>
                            <button @click="deleteMahasiswa()"
                                type="button"
                                class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                <span class="material-icons text-base mr-1">delete</span>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mx-auto px-4 py-6">
                <h2 class="text-2xl font-semibold mb-4">Data Mahasiswa Magang</h2>
                <!-- Success Message -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-icons text-green-500">check_circle</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm whitespace-pre-line">{{ session('success') }}</p>
                            <!-- Show account details if available -->
                            @if(session('new_account'))
                            <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded">
                                <p class="font-semibold text-green-800">Detail Akun Baru:</p>
                                <p><strong>Nama Mahasiswa:</strong> {{ session('new_account')['mahasiswa_nama'] }}</p>
                                <p><strong>Username:</strong> {{ session('new_account')['username'] }}</p>
                                <p><strong>Kode Pengguna:</strong> {{ session('new_account')['kode_pengguna'] }}</p>
                                <p class="text-sm text-green-600 mt-2">* Silakan catat informasi ini untuk diberikan kepada mahasiswa</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                <!-- Error Message -->
                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-icons text-red-500">error</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Validation Errors -->
                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-icons text-red-500">warning</span>
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">Terdapat kesalahan dalam form:</p>
                            <ul class="mt-2 text-sm list-disc list-inside">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Add New Button -->
                <div class="mb-4">
                    <a href="{{ route('admin.mahasiswa.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
                        <span class="material-icons mr-2">add</span>
                        Tambah Mahasiswa
                    </a>
                </div>
                <!-- Data Table -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Universitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mulai Magang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akhir Magang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Akun</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($mahasiswa as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="{{ $item->foto_url }}"
                                        alt="Foto {{ $item->nama }}"
                                        class="w-12 h-12 rounded-full object-cover">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->nim }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->universitas }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->mulai_magang)->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->akhir_magang)->format('d M Y') }}</div>
                                </td>
                                <td>
                                    @if($item->hasUserAccount())

                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="material-icons text-xs mr-1">check_circle</span>
                                        Sudah Ada Akun
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <span class="material-icons text-xs mr-1">person_outline</span>
                                        Belum Ada Akun
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center space-x-1">
                                        <button
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-sm inline-flex items-center"
                                            @click="openModal({{ json_encode($item) }})">
                                            <span class="material-icons text-sm">visibility</span>
                                        </button>
                                        <a :href="'/admin/mahasiswa/' + {{ $item->id }} + '/edit'"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-sm inline-flex items-center">
                                            <span class="material-icons text-sm">edit</span>
                                        </a>
                                        <button
                                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm inline-flex items-center"
                                            @click="confirmDelete({{ json_encode($item) }})">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <span class="material-icons text-4xl text-gray-400 mb-2">inbox</span>
                                        <p>Belum ada data mahasiswa</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if($mahasiswa->hasPages())
                <div class="mt-4">
                    {{ $mahasiswa->links() }}
                </div>
                @endif
            </div>
            <!-- Detail Modal -->
            <div x-show="showModal"
                class="fixed inset-0 modal-overlay flex items-center justify-center z-60"

                x-cloak>
                <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-screen overflow-y-auto">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center p-6 border-b">
                        <h3 class="text-2xl font-bold text-gray-800">Detail Mahasiswa</h3>
                        <button @click="closeModal()"
                            class="text-gray-400 hover:text-gray-600 text-2xl">
                            &times;
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Photo Section -->
                            <div class="flex-shrink-0">
                                <img :src="selected.foto ? '/storage/mahasiswa_photos/' + selected.foto : '/images/defaultprofil.png'"
                                    :alt="'Foto ' + selected.nama"
                                    class="w-38 h-46 rounded-2 object-cover border-4 border-gray-200 mx-auto">

                            </div>
                            <!-- Details Section -->
                            <div class="flex-grow space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                                        <p class="text-lg font-semibold text-gray-800" x-text="selected.nama"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">NIM</label>
                                        <p class="text-lg text-gray-800" x-text="selected.nim"></p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Universitas</label>
                                        <p class="text-lg text-gray-800" x-text="selected.universitas"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Mulai Magang</label>
                                        <p class="text-lg text-gray-800" x-text="formatDate(selected.mulai_magang)"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Akhir Magang</label>
                                        <p class="text-lg text-gray-800" x-text="formatDate(selected.akhir_magang)"></p>
                                    </div>
                                </div>
                                <!-- Status Badge -->
                                <div class="mt-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium"
                                        :class="getStatusBadge()">
                                        <span class="material-icons text-sm mr-1" x-text="getStatusIcon()"></span>
                                        <span x-text="getStatusText()"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer with Actions -->
                    <div class="border-t p-6">
                        <div class="flex flex-wrap gap-3 justify-center">
                            <!-- Create Account Button -->
                            <template x-if="!selected.user_id">
                                <button @click="showCreateAccount = true"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded inline-flex items-center">
                                    <span class="material-icons mr-2 text-sm">person_add</span>
                                    Buat Akun
                                </button>
                            </template>
                            <!-- Account Status -->
                            <template x-if="selected.user_id">
                                <div class="bg-green-100 text-green-800 px-4 py-2 rounded inline-flex items-center">
                                    <span class="material-icons mr-2 text-sm">check_circle</span>
                                    Akun Aktif
                                </div>
                            </template>
                            <!-- Edit Button -->
                            <a :href="'/admin/mahasiswa/' + selected.id + '/edit'"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded inline-flex items-center">
                                <span class="material-icons mr-2 text-sm">edit</span>
                                Edit Data
                            </a>
                            <!-- Delete Button -->
                            <button @click="confirmDelete(selected)"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded inline-flex items-center">
                                <span class="material-icons mr-2 text-sm">delete</span>
                                Hapus Data
                            </button>
                            <!-- Close Button -->
                            <button @click="closeModal()"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded inline-flex items-center">
                                <span class="material-icons mr-2 text-sm">close</span>
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Create Account Modal (Form Submit) -->
            <div x-show="showCreateAccount"
                class="fixed inset-0 modal-overlay flex items-center justify-center z-60"

                x-cloak>
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                    <div class="flex justify-between items-center p-6 border-b">
                        <h3 class="text-xl font-bold text-gray-800">Buat Akun Mahasiswa</h3>
                        <button @click="showCreateAccount = false"
                            class="text-gray-400 hover:text-gray-600 text-2xl">
                            &times;
                        </button>
                    </div>
                    <!-- Form Submit (Non-API) -->
                    <form :action="'/admin/mahasiswa/' + selected.id + '/create-account'" method="POST" onsubmit="return validateCreateAccountForm()">
                        @csrf
                        <div class="p-6 space-y-4">
                            <!-- Mahasiswa Info -->
                            <div class="bg-gray-50 p-3 rounded">
                                <p class="text-sm text-gray-600">Membuat akun untuk:</p>
                                <p class="font-semibold text-gray-800" x-text="selected.nama"></p>
                                <p class="text-sm text-gray-600" x-text="'NIM: ' + selected.nim"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                <input type="text"
                                    name="username"
                                    x-model="accountForm.username"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Masukkan username"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <input type="password"
                                    name="password"
                                    x-model="accountForm.password"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Minimal 6 karakter"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                <input type="password"
                                    name="password_confirmation"
                                    x-model="accountForm.password_confirmation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Ulangi password"
                                    required>
                            </div>
                            <div class="bg-blue-50 p-3 rounded">
                                <p class="text-sm text-blue-600">
                                    <span class="material-icons text-sm mr-1">info</span>
                                    Setelah akun dibuat, username dan kode pengguna akan ditampilkan. Pastikan untuk mencatatnya.
                                </p>
                            </div>
                        </div>
                        <div class="border-t p-6 flex gap-3 justify-end">
                            <button type="button"
                                @click="showCreateAccount = false; resetAccountForm()"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                Batal
                            </button>
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded inline-flex items-center">
                                <span class="material-icons mr-2 text-sm">person_add</span>
                                Buat Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Delete Form (Hidden) -->
            <form x-ref="deleteForm" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
        <script>
            function mahasiswaApp() {
                return {
                    showModal: false,
                    showDeleteModal: false,
                    showCreateAccount: false,
                    selected: {},
                    accountForm: {
                        username: '',
                        password: '',
                        password_confirmation: ''
                    },
                    openModal(mahasiswa) {
                        this.selected = mahasiswa;
                        this.showModal = true;
                    },
                    closeModal() {
                        this.showModal = false;
                        this.showCreateAccount = false;
                        this.resetAccountForm();
                    },
                    resetAccountForm() {
                        this.accountForm = {
                            username: '',
                            password: '',
                            password_confirmation: ''
                        };
                    },
                    formatDate(dateString) {
                        if (!dateString) return '-';
                        return new Date(dateString).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                    },
                    getStatusBadge() {
                        const now = new Date();
                        const mulai = new Date(this.selected.mulai_magang);
                        const akhir = new Date(this.selected.akhir_magang);
                        if (now < mulai) {
                            return 'bg-blue-100 text-blue-800';
                        } else if (now >= mulai && now <= akhir) {
                            return 'bg-green-100 text-green-800';
                        } else {
                            return 'bg-gray-100 text-gray-800';
                        }
                    },
                    getStatusIcon() {
                        const now = new Date();
                        const mulai = new Date(this.selected.mulai_magang);
                        const akhir = new Date(this.selected.akhir_magang);
                        if (now < mulai) {
                            return 'schedule';
                        } else if (now >= mulai && now <= akhir) {
                            return 'work';
                        } else {
                            return 'check_circle';
                        }
                    },
                    getStatusText() {
                        const now = new Date();
                        const mulai = new Date(this.selected.mulai_magang);
                        const akhir = new Date(this.selected.akhir_magang);
                        if (now < mulai) {
                            return 'Belum Mulai';
                        } else if (now >= mulai && now <= akhir) {
                            return 'Sedang Magang';
                        } else {
                            return 'Selesai';
                        }
                    },
                    confirmDelete(mahasiswa) {
                        this.selected = mahasiswa;
                        this.showDeleteModal = true;
                    },
                    deleteMahasiswa() {
                        const form = this.$refs.deleteForm;
                        form.action = `/admin/mahasiswa/${this.selected.id}`;
                        form.submit();
                        this.showDeleteModal = false;
                    }
                }
            }
            // Form validation before submit
            function validateCreateAccountForm() {
                const username = document.querySelector('input[name="username"]').value.trim();
                const password = document.querySelector('input[name="password"]').value;
                const confirmation = document.querySelector('input[name="password_confirmation"]').value;
                if (!username) {
                    alert('Username wajib diisi!');
                    return false;
                }
                if (username.length < 3) {
                    alert('Username minimal 3 karakter!');
                    return false;
                }
                if (!password) {
                    alert('Password wajib diisi!');
                    return false;
                }
                if (password.length < 6) {
                    alert('Password minimal 6 karakter!');
                    return false;
                }
                if (password !== confirmation) {
                    alert('Password dan konfirmasi password tidak cocok!');
                    return false;
                }
                return true;
            }
            // Auto dismiss alerts after 10 seconds
            document.addEventListener('DOMContentLoaded', function() {
                const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.style.transition = 'opacity 0.5s';
                        alert.style.opacity = '0';
                        setTimeout(() => {
                            alert.remove();
                        }, 500);
                    }, 10000);
                });
            });
        </script>
        <style>
            [x-cloak] {
                display: none !important;
            }

            .fade-enter-active,
            .fade-leave-active {
                transition: opacity 0.3s ease;
            }

            .fade-enter,
            .fade-leave-to {
                opacity: 0;
            }

            .scale-enter-active,
            .scale-leave-active {
                transition: all 0.3s ease;
            }

            .scale-enter,
            .scale-leave-to {
                transform: scale(0.95);
                opacity: 0;
            }
        </style>
        @endsection
    </div>
</body>

</html>
