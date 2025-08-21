<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'tabel_mahasiswa';
    protected $fillable = [
        'nama',
        'universitas',
        'nim',
        'mulai_magang',
        'akhir_magang',
        'foto',
        'user_id' // Add this field to link with User
    ];

    protected $dates = [
        'mulai_magang',
        'akhir_magang'
    ];

    protected $casts = [
        'mulai_magang' => 'date',
        'akhir_magang' => 'date'
    ];

    /**
     * Relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Check if mahasiswa has user account
     */
    public function hasUserAccount()
    {
        return !is_null($this->user_id) && $this->user()->exists();
    }
    

    // Accessor untuk format tanggal Indonesia
    public function getMulaiMagangFormattedAttribute()
    {
        return $this->mulai_magang ? $this->mulai_magang->format('d-m-Y') : null;
    }

    public function getAkhirMagangFormattedAttribute()
    {
        return $this->akhir_magang ? $this->akhir_magang->format('d-m-Y') : null;
    }

    // Accessor untuk URL foto
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            $path = storage_path('app/public/mahasiswa_photos/' . $this->foto);
            if (file_exists($path)) {
                return asset('storage/mahasiswa_photos/' . $this->foto);
            }
        }
        return asset('images/default-profile.png');
    }

    /**
     * Get status magang
     */
    public function getStatusMagangAttribute()
    {
        $now = now();
        if ($now->lt($this->mulai_magang)) {
            return 'belum_mulai';
        } elseif ($now->between($this->mulai_magang, $this->akhir_magang)) {
            return 'sedang_magang';
        } else {
            return 'selesai';
        }
    }

    /**
     * Get durasi magang in days
     */
    public function getDurasiMagangAttribute()
    {
        return $this->mulai_magang->diffInDays($this->akhir_magang);
    }
}
