<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'default_logistics_balance',
                'value' => '200000.00',
                'type' => 'decimal',
                'group' => 'balance',
                'label' => 'الرصيد الافتراضي للشركات اللوجستية',
                'description' => 'الرصيد الذي سيتم منحه تلقائياً لأي شركة لوجستية جديدة عند التسجيل',
                'is_public' => false,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('key', 'default_logistics_balance')->delete();
    }
};
