<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    public function generateZeroAttempts(): void
    {
        // Ambil kelas peserta ujian
        $kelasIds = $this->kelas()->pluck('kelas_id');

        if ($kelasIds->isEmpty()) {
            return;
        }

        // Ambil SEMUA siswa yg SAAT INI ada di kelas ujian
        $siswa = User::whereIn('kelas_id', $kelasIds)
            ->whereHas('role', fn($q) => $q->where('name', 'siswa'))
            ->get();

        // logger('Generate zero attempts', [
        //     'ujian_id' => $this->id,
        //     'kelas' => $kelasIds,
        //     'jumlah_siswa' => $siswa->count(),
        // ]);

        foreach ($siswa as $user) {

            // cek kalo udah ada
            $exists = UjianAttemptModel::where('ujian_id', $this->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($exists) {
                continue;
            }

            // Buat attempt NILAI 0 (TIDAK MENGERJAKAN)
            UjianAttemptModel::create([
                'ujian_id'    => $this->id,
                'user_id'     => $user->id,
                'kelas_id'    => $user->kelas_id,
                'nisn'        => $user->username,
                'final_score' => 0,
                'status'      => 'selesai',
                'ip_address'  => null,
            ]);
        }
    }

    // AUTO UPDATE STATUS 
    public function updateStatusIfNeeded(): void
    {
        $now = now();

        // draft → aktif
        if ($this->status === 'draft' && $now->gte($this->mulai_ujian)) {
            $this->update(['status' => 'aktif']);
        }

        // aktif → selesai
        if ($this->status === 'aktif' && $now->gt($this->selesai_ujian)) {

            DB::transaction(function () {

                $this->update(['status' => 'selesai']);

                $this->generateZeroAttempts();
            });
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
}
