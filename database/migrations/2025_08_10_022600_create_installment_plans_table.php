<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('installment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount', 15, 2);
            $table->integer('installment_count');
            $table->decimal('monthly_amount', 15, 2);
            $table->date('start_date');
            $table->enum('status', ['pending', 'approved', 'active', 'completed', 'cancelled'])
                  ->default('pending');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('installment_plans');
    }
};
