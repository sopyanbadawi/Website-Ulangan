<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


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


    public static function distribusiNilai(
        ?int $kelasId = null,
        ?string $semester = null,
        ?int $tahunAjaranId = null
    ): array {
        $query = self::query()
            ->join('ujian', 'ujian.id', '=', 'ujian_attempt.ujian_id')
            ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'ujian.tahun_ajaran_id')
            ->where('ujian.status', 'selesai')
            ->whereNotNull('ujian_attempt.final_score');

        // Filter kelas
        if ($kelasId) {
            $query->where('ujian_attempt.kelas_id', $kelasId);
        }

        // Filter semester (ganjil / genap)
        if ($semester) {
            $query->where('tahun_ajaran.semester', $semester);
        }

        // Filter tahun ajaran (ID)
        if ($tahunAjaranId) {
            $query->where('tahun_ajaran.id', $tahunAjaranId);
        }

        return [
            '0_59' => (clone $query)->whereBetween('ujian_attempt.final_score', [0, 59])->count(),
            '60_69' => (clone $query)->whereBetween('ujian_attempt.final_score', [60, 69])->count(),
            '70_79' => (clone $query)->whereBetween('ujian_attempt.final_score', [70, 79])->count(),
            '80_100' => (clone $query)->whereBetween('ujian_attempt.final_score', [80, 100])->count(),
        ];
    }

    public static function avgSemesterFair(): array
    {
        /**
         * STEP 1
         * Hitung jumlah ujian per semester
         */
        $jumlahUjian = DB::table('ujian')
            ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'ujian.tahun_ajaran_id')
            ->where('ujian.status', 'selesai')
            ->groupBy('ujian.tahun_ajaran_id')
            ->select(
                'ujian.tahun_ajaran_id',
                DB::raw('COUNT(ujian.id) as total_ujian')
            )
            ->get();

        if ($jumlahUjian->isEmpty()) {
            return [];
        }

        // STEP 2 → Ambil jumlah TERKECIL
        $minUjian = $jumlahUjian->min('total_ujian');

        /**
         * STEP 3
         * Ambil TOP ujian per semester
         */
        $hasil = [];

        foreach ($jumlahUjian as $row) {

            $topUjian = DB::table('ujian_attempt as ua')
                ->join('ujian', 'ujian.id', '=', 'ua.ujian_id')
                ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'ujian.tahun_ajaran_id')
                ->where('ujian.tahun_ajaran_id', $row->tahun_ajaran_id)
                ->whereNotNull('ua.final_score')
                ->groupBy('ua.ujian_id', 'tahun_ajaran.tahun')
                ->select(
                    DB::raw('AVG(ua.final_score) as avg_score'),
                    'tahun_ajaran.tahun'
                )
                ->orderByDesc('avg_score')
                ->limit($minUjian)
                ->get();

            if ($topUjian->count() > 0) {
                $hasil[] = [
                    'semester' => $topUjian->first()->tahun,
                    'avg' => round($topUjian->avg('avg_score'), 2),
                ];
            }
        }

        return $hasil;
    }

    public static function getRekapUntukGuru($guruId, $tahunAjaranId = null)
    {
        $query = self::with(['ujian.mataPelajaran', 'kelas'])
            ->whereHas('ujian', function($q) use ($guruId, $tahunAjaranId) {
                // Sesuai gambar: tabel guru_mapel punya kolom mata_pelajaran_id
                $q->whereIn('mata_pelajaran_id', function($sub) use ($guruId) {
                    $sub->select('mata_pelajaran_id')
                        ->from('guru_mapel')
                        ->where('user_id', $guruId);
                });
                
                if ($tahunAjaranId) {
                    $q->where('tahun_ajaran_id', $tahunAjaranId);
                }
            })
            ->where('status', 'selesai'); // Filter hanya yang sudah selesai

        return $query->get()->groupBy([
            'ujian.mataPelajaran.nama_mapel',
            'kelas.nama_kelas'
        ]);
    }
}
