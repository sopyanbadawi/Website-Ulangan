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
        Schema::create('ujian_ip_whitelist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->string('ip_address', 45);

            // INDEX & CONSTRAINT
            $table->index('ujian_id');
            $table->index('ip_address');
            $table->unique(['ujian_id', 'ip_address']);

            // FOREIGN KEY
            $table->foreign('ujian_id')
                ->references('id')
                ->on('ujian')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_ip_whitelist');
    }
};
