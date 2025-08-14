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
        Schema::create('balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['credit', 'debit', 'initial'])->comment('نوع المعاملة: إضافة، خصم، رصيد ابتدائي');
            $table->decimal('amount', 15, 2)->comment('المبلغ');
            $table->string('description')->comment('وصف المعاملة');
            $table->decimal('balance_before', 15, 2)->comment('الرصيد قبل المعاملة');
            $table->decimal('balance_after', 15, 2)->comment('الرصيد بعد المعاملة');
            $table->string('reference_type')->nullable()->comment('نوع المرجع المرتبط');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('معرف المرجع المرتبط');
            $table->json('metadata')->nullable()->comment('بيانات إضافية');
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_transactions');
    }
};
