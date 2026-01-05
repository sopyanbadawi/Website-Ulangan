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
        Schema::create('opsi_jawaban', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('soal_id');
            $table->string('opsi');
            $table->boolean('is_correct');
            $table->timestamps();

            // INDEX & CONSTRAINT
            $table->index('soal_id');
            $table->unique(['soal_id', 'opsi']);

            // FOREIGN KEY
            $table->foreign('soal_id')
                ->references('id')
                ->on('soal')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opsi_jawaban');
    }
};
