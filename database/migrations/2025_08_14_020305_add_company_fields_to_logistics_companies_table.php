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
        Schema::table('logistics_companies', function (Blueprint $table) {
            $table->string('company_name')->after('user_id');
            $table->string('company_type')->default('transportation')->after('company_name');
            $table->string('contact_person')->after('company_type');
            $table->string('email')->after('contact_person');
            $table->string('phone')->after('email');
            $table->text('address')->after('phone');
            $table->string('commercial_register')->after('address');
            $table->decimal('credit_limit', 15, 2)->default(100000)->after('commercial_register');
            $table->decimal('used_credit', 15, 2)->default(0)->after('credit_limit');
            $table->string('status')->default('pending')->after('total_requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logistics_companies', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'company_type',
                'contact_person',
                'email',
                'phone',
                'address',
                'commercial_register',
                'credit_limit',
                'used_credit',
                'status'
            ]);
        });
    }
};
