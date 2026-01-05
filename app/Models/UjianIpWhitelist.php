<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianIpWhitelist extends Model
{
    use HasFactory;

    protected $table = 'ujian_ip_whitelist';

    protected $fillable = [
        'ujian_id',
        'ip_address',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // Whitelist IP ini milik satu ujian
    public function ujian()
    {
        return $this->belongsTo(UjianModel::class, 'ujian_id');
    }

    /* =========================
     | SCOPES (OPTIMIZED)
     ========================= */

    // Ambil whitelist berdasarkan ujian
    public function scopeByUjian($query, int $ujianId)
    {
        return $query->where('ujian_id', $ujianId);
    }

    // Cek apakah IP diizinkan untuk ujian tertentu
    public function scopeAllowIp($query, int $ujianId, string $ip)
    {
        return $query->where('ujian_id', $ujianId)
                     ->where('ip_address', $ip);
    }

    /* =========================
     | BUSINESS HELPERS
     ========================= */

    /**
     * Cek IP diizinkan atau tidak
     */
    public static function isAllowed(int $ujianId, string $ip): bool
    {
        return self::where('ujian_id', $ujianId)
            ->where('ip_address', $ip)
            ->exists();
    }

    /**
     * Tambahkan IP ke whitelist (aman dari duplikat)
     */
    public static function addIp(int $ujianId, string $ip): self
    {
        return self::firstOrCreate([
            'ujian_id'    => $ujianId,
            'ip_address' => $ip,
        ]);
    }

    /**
     * Hapus IP dari whitelist
     */
    public static function removeIp(int $ujianId, string $ip): int
    {
        return self::where('ujian_id', $ujianId)
            ->where('ip_address', $ip)
            ->delete();
    }
}
