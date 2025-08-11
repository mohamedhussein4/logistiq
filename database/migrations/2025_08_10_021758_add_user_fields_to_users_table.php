<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['admin', 'logistics', 'service_company', 'regular'])
                  ->default('regular')
                  ->after('email_verified_at');
            $table->string('phone')->nullable()->after('user_type');
            $table->string('company_name')->nullable()->after('phone');
            $table->string('company_registration')->nullable()->after('company_name');
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])
                  ->default('pending')
                  ->after('company_registration');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type',
                'phone',
                'company_name',
                'company_registration',
                'status'
            ]);
        });
    }
};
