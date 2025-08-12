<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_request_id')->constrained()->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->string('file_size');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['payment_request_id', 'status']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_proofs');
    }
};
