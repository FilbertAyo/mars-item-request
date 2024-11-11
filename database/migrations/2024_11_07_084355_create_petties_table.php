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
        Schema::create('petties', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->text('reason');
            $table->string('request_by');
            $table->string('status');
            $table->text('comment')->nullable();
            $table->enum('request_type', ['Petty Cash', 'Reimbursement']);
            $table->string('attachment')->nullable(); // Path to the receipt file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petties');
    }
};
