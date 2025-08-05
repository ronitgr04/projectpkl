<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $fillable = [
        'user_id',        // Mahasiswa ID
        'tanggal',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kegiatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
