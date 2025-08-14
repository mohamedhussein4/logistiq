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
        Schema::table('users', function (Blueprint $table) {
            // إضافة حقول الرصيد للشركات اللوجستية فقط
            $table->decimal('available_balance', 15, 2)->default(0)->comment('الرصيد المتاح');
            $table->decimal('used_balance', 15, 2)->default(0)->comment('الرصيد المستخدم');
            $table->decimal('total_balance', 15, 2)->default(0)->comment('إجمالي الرصيد');

            $table->index('available_balance');
            $table->index(['user_type', 'available_balance']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['available_balance']);
            $table->dropIndex(['user_type', 'available_balance']);

            $table->dropColumn([
                'available_balance',
                'used_balance',
                'total_balance'
            ]);
        });
    }
};
