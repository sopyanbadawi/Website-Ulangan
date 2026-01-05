<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasHistoryModel extends Model
{
    use HasFactory;

    protected $table = 'kelas_history';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'tahun_ajaran_id',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // Siswa
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kelas
    public function kelas()
    {
        return $this->belongsTo(KelasModel::class);
    }

    // Tahun ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaranModel::class, 'tahun_ajaran_id');
    }

    /* =========================
     | SCOPES (RECOMMENDED)
     ========================= */

    // Filter by siswa
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Filter by tahun ajaran
    public function scopeByTahunAjaran($query, int $tahunAjaranId)
    {
        return $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    // Filter by kelas
    public function scopeByKelas($query, int $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }
}
