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
        Schema::table('prototypes', function (Blueprint $table) {
            $table->string('type')->default('standard')->after('status'); // standard, sleep, study, checklist
            $table->json('settings')->nullable()->after('type'); // Stores target_time, checklist_items, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prototypes', function (Blueprint $table) {
            //
        });
    }
};
