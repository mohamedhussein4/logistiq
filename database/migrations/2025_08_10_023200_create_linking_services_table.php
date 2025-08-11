<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('linking_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logistics_company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_company_id')->constrained()->cascadeOnDelete();
            $table->enum('service_type', ['financing', 'logistics', 'warehousing', 'distribution']);
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])
                  ->default('pending');
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('commission', 15, 2)->nullable();
            $table->timestamp('linked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('linking_services');
    }
};
