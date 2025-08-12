<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('electronic_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('wallet_name');
            $table->string('wallet_type');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('electronic_wallets');
    }
};
