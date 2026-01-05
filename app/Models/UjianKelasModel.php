<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianKelasModel extends Model
{
    use HasFactory;

    protected $table = 'ujian_kelas';

    protected $fillable = [
        'ujian_id',
        'kelas_id',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // Relasi ke ujian
    public function ujian()
    {
        return $this->belongsTo(UjianModel::class, 'ujian_id');
    }

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'kelas_id');
    }
}
