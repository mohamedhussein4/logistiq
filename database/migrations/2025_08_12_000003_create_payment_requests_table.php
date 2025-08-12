<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('request_number')->unique();
            $table->enum('payment_type', ['product_order', 'invoice', 'funding_request', 'other']);
            $table->unsignedBigInteger('related_id');
            $table->string('related_type');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['bank_transfer', 'electronic_wallet', 'cash', 'check']);
            $table->unsignedBigInteger('payment_account_id')->nullable();
            $table->string('payment_account_type')->nullable();
            $table->text('payment_notes')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['payment_type', 'related_id']);
            $table->index('request_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_requests');
    }
};
