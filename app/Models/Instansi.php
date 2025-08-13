<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Services\LocationService;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'tabel_setting_absensi';

    protected $fillable = [
        'nama_instansi',
        'nama_ketua',
        'nama_pembina',
        'alamat',
        'no_telp',
        'logo',
        'website',
        'jam_mulai_absensi',
        'jam_akhir_absensi',
        'latitude',
        'longitude',
        'radius_absensi',
        'enable_location_check',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius_absensi' => 'integer',
        'enable_location_check' => 'boolean',
    ];

    /**
     * Get the logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/logos/' . $this->logo);
        }
        return asset('images/default-logo.png');
    }

    /**
     * Get the first instansi record (since there should only be one)
     */
    public static function getInstansi()
    {
        return self::first() ?? new self([
            'jam_mulai_absensi' => '08:00',
            'jam_akhir_absensi' => '17:00',
            'radius_absensi' => 1000,
            'enable_location_check' => true,
        ]);
    }

    /**
     * Check if current time is within attendance hours
     */
    public function isWithinAttendanceHours($time = null)
    {
        if ($time === null) {
            $currentTime = Carbon::now('Asia/Jakarta')->format('H:i');
        } else {
            $currentTime = $time;
        }

        // Pastikan format waktu yang konsisten
        $startTime = $this->jam_mulai_absensi;
        $endTime = $this->jam_akhir_absensi;

        // Jika jam_mulai_absensi dalam format datetime, ekstrak hanya jam:menit
        if (strlen($startTime) > 5) {
            $startTime = Carbon::parse($startTime)->format('H:i');
        }
        if (strlen($endTime) > 5) {
            $endTime = Carbon::parse($endTime)->format('H:i');
        }

        return $currentTime >= $startTime && $currentTime <= $endTime;
    }


    /**
     * Get formatted attendance hours
     */
    public function getFormattedAttendanceHours()
    {
        $startTime = $this->jam_mulai_absensi;
        $endTime = $this->jam_akhir_absensi;

        // Jika dalam format datetime, ekstrak hanya jam:menit
        if (strlen($startTime) > 5) {
            $startTime = Carbon::parse($startTime)->format('H:i');
        }
        if (strlen($endTime) > 5) {
            $endTime = Carbon::parse($endTime)->format('H:i');
        }

        return $startTime . ' - ' . $endTime;
    }

    /**
     * Get jam mulai absensi dalam format H:i
     */
    public function getJamMulaiFormattedAttribute()
    {
        if (strlen($this->jam_mulai_absensi) > 5) {
            return Carbon::parse($this->jam_mulai_absensi)->format('H:i');
        }
        return $this->jam_mulai_absensi;
    }

    /**
     * Get jam akhir absensi dalam format H:i
     */
    public function getJamAkhirFormattedAttribute()
    {
        if (strlen($this->jam_akhir_absensi) > 5) {
            return Carbon::parse($this->jam_akhir_absensi)->format('H:i');
        }
        return $this->jam_akhir_absensi;
    }

    /**
     * Cek apakah lokasi kantor sudah di-set
     */
    public function hasLocation()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Cek apakah pengecekan lokasi aktif
     */
    public function isLocationCheckEnabled()
    {
        return $this->enable_location_check && $this->hasLocation();
    }

    /**
     * Cek apakah user berada dalam radius yang diizinkan
     */
    public function isUserWithinRadius($userLat, $userLon)
    {
         //1//
        if (!$this->isLocationCheckEnabled()) {
            return [
                'is_within_radius' => true,
                'distance' => 0,
                'message' => 'Pengecekan lokasi tidak aktif'
            ];
        }

        if (!LocationService::isValidCoordinate($userLat, $userLon)) {
            return [
                'is_within_radius' => false,
                'distance' => null,
                'message' => 'Koordinat tidak valid'
            ];
        }

        return LocationService::isWithinRadius(
            $userLat,
            $userLon,
            $this->latitude,
            $this->longitude,
            $this->radius_absensi
        );
       
    }

    /**
     * Get formatted radius for display
     */
    public function getFormattedRadiusAttribute()
    {
        return LocationService::formatDistance($this->radius_absensi);
    }

    /**
     * Get Google Maps URL untuk lokasi kantor
     */
    public function getGoogleMapsUrlAttribute()
    {
        //2//
        if ($this->hasLocation()) {
            return LocationService::getGoogleMapsUrl($this->latitude, $this->longitude);
        }
        return null;
        
    }

    /**
     * Get alamat dari koordinat (jika alamat kosong)
     */
    public function getLocationAddressAttribute()
    {
                //3///
        if ($this->alamat) {
            return $this->alamat;
        }

        if ($this->hasLocation()) {
            return LocationService::getAddressFromCoordinates($this->latitude, $this->longitude);
        }

        return 'Alamat belum diset';
       
    }

    /**
     * Validasi setting lokasi
     */
    public function validateLocationSettings()
    {
         {
        //4///
        $errors = [];

        if ($this->enable_location_check) {
            if (!$this->hasLocation()) {
                $errors[] = 'Koordinat kantor belum diset';
            }

            if ($this->radius_absensi <= 0) {
                $errors[] = 'Radius absensi harus lebih dari 0';
            }

            if ($this->radius_absensi > 10000) {
                $errors[] = 'Radius absensi maksimal 10 km';
            }
        }

        return $errors;
    }
    }
}
