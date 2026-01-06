<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSiswaModel extends Model
{
    use HasFactory;

    protected $table = 'jawaban_siswa';

    protected $fillable = [
        'ujian_attempt_id',
        'soal_id',
        'opsi_id',
        'skor',
    ];

    protected $casts = [
        'skor' => 'decimal:2',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    public function attempt()
    {
        return $this->belongsTo(UjianAttemptModel::class, 'ujian_attempt_id');
    }

    public function soal()
    {
        return $this->belongsTo(SoalModel::class, 'soal_id');
    }

    public function opsi()
    {
        return $this->belongsTo(OpsiJawabanModel::class, 'opsi_id');
    }

    /* =========================
     | BUSINESS LOGIC
     ========================= */

    /**
     * Set jawaban siswa + auto scoring (FINAL)
     */
    public function submitAnswer(?int $opsiId): void
    {
        // Simpan opsi_id dulu
        $this->opsi_id = $opsiId;

        // Jika tidak memilih opsi
        if (!$opsiId) {
            $this->skor = 0;
            $this->save();
            return;
        }

        // Ambil opsi via RELATION (AMAN & KONSISTEN)
        $opsi = $this->opsi()->first();

        // Jika opsi tidak valid / tidak satu soal
        if (!$opsi || $opsi->soal_id !== $this->soal_id) {
            $this->skor = 0;
            $this->save();
            return;
        }

        // Jika benar → skor = bobot soal
        $this->skor = $opsi->is_correct
            ? $this->soal->bobot
            : 0;

        $this->save();
    }

    /**
     * Apakah jawaban benar
     */
    public function isCorrect(): bool
    {
        return $this->skor > 0;
    }

    /**
     * Reset jawaban
     */
    public function resetAnswer(): void
    {
        $this->update([
            'opsi_id' => null,
            'skor'    => 0,
        ]);
    }
}
