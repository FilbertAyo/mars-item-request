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
            $table->unsignedBigInteger('user_id');
              $table->unsignedBigInteger('trans_mode_id')->nullable();
            $table->foreign('trans_mode_id')->references('id')->on('trans_modes')->onDelete('set null');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('code')->nullable();
            $table->string('request_for')->nullable();
            $table->decimal('amount', 10, 2);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'processing','resubmission','resubmitted','paid'])->default('pending');
             $table->unsignedBigInteger('replenishment_id')->nullable();
            $table->foreign('replenishment_id')->references('id')->on('replenishments')->nullOnDelete();
            $table->enum('request_type', ['Petty Cash', 'Reimbursement']);
            $table->string('attachment')->nullable();
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
