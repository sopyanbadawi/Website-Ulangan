<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaranModel extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun',
        'semester',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // Relasi ke ujian
    public function ujian()
    {
        return $this->hasMany(UjianModel::class, 'tahun_ajaran_id');
    }

    // Relasi ke riwayat kelas siswa
    public function kelasHistories()
    {
        return $this->hasMany(KelasHistoryModel::class, 'tahun_ajaran_id');
    }

    /* =========================
     | SCOPES (SANGAT DISARANKAN)
     ========================= */

    // Tahun ajaran aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Filter semester
    public function scopeSemester($query, string $semester)
    {
        return $query->where('semester', $semester);
    }

    // Filter tahun
    public function scopeTahun($query, string $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /* =========================
     | HELPERS
     ========================= */

    // Ambil satu tahun ajaran aktif
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
}
