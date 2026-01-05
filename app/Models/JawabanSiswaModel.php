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

    // Attempt ujian (1 attempt banyak jawaban)
    public function attempt()
    {
        return $this->belongsTo(UjianAttemptModel::class, 'ujian_attempt_id');
    }

    // Soal yang dijawab
    public function soal()
    {
        return $this->belongsTo(SoalModel::class, 'soal_id');
    }

    // Opsi jawaban yang dipilih (nullable)
    public function opsi()
    {
        return $this->belongsTo(OpsiJawabanModel::class, 'opsi_id');
    }

    /* =========================
     | SCOPES
     ========================= */

    // Jawaban untuk attempt tertentu
    public function scopeByAttempt($query, $attemptId)
    {
        return $query->where('ujian_attempt_id', $attemptId);
    }

    // Jawaban benar
    public function scopeCorrect($query)
    {
        return $query->where('skor', '>', 0);
    }

    // Jawaban salah
    public function scopeWrong($query)
    {
        return $query->where('skor', 0);
    }

    /* =========================
     | BUSINESS LOGIC
     ========================= */

    /**
     * Set jawaban siswa + auto scoring
     */
    public function submitAnswer(?int $opsiId): void
    {
        $this->opsi_id = $opsiId;

        if (!$opsiId) {
            $this->skor = 0;
            $this->save();
            return;
        }

        $opsi = OpsiJawabanModel::find($opsiId);

        if (!$opsi) {
            $this->skor = 0;
            $this->save();
            return;
        }

        // Jika jawaban benar → skor = bobot soal
        $this->skor = $opsi->is_correct
            ? $this->soal->bobot
            : 0;

        $this->save();
    }

    /**
     * Cek apakah jawaban benar
     */
    public function isCorrect(): bool
    {
        return $this->skor > 0;
    }

    /**
     * Reset jawaban (jika ganti opsi)
     */
    public function resetAnswer(): void
    {
        $this->update([
            'opsi_id' => null,
            'skor' => 0,
        ]);
    }
}
