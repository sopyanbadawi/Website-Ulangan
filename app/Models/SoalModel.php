<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalModel extends Model
{
    use HasFactory;

    protected $table = 'soal';

    protected $fillable = [
        'ujian_id',
        'pertanyaan',
        'pertanyaan_gambar',
        'bobot',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // Ujian induk
    public function ujian()
    {
        return $this->belongsTo(UjianModel::class, 'ujian_id');
    }

    // Opsi jawaban (PG)
    public function opsiJawaban()
    {
        return $this->hasMany(OpsiJawabanModel::class, 'soal_id');
    }

    // Jawaban siswa
    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswaModel::class, 'soal_id');
    }

    /* =========================
     | SCOPES
     ========================= */

    // Filter soal per ujian
    public function scopeByUjian($query, int $ujianId)
    {
        return $query->where('ujian_id', $ujianId);
    }

    /* =========================
     | HELPERS
     ========================= */

    // Total bobot soal per ujian
    public static function totalBobot(int $ujianId): int
    {
        return self::where('ujian_id', $ujianId)->sum('bobot');
    }
}
