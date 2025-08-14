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
        Schema::table('service_companies', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('user_id');
            $table->string('contact_person')->nullable()->after('company_name');
            $table->string('email')->nullable()->after('contact_person');
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('commercial_register')->nullable()->after('address');
            $table->string('tax_number')->nullable()->after('commercial_register');
            $table->string('bank_account')->nullable()->after('tax_number');
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])
                  ->default('active')
                  ->after('credit_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_companies', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'contact_person',
                'email',
                'phone',
                'address',
                'commercial_register',
                'tax_number',
                'bank_account',
                'status'
            ]);
        });
    }
};
