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
                   $table->boolean('is_transporter')->default(false)->after('request_type'); // Replace with appropriate column

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petties', function (Blueprint $table) {
                  $table->dropColumn('is_transporter');
        });
    }
};
