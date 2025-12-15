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
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prototype_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');


            $table->boolean('success'); // berhasil / gagal
            $table->integer('quantity')->nullable(); // contoh: 4x aktivitas
            $table->timestamp('logged_at'); // waktu check-in
            $table->timestamps();
            $table->unique(['prototype_id', 'logged_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};
