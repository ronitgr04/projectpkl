<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'tabel_profil_mahasiswa';

    protected $fillable = [
        'mahasiswa_id',
        'no_telp',
        'alamat',
        'jurusan',
        'tanggal_lahir',
        'jenis_kelamin', 
        'nama_pembimbing',
        'no_telp_pembimbing',
        'email_pembimbing',
        'deskripsi_diri',
        'skills',
        'instagram',
        'linkedin',
        'github'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'skills' => 'array'
    ];

    /**
     * Relationship dengan Mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    /**
     * Accessor untuk format tanggal lahir Indonesia
     */
    public function getTanggalLahirFormattedAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->format('d-m-Y') : null;
    }

    /**
     * Accessor untuk jenis kelamin lengkap
     */
    public function getJenisKelaminLengkapAttribute()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : ($this->jenis_kelamin === 'P' ? 'Perempuan' : null);
    }

    /**
     * Accessor untuk umur
     */
    public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->diffInYears(now()) : null;
    }

    /**
     * Accessor untuk skills sebagai string
     */
    public function getSkillsStringAttribute()
    {
        return $this->skills ? implode(', ', $this->skills) : '';
    }

    /**
     * Mutator untuk skills
     */
    public function setSkillsAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['skills'] = json_encode(array_map('trim', explode(',', $value)));
        } elseif (is_array($value)) {
            $this->attributes['skills'] = json_encode($value);
        }
    }

    /**
     * Check if profile is complete
     */
    public function isComplete()
    {
        $requiredFields = ['no_telp', 'alamat', 'jurusan', 'tanggal_lahir', 'jenis_kelamin'];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentage()
    {
        $fields = [
            'no_telp', 'alamat', 'jurusan', 'tanggal_lahir', 'jenis_kelamin',
            'nama_pembimbing', 'deskripsi_diri', 'skills', 'instagram', 'linkedin', 'github'
        ];

        $completed = 0;
        $total = count($fields);

        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return round(($completed / $total) * 100);
    }
}