<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasModel extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    Protected $primaryKey = 'id';

    protected $fillable = [
        'nama_kelas',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // User (siswa) yang saat ini berada di kelas ini
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Riwayat perpindahan kelas siswa
    public function kelasHistories()
    {
        return $this->hasMany(KelasHistoryModel::class);
    }

    // Ujian yang diikuti oleh kelas ini
    public function ujian()
    {
        return $this->belongsToMany(
            UjianModel::class,
            'ujian_kelas',
            'kelas_id',
            'ujian_id'
        )->withTimestamps();
    }
}
