<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('region')->nullable()->after('name'); // nullable so existing rows are okay
            $table->text('location_url')->nullable()->after('region');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('location_url');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['region', 'location_url', 'status']);
        });
    }
};
