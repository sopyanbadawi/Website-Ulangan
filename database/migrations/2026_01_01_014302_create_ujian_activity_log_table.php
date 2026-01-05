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
        Schema::create('ujian_activity_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_attempt_id');
            $table->string('event');
            $table->string('detail')->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent');

            // INDEX
            $table->index('ujian_attempt_id');
            $table->index('event');
            $table->index('created_at');
            $table->index(['ujian_attempt_id', 'event']);

            // FOREIGN KEY
            $table->foreign('ujian_attempt_id')
                ->references('id')
                ->on('ujian_attempt')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_activity_log');
    }
};
