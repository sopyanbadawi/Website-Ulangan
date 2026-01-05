<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'kelas_id',
        'status_lock',
        'last_ip',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status_lock' => 'boolean',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // Role user (admin, guru, siswa)
    public function role()
    {
        return $this->belongsTo(RoleModel::class);
    }

    // Kelas (khusus siswa)
    public function kelas()
    {
        return $this->belongsTo(KelasModel::class);
    }

    // Riwayat ujian siswa
    public function ujianAttempts()
    {
        return $this->hasMany(UjianAttemptModel::class);
    }

    // guru pengampu mata pelajaran
    public function mapelPengampu()
{
    if ($this->role_id !== 2) { // misal 2 = guru
        return collect();
    }

    return $this->hasMany(GuruMapelModel::class, 'user_id');
}
}
