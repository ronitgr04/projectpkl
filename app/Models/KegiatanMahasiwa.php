<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KegiatanMahasiwa extends Model
{
    use HasFactory;

    protected $table = 'tabel_kegiatan';

    protected $fillable = [
        'mahasiswa_id',
        'hari',
        'tanggal',
        'jam',
        'kegiatan',
        'foto_dokumentasi'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    // Accessor untuk format tanggal Indonesia
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->format('d M Y') : '-';
    }

    // Accessor untuk format hari Indonesia
    public function getFormattedHariAttribute()
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];

        return $days[$this->hari] ?? $this->hari;
    }

    // Accessor untuk URL foto
    public function getFotoUrlAttribute()
    {
        return $this->foto_dokumentasi ? asset('storage/' . $this->foto_dokumentasi) : null;
    }

    // Scope untuk filter berdasarkan mahasiswa
    public function scopeByMahasiswa($query, $mahasiswaId)
    {
        return $query->where('mahasiswa_id', $mahasiswaId);
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    // Scope untuk filter berdasarkan bulan
    public function scopeByMonth($query, $month, $year = null)
    {
        $year = $year ?: now()->year;
        return $query->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
    }

    // Scope untuk kegiatan terbaru
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal', 'desc')->orderBy('jam', 'desc');
    }
}
