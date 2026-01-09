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

    // Relasi ke Ujian
    public function ujian()
    {
        return $this->belongsTo(UjianModel::class, 'ujian_id');
    }

    // Ambil whitelist berdasarkan ujian
    public function scopeByUjian($query, int $ujianId)
    {
        return $query->where('ujian_id', $ujianId);
    }

    /**
     * Cek apakah IP diizinkan (IP tunggal atau CIDR)
     */
    public static function isAllowed(int $ujianId, string $ip): bool
    {
        $whitelists = self::where('ujian_id', $ujianId)->get('ip_address');

        $ipDec = ip2long($ip);
        if ($ipDec === false) return false;

        foreach ($whitelists as $w) {
            $range = $w->ip_address;

            // CIDR
            if (str_contains($range, '/')) {
                [$subnet, $bits] = explode('/', $range);
                $subnetDec = ip2long($subnet);
                $mask = ~((1 << (32 - (int)$bits)) - 1);

                if (($ipDec & $mask) === ($subnetDec & $mask)) {
                    return true;
                }
            } else {
                // IP tunggal
                if ($ip === $range) return true;
            }
        }

        return false;
    }

    /**
     * Tambahkan IP (single atau CIDR)
     */
    public static function addIp(int $ujianId, string $ip): self
    {
        return self::firstOrCreate([
            'ujian_id'    => $ujianId,
            'ip_address' => $ip,
        ]);
    }

    public static function removeIp(int $ujianId, string $ip): int
    {
        return self::where('ujian_id', $ujianId)
            ->where('ip_address', $ip)
            ->delete();
    }
}
