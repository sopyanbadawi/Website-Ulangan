<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('tahun_ajaran_id');
            $table->string('nama_ujian');
            $table->dateTime('mulai_ujian');
            $table->dateTime('selesai_ujian');
            $table->integer('durasi');
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');

            // INDEX
            $table->index('created_by');
            $table->index('tahun_ajaran_id');
            $table->index('status');
            $table->index('mulai_ujian');

            // FOREIGN KEY
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian');
    }
};
