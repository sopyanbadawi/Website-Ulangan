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
        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_attempt_id');
            $table->unsignedBigInteger('soal_id');
            $table->unsignedBigInteger('opsi_id')->nullable();
            $table->decimal('skor', 5, 2)->default(0);

            // INDEX & CONSTRAINT
            $table->index('ujian_attempt_id');
            $table->index('soal_id');
            $table->index('opsi_id');
            $table->unique(['ujian_attempt_id', 'soal_id']);
            $table->index(['soal_id', 'opsi_id']);

            // FOREIGN KEY
            $table->foreign('ujian_attempt_id')->references('id')->on('ujian_attempt')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('soal')->onDelete('cascade');
            $table->foreign('opsi_id')->references('id')->on('opsi_jawaban')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_siswa');
    }
};
