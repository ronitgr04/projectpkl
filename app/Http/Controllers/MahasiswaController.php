<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Instansi;
use App\Models\Libur; // TAMBAHAN IMPORT
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MahasiswaAbsensiController extends Controller
{
    /**
     * Menampilkan halaman absensi untuk mahasiswa
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa ?? Mahasiswa::where('user_id', $user->id_user)->first();

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan. Silakan hubungi admin.');
        }

        // Get instansi setting untuk jam absensi dan lokasi
        $instansi = Instansi::getInstansi();

        // Set timezone ke WIB (Asia/Jakarta)
        Carbon::setLocale('id');
        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        $currentTime = Carbon::now('Asia/Jakarta');

        // dd($today);

        // TAMBAHAN: Check if today is a holiday
        $todayHoliday = Libur::getLiburByTanggal($today);
        $isHoliday = Libur::isLibur($today);

        // Check if already attended today - INI YANG DIPERBAIKI
        $todayAttendance = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->whereDate('tanggal', $today->toDateString()) // Pastikan format tanggal konsisten
            ->first();

        // Log untuk debugging
        Log::info('Checking today attendance:', [
            'mahasiswa_id' => $mahasiswa->id,
            'today_date' => $today->toDateString(),
            'is_holiday' => $isHoliday,
            'holiday_data' => $todayHoliday ? $todayHoliday->toArray() : null,
            'found_attendance' => $todayAttendance ? $todayAttendance->id : null,
            'attendance_data' => $todayAttendance ? [
                'id' => $todayAttendance->id,
                'status' => $todayAttendance->status,
                'tanggal' => $todayAttendance->tanggal,
                'waktu' => $todayAttendance->waktu
            ] : null
        ]);

        // Check if current time is within attendance hours
        $isWithinAttendanceHours = $instansi->isWithinAttendanceHours($currentTime->format('H:i'));

        return view('mahasiswa.absensi', compact(
            'mahasiswa',
            'todayAttendance',
            'instansi',
            'isWithinAttendanceHours',
            'currentTime',
            'isHoliday', // TAMBAHAN
            'todayHoliday' // TAMBAHAN
        ));
    }

    /**
     * Menyimpan data absensi mahasiswa dengan location check
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa ?? Mahasiswa::where('user_id', $user->id_user)->first();

        if (!$mahasiswa) {
            Log::error('Data mahasiswa tidak ditemukan untuk user ID: ' . $user->id_user);
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Get instansi setting untuk validasi jam dan lokasi
        $instansi = Instansi::getInstansi();

        // Set timezone ke WIB
        Carbon::setLocale('id');
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->copy()->startOfDay();
        // dd($today);

        // TAMBAHAN: Check if today is a holiday BEFORE any other validation
        $isHoliday = Libur::isLibur($today);
        $todayHoliday = Libur::getLiburByTanggal($today);



        if ($isHoliday) {
            // dd('sedang libur bro');
            $holidayName = $todayHoliday ? $todayHoliday->nama_libur : 'Hari Libur';
            $errorMessage = "Absensi tidak dapat dilakukan pada hari libur: {$holidayName}";

            Log::warning('Attendance attempted on holiday:', [
                'mahasiswa_id' => $mahasiswa->id,
                'date' => $today->toDateString(),
                'holiday_name' => $holidayName,
                'holiday_type' => $todayHoliday ? $todayHoliday->jenis_libur : 'unknown'
            ]);

            return redirect()->back()->with('error', $errorMessage);
        }

        // DOUBLE CHECK - Cek apakah sudah absen hari ini SEBELUM validasi lainnya
        $existingAttendance = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->whereDate('tanggal', $today->toDateString())
            ->first();

        if ($existingAttendance) {
            Log::warning('Attempt to submit duplicate attendance:', [
                'mahasiswa_id' => $mahasiswa->id,
                'existing_attendance_id' => $existingAttendance->id,
                'existing_date' => $existingAttendance->tanggal,
                'today_date' => $today->toDateString()
            ]);

            return redirect()->route('mahasiswa.absensi')
                ->with('error', 'Anda sudah melakukan absensi hari ini pada ' . $existingAttendance->waktu . ' dengan status: ' . $existingAttendance->status);
        }

        // Validasi jam absensi
        $currentTimeString = $now->format('H:i');
        $isWithinHours = $instansi->isWithinAttendanceHours($currentTimeString);

        if (!$isWithinHours) {
            $attendanceHours = $instansi->getFormattedAttendanceHours();
            $errorMessage = "Absensi hanya dapat dilakukan pada jam {$attendanceHours}. Waktu saat ini: {$currentTimeString} WIB";

            Log::warning('Attendance attempted outside hours:', [
                'mahasiswa_id' => $mahasiswa->id,
                'current_time' => $currentTimeString,
                'allowed_hours' => $attendanceHours
            ]);

            return redirect()->back()->with('error', $errorMessage);
        }

        // Validasi input
        $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit',
            'keterangan' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ], [
            'status.required' => 'Status absensi wajib dipilih',
            'status.in' => 'Status absensi tidak valid',
            'keterangan.max' => 'Keterangan maksimal 500 karakter',
            'latitude.between' => 'Latitude tidak valid',
            'longitude.between' => 'Longitude tidak valid'
        ]);

        // Validasi keterangan untuk status Izin dan Sakit
        if (in_array($request->status, ['Izin', 'Sakit']) && empty(trim($request->keterangan))) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Keterangan wajib diisi untuk status {$request->status}");
        }

        // Initialize location data
        $locationData = [
            'latitude' => null,
            'longitude' => null,
            'jarak_dari_kantor' => null,
            'alamat_lengkap' => null
        ];

        // Location validation untuk status "Hadir"
        if ($request->status === 'Hadir' && $instansi->isLocationCheckEnabled()) {
            // Cek apakah koordinat dikirim
            if (!$request->has('latitude') || !$request->has('longitude')) {
                return redirect()->back()->with('error', 'Lokasi diperlukan untuk absensi Hadir. Pastikan GPS aktif dan izinkan akses lokasi.');
            }

            $userLat = $request->latitude;
            $userLon = $request->longitude;

            // Validasi koordinat
            if (!LocationService::isValidCoordinate($userLat, $userLon)) {
                return redirect()->back()->with('error', 'Koordinat lokasi tidak valid.');
            }

            // Cek apakah dalam radius
            $locationCheck = $instansi->isUserWithinRadius($userLat, $userLon);

            Log::info('Location check result:', [
                'user_lat' => $userLat,
                'user_lon' => $userLon,
                'office_lat' => $instansi->latitude,
                'office_lon' => $instansi->longitude,
                'distance' => $locationCheck['distance'] ?? 'unknown',
                'is_within_radius' => $locationCheck['is_within_radius'] ?? false,
                'allowed_radius' => $instansi->radius_absensi
            ]);

            if (!$locationCheck['is_within_radius']) {
                $distance = $locationCheck['distance_formatted'] ?? LocationService::formatDistance($locationCheck['distance']);
                $allowedRadius = LocationService::formatDistance($instansi->radius_absensi);

                return redirect()->back()->with(
                    'error',
                    "Anda berada di luar radius kantor. Jarak Anda: {$distance}, Radius yang diizinkan: {$allowedRadius}. Silakan datang ke kantor untuk melakukan absensi Hadir."
                );
            }

            // Set location data
            $locationData = [
                'latitude' => $userLat,
                'longitude' => $userLon,
                'jarak_dari_kantor' => $locationCheck['distance'],
                'alamat_lengkap' => LocationService::getAddressFromCoordinates($userLat, $userLon)
            ];
        } elseif ($request->status !== 'Hadir' && $request->has('latitude') && $request->has('longitude')) {
            // Untuk status selain "Hadir", tetap simpan lokasi jika tersedia (opsional)
            $userLat = $request->latitude;
            $userLon = $request->longitude;

            if (LocationService::isValidCoordinate($userLat, $userLon)) {
                $distance = 0;

                if ($instansi->hasLocation()) {
                    $distance = LocationService::calculateDistance(
                        $userLat,
                        $userLon,
                        $instansi->latitude,
                        $instansi->longitude
                    );
                }

                $locationData = [
                    'latitude' => $userLat,
                    'longitude' => $userLon,
                    'jarak_dari_kantor' => $distance,
                    'alamat_lengkap' => LocationService::getAddressFromCoordinates($userLat, $userLon)
                ];
            }
        }

        // Refresh waktu untuk mendapatkan waktu terkini
        $now = Carbon::now('Asia/Jakarta');

        $absensiData = array_merge([
            'mahasiswa_id' => $mahasiswa->id,
            'status' => $request->status,
            'tanggal' => $now->toDateString(), // Format: Y-m-d
            'waktu' => $now->format('H:i:s'),   // Format: H:i:s
            'hari' => $now->locale('id')->dayName,
            'keterangan' => $request->keterangan ?? ''
        ], $locationData);

        // Debug: Log data yang akan disimpan
        Log::info('Data absensi yang akan disimpan:', $absensiData);

        try {
            // Simpan data absensi
            $absensi = Absensi::create($absensiData);

            // Debug: Log data yang tersimpan
            Log::info('Data absensi berhasil tersimpan:', [
                'absensi_id' => $absensi->id,
                'mahasiswa_id' => $absensi->mahasiswa_id,
                'status' => $absensi->status,
                'waktu' => $absensi->waktu,
                'tanggal' => $absensi->tanggal,
                'has_location' => $absensi->hasLocation(),
                'distance' => $absensi->jarak_dari_kantor,
                'created_at' => $absensi->created_at
            ]);

            $successMessage = "Absensi {$request->status} berhasil disimpan pada {$now->format('d/m/Y H:i:s')} WIB";

            // Tambahkan info lokasi ke pesan sukses jika ada
            if ($absensi->hasLocation() && $request->status === 'Hadir') {
                $successMessage .= " (Jarak dari kantor: {$absensi->formatted_distance})";
            }

            return redirect()->route('mahasiswa.absensi')->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Error saving attendance:', [
                'error' => $e->getMessage(),
                'mahasiswa_id' => $mahasiswa->id,
                'data' => $absensiData
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan absensi. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan riwayat absensi mahasiswa
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa ?? Mahasiswa::where('user_id', $user->id_user)->first();

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $query = Absensi::where('mahasiswa_id', $mahasiswa->id);

        // Filter berdasarkan bulan dan tahun
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                ->whereYear('tanggal', $request->tahun);
        } elseif ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan)
                ->whereYear('tanggal', date('Y'));
        } elseif ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $riwayatAbsensi = $query->orderBy('tanggal', 'desc')->paginate(15);

        // Statistik absensi
        $totalHadir = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'Hadir')
            ->count();
        $totalIzin = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'Izin')
            ->count();
        $totalSakit = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'Sakit')
            ->count();
        $totalAlpha = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'Alpha')
            ->count();

        $level = $user->level;

        // Get instansi untuk info lokasi
        $instansi = Instansi::getInstansi();

        return view('mahasiswa.riwayatabsensi', compact(
            'mahasiswa',
            'level',
            'riwayatAbsensi',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlpha',
            'instansi'
        ));
    }

    /**
     * Mengecek status absensi hari ini (untuk AJAX)
     */
    public function checkTodayAttendance()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa ?? Mahasiswa::where('user_id', $user->id_user)->first();

        if (!$mahasiswa) {
            return response()->json(['error' => 'Data mahasiswa tidak ditemukan'], 404);
        }

        $instansi = Instansi::getInstansi();
        Carbon::setLocale('id');
        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        $currentTime = Carbon::now('Asia/Jakarta');

        // TAMBAHAN: Check holiday status
        $isHoliday = Libur::isLibur($today);
        $todayHoliday = Libur::getLiburByTanggal($today);

        $todayAttendance = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->whereDate('tanggal', $today->toDateString())
            ->first();

        $isWithinAttendanceHours = $instansi->isWithinAttendanceHours($currentTime->format('H:i'));

        return response()->json([
            'has_attendance' => $todayAttendance ? true : false,
            'attendance' => $todayAttendance,
            'is_within_hours' => $isWithinAttendanceHours,
            'attendance_hours' => $instansi->getFormattedAttendanceHours(),
            'current_time' => $currentTime->format('H:i'),
            'location_check_enabled' => $instansi->isLocationCheckEnabled(),
            'is_holiday' => $isHoliday, // TAMBAHAN
            'holiday_data' => $todayHoliday ? [ // TAMBAHAN
                'name' => $todayHoliday->nama_libur,
                'type' => $todayHoliday->jenis_libur,
                'description' => $todayHoliday->keterangan,
                'start_date' => $todayHoliday->tanggal_mulai_formatted,
                'end_date' => $todayHoliday->tanggal_selesai_formatted
            ] : null,
            'office_location' => $instansi->hasLocation() ? [
                'latitude' => $instansi->latitude,
                'longitude' => $instansi->longitude,
                'radius' => $instansi->radius_absensi,
                'formatted_radius' => $instansi->formatted_radius
            ] : null
        ]);
    }

    /**
     * Endpoint untuk validasi lokasi via AJAX
     */
    public function validateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $instansi = Instansi::getInstansi();

        if (!$instansi->isLocationCheckEnabled()) {
            return response()->json([
                'valid' => true,
                'message' => 'Pengecekan lokasi tidak aktif',
                'distance' => 0
            ]);
        }

        $userLat = $request->latitude;
        $userLon = $request->longitude;

        $locationCheck = $instansi->isUserWithinRadius($userLat, $userLon);

        return response()->json([
            'valid' => $locationCheck['is_within_radius'],
            'distance' => $locationCheck['distance'],
            'formatted_distance' => $locationCheck['distance_formatted'] ?? LocationService::formatDistance($locationCheck['distance']),
            'allowed_radius' => LocationService::formatDistance($instansi->radius_absensi),
            'message' => $locationCheck['is_within_radius']
                ? 'Anda berada dalam radius kantor'
                : 'Anda berada di luar radius kantor'
        ]);
    }

    /**
     * TAMBAHAN: Method untuk cek status hari libur via AJAX
     */
    public function checkHolidayStatus(Request $request)
    {
        $date = $request->get('date', now('Asia/Jakarta')->format('Y-m-d'));

        $isHoliday = Libur::isLibur($date);
        $holidayData = Libur::getLiburByTanggal($date);

        return response()->json([
            'is_holiday' => $isHoliday,
            'holiday_data' => $holidayData ? [
                'id' => $holidayData->id,
                'name' => $holidayData->nama_libur,
                'type' => $holidayData->jenis_libur,
                'description' => $holidayData->keterangan,
                'start_date' => $holidayData->tanggal_mulai_formatted,
                'end_date' => $holidayData->tanggal_selesai_formatted,
                'duration' => $holidayData->durasi_hari
            ] : null
        ]);
    }

    /**
     * Debug method untuk troubleshooting location
     */
    public function debugLocation(Request $request)
    {
        $user = Auth::user();
        $instansi = Instansi::getInstansi();
        $today = Carbon::now('Asia/Jakarta')->startOfDay();

        $data = [
            'user_id' => $user->id_user,
            'current_date' => $today->format('Y-m-d'),
            'is_holiday' => Libur::isLibur($today), // TAMBAHAN
            'holiday_data' => Libur::getLiburByTanggal($today), // TAMBAHAN
            'instansi' => [
                'has_location' => $instansi->hasLocation(),
                'latitude' => $instansi->latitude,
                'longitude' => $instansi->longitude,
                'radius' => $instansi->radius_absensi,
                'location_check_enabled' => $instansi->isLocationCheckEnabled(),
                'google_maps_url' => $instansi->google_maps_url
            ],
            'request_location' => [
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude')
            ]
        ];

        if ($request->has('latitude') && $request->has('longitude') && $instansi->hasLocation()) {
            $locationCheck = $instansi->isUserWithinRadius($request->latitude, $request->longitude);
            $data['location_check'] = $locationCheck;
        }

        return response()->json($data);
    }
}