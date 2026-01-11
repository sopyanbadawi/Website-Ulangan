<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasModel extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_kelas',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // Semua user di kelas
    public function users()
    {
        return $this->hasMany(User::class, 'kelas_id');
    }

    // Hanya siswa di kelas
    public function siswa()
    {
        return $this->hasMany(User::class, 'kelas_id')
            ->whereHas('role', function ($q) {
                $q->where('name', 'siswa');
            });
    }

    // Riwayat perpindahan kelas
    public function kelasHistories()
    {
        return $this->hasMany(KelasHistoryModel::class, 'kelas_id');
    }

    // Relasi ujian ↔ kelas
    public function ujian()
    {
        return $this->belongsToMany(
            UjianModel::class,
            'ujian_kelas',
            'kelas_id',
            'ujian_id'
        )->withTimestamps();
    }

    public static function totalKelas(){
        return self::count();
    }
}
