<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianActivityLogModel extends Model
{
    use HasFactory;

    protected $table = 'ujian_activity_log';

    protected $fillable = [
        'ujian_attempt_id',
        'event',
        'detail',
        'ip_address',
        'user_agent',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // Log ini milik satu attempt ujian
    public function attempt()
    {
        return $this->belongsTo(UjianAttemptModel::class, 'ujian_attempt_id');
    }

    /* =========================
     | SCOPES (OPTIMIZED)
     ========================= */

    // Log berdasarkan attempt
    public function scopeByAttempt($query, $attemptId)
    {
        return $query->where('ujian_attempt_id', $attemptId);
    }

    // Log berdasarkan event
    public function scopeEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    // Log terbaru (pakai index created_at)
    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at');
    }

    /* =========================
     | BUSINESS HELPERS
     ========================= */

    /**
     * Simpan log aktivitas ujian
     */
    public static function log(
        int $attemptId,
        string $event,
        ?string $detail = null
    ): self {
        return self::create([
            'ujian_attempt_id' => $attemptId,
            'event'            => $event,
            'detail'           => $detail,
            'ip_address'       => request()->ip(),
            'user_agent'       => request()->userAgent(),
        ]);
    }

    /**
     * Shortcut event penting
     */
    public static function started(int $attemptId): self
    {
        return self::log($attemptId, 'UJIAN_DIMULAI');
    }

    public static function finished(int $attemptId): self
    {
        return self::log($attemptId, 'UJIAN_SELESAI');
    }

    public static function tabSwitch(int $attemptId): self
    {
        return self::log($attemptId, 'TAB_SWITCH');
    }

    public static function ipChanged(int $attemptId, string $oldIp, string $newIp): self
    {
        return self::log(
            $attemptId,
            'IP_CHANGED',
            "From {$oldIp} to {$newIp}"
        );
    }

    public static function autoSubmit(int $attemptId): self
    {
        return self::log($attemptId, 'AUTO_SUBMIT');
    }
}
