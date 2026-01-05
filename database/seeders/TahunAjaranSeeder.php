<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tahun_ajaran')->insert([
            [
                'tahun' => '2025/2026',
                'semester' => 'Ganjil',
                'is_active' => true,
            ],
            [
                'tahun' => '2025/2026',
                'semester' => 'Genap',
                'is_active' => false,
            ],
        ]);
    }
}
