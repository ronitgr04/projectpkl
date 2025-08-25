<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Services\LocationService;
use Illuminate\Support\Facades\Auth;
//aldallalala
class Absensi extends Model
{
    use HasFactory;

    protected $table = 'tabel_absensi';

    protected $fillable = [
        'mahasiswa_id',
        'status',
        'waktu',
        'hari',
        'tanggal',
        'keterangan',
        'latitude',
        'longitude',
        'jarak_dari_kantor',
        'alamat_lengkap',
        'level'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'jarak_dari_kantor' => 'decimal:2',
    ];

    // Relasi dengan mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($absensi) {
            // Auto generate level jika belum diset
            if (empty($absensi->level)) {
                $absensi->level = $absensi->generateLevel();
            }

            // TAMBAHAN: Validasi hari libur sebelum create
            if (Libur::isLibur($absensi->tanggal)) {
                throw new \Exception('Tidak dapat membuat absensi pada hari libur: ' . $absensi->tanggal);
            }
        });

        static::updating(function ($absensi) {
            // Auto generate level jika level kosong saat update
            if (empty($absensi->level)) {
                $absensi->level = $absensi->generateLevel();
            }

            // TAMBAHAN: Validasi hari libur sebelum update (jika tanggal berubah)
            if ($absensi->isDirty('tanggal') && Libur::isLibur($absensi->tanggal)) {
                throw new \Exception('Tidak dapat mengubah absensi ke hari libur: ' . $absensi->tanggal);
            }
        });
    }

    public function generateLevel()
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Jika user level adalah Admin, return 'Admin'
            if ($user->level === 'Admin') {
                return 'Admin';
            }

            // Jika user level adalah User atau apapun selain Admin, return 'User'
            return 'User';
        }

        // Default jika tidak ada user yang login
        return 'User';
    }

    // Status options
    public static function getStatusOptions()
    {
        return [
            'Hadir' => 'Hadir',
            'Izin' => 'Izin',
            'Sakit' => 'Sakit',
            'Alpha' => 'Alpha'
        ];
    }

    // Accessor untuk format waktu yang lebih user-friendly
    public function getWaktuFormattedAttribute()
    {
        // Jika waktu dalam format H:i:s
        if (strlen($this->waktu) <= 8) {
            return $this->waktu;
        }
        // Jika waktu dalam format datetime
        return Carbon::parse($this->waktu)->format('H:i:s');
    }

    // Accessor untuk format tanggal Indonesia
    public function getTanggalFormattedAttribute()
    {
        return Carbon::parse($this->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
    }

    // Accessor untuk waktu display (H:i)
    public function getWaktuDisplayAttribute()
    {
        if (strlen($this->waktu) <= 8) {
            // Jika format H:i:s, ambil hanya H:i
            return substr($this->waktu, 0, 5);
        }
        // Jika format datetime
        return Carbon::parse($this->waktu)->format('H:i');
    }

    /**
     * TAMBAHAN: Cek apakah tanggal absensi ini adalah hari libur
     */
    public function isOnHoliday()
    {
        return Libur::isLibur($this->tanggal);
    }

    /**
     * TAMBAHAN: Get data hari libur untuk tanggal absensi ini
     */
    public function getHolidayData()
    {
        return Libur::getLiburByTanggal($this->tanggal);
    }

    /**
     * TAMBAHAN: Get holiday status attribute
     */
    public function getHolidayStatusAttribute()
    {
        if (!$this->isOnHoliday()) {
            return [
                'is_holiday' => false,
                'message' => 'Hari kerja normal',
                'class' => 'bg-gray-100 text-gray-800'
            ];
        }

        $holidayData = $this->getHolidayData();

        return [
            'is_holiday' => true,
            'data' => $holidayData,
            'message' => $holidayData ? $holidayData->nama_libur : 'Hari Libur',
            'class' => 'bg-orange-100 text-orange-800'
        ];
    }

    /**
     * Cek apakah absensi ini memiliki data lokasi
     */
    public function hasLocation()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Get formatted distance
     */
    public function getFormattedDistanceAttribute()
    {
        if (is_null($this->jarak_dari_kantor)) {
            return 'Tidak ada data lokasi';
        }

        return LocationService::formatDistance($this->jarak_dari_kantor);
    }

    /**
     * Get Google Maps URL untuk lokasi absensi
     */
    public function getGoogleMapsUrlAttribute()
    {
        if ($this->hasLocation()) {
            return LocationService::getGoogleMapsUrl($this->latitude, $this->longitude);
        }
        return null;
    }

    /**
     * Get status badge class untuk UI
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'Hadir':
                return 'bg-green-100 text-green-800';
            case 'Izin':
                return 'bg-yellow-100 text-yellow-800';
            case 'Sakit':
                return 'bg-blue-100 text-blue-800';
            case 'Alpha':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Get location status untuk UI
     */
    public function getLocationStatusAttribute()
    {
        if (!$this->hasLocation()) {
            return [
                'status' => 'no_location',
                'message' => 'Tidak ada data lokasi',
                'class' => 'bg-gray-100 text-gray-800'
            ];
        }

        if ($this->status !== 'Hadir') {
            return [
                'status' => 'not_applicable',
                'message' => 'Pengecekan lokasi tidak berlaku',
                'class' => 'bg-gray-100 text-gray-800'
            ];
        }

        // Ambil setting instansi untuk cek radius
        $instansi = Instansi::getInstansi();

        if (!$instansi->isLocationCheckEnabled()) {
            return [
                'status' => 'disabled',
                'message' => 'Pengecekan lokasi tidak aktif',
                'class' => 'bg-gray-100 text-gray-800'
            ];
        }

        if ($this->jarak_dari_kantor <= $instansi->radius_absensi) {
            return [
                'status' => 'valid',
                'message' => 'Dalam radius kantor (' . $this->formatted_distance . ')',
                'class' => 'bg-green-100 text-green-800'
            ];
        } else {
            return [
                'status' => 'invalid',
                'message' => 'Di luar radius kantor (' . $this->formatted_distance . ')',
                'class' => 'bg-red-100 text-red-800'
            ];
        }
    }

    /**
     * TAMBAHAN: Method untuk validasi apakah bisa absen pada tanggal tertentu
     */
    public static function canAttendOnDate($date)
    {
        return !Libur::isLibur($date);
    }

    /**
     * TAMBAHAN: Get working days in range (exclude holidays)
     */
    public static function getWorkingDaysInRange($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $workingDays = [];

        while ($start <= $end) {
            if (!Libur::isLibur($start)) {
                $workingDays[] = $start->copy();
            }
            $start->addDay();
        }

        return $workingDays;
    }

    /**
     * TAMBAHAN: Get holiday count in range
     */
    public static function getHolidayCountInRange($startDate, $endDate)
    {
        $holidays = Libur::getLiburInRange($startDate, $endDate);
        $totalDays = 0;

        foreach ($holidays as $holiday) {
            $totalDays += $holiday->durasi_hari;
        }

        return $totalDays;
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeFilterByDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('tanggal', [$startDate, $endDate]);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan nama mahasiswa
     */
    public function scopeFilterByMahasiswa($query, $mahasiswaName)
    {
        if ($mahasiswaName) {
            return $query->whereHas('mahasiswa', function ($q) use ($mahasiswaName) {
                $q->where('nama', 'like', '%' . $mahasiswaName . '%')
                    ->orWhere('nim', 'like', '%' . $mahasiswaName . '%');
            });
        }
        return $query;
    }

    /**
     * Scope untuk hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', now('Asia/Jakarta'));
    }

    /**
     * Scope untuk mahasiswa tertentu
     */
    public function scopeForMahasiswa($query, $mahasiswaId)
    {
        return $query->where('mahasiswa_id', $mahasiswaId);
    }

    /**
     * Scope untuk absensi dengan lokasi
     */
    public function scopeWithLocation($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    /**
     * Scope untuk absensi dalam radius
     */
    public function scopeWithinRadius($query, $radius = null)
    {
        if (is_null($radius)) {
            $instansi = Instansi::getInstansi();
            $radius = $instansi->radius_absensi;
        }

        return $query->where('jarak_dari_kantor', '<=', $radius);
    }

    /**
     * TAMBAHAN: Scope untuk absensi pada hari kerja (non-holiday)
     */
    public function scopeWorkingDays($query)
    {
        $holidays = Libur::active()->pluck('tanggal_mulai', 'tanggal_selesai');

        foreach ($holidays as $end => $start) {
            $query->whereNotBetween('tanggal', [$start, $end]);
        }

        return $query;
    }

    /**
     * TAMBAHAN: Scope untuk absensi pada hari libur
     */
    public function scopeOnHolidays($query)
    {
        $holidays = Libur::active()->get();

        $query->where(function ($q) use ($holidays) {
            foreach ($holidays as $holiday) {
                $q->orWhereBetween('tanggal', [
                    $holiday->tanggal_mulai,
                    $holiday->tanggal_selesai
                ]);
            }
        });

        return $query;
    }

    /**
     * TAMBAHAN: Static method untuk auto-generate Alpha untuk mahasiswa yang tidak absen pada hari kerja
     */
    public static function generateAlphaForMissingAttendance($date = null)
    {
        if (!$date) {
            $date = now('Asia/Jakarta')->subDay(); // Kemarin
        }

        // Skip jika hari libur
        if (Libur::isLibur($date)) {
            return false;
        }

        $mahasiswaIds = \App\Models\Mahasiswa::pluck('id');
        $attendedMahasiswaIds = static::whereDate('tanggal', $date)->pluck('mahasiswa_id');
        $missingMahasiswaIds = $mahasiswaIds->diff($attendedMahasiswaIds);

        $alphaRecords = [];
        foreach ($missingMahasiswaIds as $mahasiswaId) {
            $alphaRecords[] = [
                'mahasiswa_id' => $mahasiswaId,
                'status' => 'Alpha',
                'tanggal' => Carbon::parse($date)->format('Y-m-d'),
                'waktu' => '23:59:59',
                'hari' => Carbon::parse($date)->locale('id')->dayName,
                'keterangan' => 'Auto-generated: Tidak hadir tanpa keterangan',
                'level' => 'System',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        if (!empty($alphaRecords)) {
            static::insert($alphaRecords);
            return count($alphaRecords);
        }

        return 0;
    }
}