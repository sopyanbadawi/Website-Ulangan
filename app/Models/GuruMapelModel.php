<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruMapelModel extends Model
{
    use HasFactory;

    protected $table = 'guru_mapel';

    protected $fillable = [
        'user_id',
        'mata_pelajaran_id',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // Relasi ke User (Guru)
    public function guru()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Mata Pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaranModel::class, 'mata_pelajaran_id');
    }

    /* =========================
     | SCOPES
     ========================= */

    // Ambil hanya data guru tertentu
    public function scopeByGuru($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Ambil hanya mapel tertentu
    public function scopeByMapel($query, int $mapelId)
    {
        return $query->where('mata_pelajaran_id', $mapelId);
    }

    /* =========================
     | HELPERS
     ========================= */

    // Cek apakah guru mengajar mapel tertentu
    public static function isGuruMengajarMapel(int $userId, int $mapelId): bool
    {
        return self::where('user_id', $userId)
            ->where('mata_pelajaran_id', $mapelId)
            ->exists();
    }
}
