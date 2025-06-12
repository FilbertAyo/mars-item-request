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
            $table->unsignedBigInteger('replenishment_id')->nullable()->after('status');

            // Optional: add foreign key if you want relational integrity
            $table->foreign('replenishment_id')->references('id')->on('replenishments')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petties', function (Blueprint $table) {
            $table->dropForeign(['replenishment_id']);
            $table->dropColumn('replenishment_id');
        });
    }
};
