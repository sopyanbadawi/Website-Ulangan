<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpsiJawabanModel extends Model
{
    use HasFactory;

    protected $table = 'opsi_jawaban';

    protected $fillable = [
        'soal_id',
        'opsi',
        'opsi_gambar',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // Soal induk
    public function soal()
    {
        return $this->belongsTo(SoalModel::class, 'soal_id');
    }

    // Jawaban siswa yang memilih opsi ini
    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswaModel::class, 'opsi_id');
    }

    /* =========================
     | SCOPES
     ========================= */

    // Ambil opsi benar
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    // Ambil opsi salah
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    /* =========================
     | HELPERS
     ========================= */

    // Cek apakah opsi ini jawaban benar
    public function isCorrect(): bool
    {
        return $this->is_correct === true;
    }
}
