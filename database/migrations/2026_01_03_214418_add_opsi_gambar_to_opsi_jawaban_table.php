<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('opsi_jawaban', function (Blueprint $table) {
            $table->string('opsi_gambar')
                  ->nullable()
                  ->after('opsi');
        });
    }

    public function down(): void
    {
        Schema::table('opsi_jawaban', function (Blueprint $table) {
            $table->dropColumn('opsi_gambar');
        });
    }
};
