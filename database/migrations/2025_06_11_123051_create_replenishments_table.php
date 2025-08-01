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
        Schema::create('replenishments', function (Blueprint $table) {
            $table->id();
            $table->date('from');
            $table->date('to');
            $table->decimal('total_amount', 15, 2);
            $table->text('description');
            $table->enum('status', ['pending','processing','approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replenishments');
    }
};
