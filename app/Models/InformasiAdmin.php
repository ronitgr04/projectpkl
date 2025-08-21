<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiAdmin extends Model
{
    use HasFactory;

    protected $table = 'tabel_informasi_admin';
    protected $primaryKey = 'id_informasi_admin';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'email',
        'no_telepon',
        'alamat',
        'foto_profil',
        'tanggal_lahir',
        'jenis_kelamin',
        'jabatan',
        'bio',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Get full profile photo URL
     */
    public function getFotoProfilUrlAttribute()
    {
        if ($this->foto_profil) {
            return asset('storage/admin/foto_profil/' . $this->foto_profil);
        }

        // Default avatar based on gender
        if ($this->jenis_kelamin === 'Perempuan') {
            return asset('images/default-avatar-female.png');
        }

        return asset('images/default-avatar-male.png');
    }

    /**
     * Get age from birth date
     */
    public function getUmurAttribute()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }

        return $this->tanggal_lahir->age;
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->no_telepon) {
            return null;
        }

        // Format Indonesian phone number
        $phone = preg_replace('/[^0-9]/', '', $this->no_telepon);

        if (substr($phone, 0, 1) === '0') {
            return '+62' . substr($phone, 1);
        }

        return $phone;
    }
}