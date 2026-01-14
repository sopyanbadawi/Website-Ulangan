<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UjianModel extends Model
{
    use HasFactory;

    protected $table = 'ujian';

    protected $fillable = [
        'created_by',
        'tahun_ajaran_id',
        'mata_pelajaran_id',
        'nama_ujian',
        'mulai_ujian',
        'selesai_ujian',
        'durasi',
        'status',
    ];

    protected $casts = [
        'mulai_ujian'   => 'datetime',
        'selesai_ujian' => 'datetime',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    public function ipWhitelist()
    {
        return $this->hasMany(UjianIpWhitelist::class, 'ujian_id');
    }

    // Guru pembuat ujian
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Tahun ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaranModel::class, 'tahun_ajaran_id');
    }

    // Mata pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaranModel::class, 'mata_pelajaran_id');
    }

    // Kelas peserta ujian
    public function kelas()
    {
        return $this->belongsToMany(
            KelasModel::class,
            'ujian_kelas',
            'ujian_id',
            'kelas_id'
        )->withTimestamps();
    }

    // Soal ujian
    public function soal()
    {
        return $this->hasMany(SoalModel::class, 'ujian_id');
    }

    // Attempt siswa
    public function attempts()
    {
        return $this->hasMany(UjianAttemptModel::class, 'ujian_id');
    }

    /* =========================
     | SCOPES
     ========================= */

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeByCreator($query, int $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function scopeSedangBerjalan($query)
    {
        return $query->where('status', 'aktif')
            ->where('mulai_ujian', '<=', now())
            ->where('selesai_ujian', '>=', now());
    }

    /* =========================
     | STATUS HELPERS
     ========================= */

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }

    /* =========================
     | LOGIC UJIAN
     ========================= */

    // Apakah ujian sedang bisa dikerjakan
    public function isOpen(): bool
    {
        return $this->status === 'aktif'
            && now()->between($this->mulai_ujian, $this->selesai_ujian);
    }

    // Sisa waktu ujian (DETIK – lebih akurat CBT)
    public function sisaWaktuDetik(): int
    {
        if (now()->greaterThan($this->selesai_ujian)) {
            return 0;
        }

        return now()->diffInSeconds($this->selesai_ujian);
    }

    // 🔥 AUTO UPDATE STATUS (INI KUNCI UTAMA)
    public function updateStatusIfNeeded(): void
    {
        $now = now();

        if ($this->status === 'draft' && $now->gte($this->mulai_ujian)) {
            $this->update(['status' => 'aktif']);
        }

        if ($this->status === 'aktif' && $now->gt($this->selesai_ujian)) {
            $this->update(['status' => 'selesai']);
        }
    }


    public static function totalUjian(): int
    {
        return self::count();
    }

    public static function totalUjianAktif(): int
    {
        return self::aktif()->count();
    }

    public static function totalKelas(): int
    {
        return self::with('kelas')
            ->get()
            ->pluck('kelas')
            ->flatten()
            ->unique('id')
            ->count();
    }

    public static function totalSiswa(): int
    {
        return self::with('kelas.siswa')
            ->get()
            ->pluck('kelas')
            ->flatten()
            ->pluck('siswa')
            ->flatten()
            ->unique('id')
            ->count();
    }

    public static function totalSiswaForGuru()
    {
        $guruId = auth()->id();
        return User::whereHas('kelas.ujian', function($q) use ($guruId) {
            $q->where('ujian.created_by', $guruId);
        })->whereHas('role', function($q) {
            $q->where('name', 'siswa');
        })->count();
    }
    

    public static function totalKelasForGuru()
    {
        $guruId = auth()->id();
        return KelasModel::whereHas('ujian', function($q) use ($guruId) {
            $q->where('ujian.created_by', $guruId);
        })->count();
    }

    public static function totalSiswaSudahSubmit()
    {
        $guruId = auth()->id();
        return UjianAttemptModel::where('status', 'selesai')
            ->whereHas('ujian', function($q) use ($guruId) {
                $q->where('user_id', $guruId);
            })->count();
    }
}
