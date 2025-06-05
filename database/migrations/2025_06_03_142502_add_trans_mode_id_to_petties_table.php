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
        Schema::table('petties', function (Blueprint $table) {
            $table->unsignedBigInteger('trans_mode_id')->nullable()->after('user_id');
            $table->foreign('trans_mode_id')->references('id')->on('trans_modes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petties', function (Blueprint $table) {
            //
        });
    }
};
