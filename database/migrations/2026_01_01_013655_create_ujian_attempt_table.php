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
        Schema::create('ujian_attempt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kelas_id');
            $table->string('nisn');
            $table->decimal('final_score', 5, 2);
            $table->string('ip_address', 45);
            $table->enum('status', ['ongoing', 'selesai', 'lock']);

            // INDEX & CONSTRAINT
            $table->index('ujian_id');
            $table->index('user_id');
            $table->index('kelas_id');
            $table->index('status');
            $table->unique(['ujian_id', 'user_id']);
            $table->index(['ujian_id', 'kelas_id']);
            $table->index(['user_id', 'status']);

            // FOREIGN KEY
            $table->foreign('ujian_id')->references('id')->on('ujian')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_attempt');
    }
};
