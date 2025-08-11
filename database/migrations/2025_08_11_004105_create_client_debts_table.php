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
        Schema::create('client_debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('funding_request_id');
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->string('invoice_document')->nullable();
            $table->enum('status', ['pending', 'account_created', 'invoice_sent', 'paid'])->default('pending');
            $table->unsignedBigInteger('created_user_id')->nullable();
            $table->unsignedBigInteger('created_invoice_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('funding_request_id')->references('id')->on('funding_requests')->onDelete('cascade');
            $table->foreign('created_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_invoice_id')->references('id')->on('invoices')->onDelete('set null');

            // Indexes
            $table->index(['funding_request_id', 'status']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_debts');
    }
};
