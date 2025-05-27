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
        Schema::create('petty_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petty_id')->constrained()->onDelete('cascade');
            $table->string('item_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_lists');
    }
};
