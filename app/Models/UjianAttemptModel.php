<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianAttemptModel extends Model
{
    use HasFactory;

    protected $table = 'ujian_attempt';

    protected $fillable = [
        'ujian_id',
        'user_id',
        'kelas_id',
        'nisn',
        'final_score',
        'ip_address',
        'status',
    ];

    protected $casts = [
        'final_score' => 'decimal:2',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // Relasi ke Ujian
    public function ujian()
    {
        return $this->belongsTo(UjianModel::class, 'ujian_id');
    }

    // Relasi ke User (Peserta)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'kelas_id');
    }

    // Jawaban siswa untuk attempt ini
    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswaModel::class, 'ujian_attempt_id');
    }

    /* =========================
     | SCOPES
     ========================= */

    // Attempt yang sedang berlangsung
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    // Attempt yang sudah selesai
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    // Attempt terkunci (indikasi pelanggaran)
    public function scopeLocked($query)
    {
        return $query->where('status', 'lock');
    }

    // Attempt berdasarkan ujian
    public function scopeByUjian($query, $ujianId)
    {
        return $query->where('ujian_id', $ujianId);
    }

    // Attempt berdasarkan user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /* =========================
     | HELPERS (BUSINESS LOGIC)
     ========================= */

    // Apakah ujian masih berjalan
    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    // Apakah ujian sudah selesai
    public function isFinished(): bool
    {
        return $this->status === 'selesai';
    }

    // Apakah attempt terkunci
    public function isLocked(): bool
    {
        return $this->status === 'lock';
    }

    // Kunci attempt (misal ganti tab / IP berubah)
    public function lockAttempt(): void
    {
        $this->update(['status' => 'lock']);
    }

    // Selesaikan ujian
    public function finish(float $score): void
    {
        $this->update([
            'final_score' => $score,
            'status' => 'selesai',
        ]);
    }
}
