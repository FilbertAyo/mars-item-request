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
        Schema::create('catalogue_files', function (Blueprint $table) {
            $table->id();
              $table->unsignedBigInteger('user_id');
            $table->foreignId('catalogue_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->enum('status', ['visible', 'expired'])->default('expired');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogue_files');
    }
};
