<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaranModel extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'nama_mapel',
    ];

    /* =========================
     | RELATIONSHIPS
     ========================= */

    // 1 mapel bisa punya banyak ujian
    public function ujian()
    {
        return $this->hasMany(UjianModel::class, 'mata_pelajaran_id');
    }

    // 1 mapel bisa diajar oleh banyak guru
    public function guru()
    {
        return $this->belongsToMany(
            User::class,
            'guru_mapel',
            'mata_pelajaran_id',
            'user_id'
        );
    }

    /* =========================
     | SCOPES
     ========================= */

    // Cari mapel berdasarkan nama
    public function scopeSearch($query, string $keyword)
    {
        return $query->where('nama_mapel', 'like', "%{$keyword}%");
    }

    /* =========================
     | HELPERS
     ========================= */

    // Ambil nama mapel dengan aman
    public function getNamaAttribute(): string
    {
        return $this->nama_mapel;
    }
}
