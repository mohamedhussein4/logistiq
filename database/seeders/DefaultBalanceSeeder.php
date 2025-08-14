<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;

class DefaultBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultBalance = Setting::get('default_logistics_balance', 200000);

        // منح الرصيد الافتراضي للشركات اللوجستية الموجودة التي لا تملك رصيد
        User::where('user_type', 'logistics')
            ->where('total_balance', 0)
            ->each(function ($user) use ($defaultBalance) {
                $user->setDefaultBalance();
                $this->command->info("تم منح رصيد افتراضي للشركة: {$user->name}");
            });
    }
}
