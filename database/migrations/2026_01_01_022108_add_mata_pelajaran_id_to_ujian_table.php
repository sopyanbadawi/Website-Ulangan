<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->unsignedBigInteger('mata_pelajaran_id')
                  ->after('tahun_ajaran_id');

            // INDEX
            $table->index('mata_pelajaran_id');

            // FOREIGN KEY
            $table->foreign('mata_pelajaran_id')
                  ->references('id')
                  ->on('mata_pelajaran')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
            $table->dropIndex(['mata_pelajaran_id']);
            $table->dropColumn('mata_pelajaran_id');
        });
    }
};
