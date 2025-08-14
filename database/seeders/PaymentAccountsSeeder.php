<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class PaymentAccountsSeeder extends Seeder
{
    public function run()
    {
        // إنشاء الحسابات البنكية
        $bankAccounts = [
            [
                'bank_name' => 'البنك الأهلي السعودي',
                'account_name' => 'Link2u للتجارة',
                'account_number' => '1234567890',
                'iban' => 'SA0380000000608010167519',
                'swift_code' => 'NCBJSARI',
                'branch_name' => 'الفرع الرئيسي - الرياض',
                'branch_code' => '001',
                'notes' => 'الحساب الرئيسي للشركة',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'bank_name' => 'بنك الراجحي',
                'account_name' => 'Link2u للتجارة',
                'account_number' => '0987654321',
                'iban' => 'SA0380000000608010167520',
                'swift_code' => 'RJHISARI',
                'branch_name' => 'فرع جدة',
                'branch_code' => '002',
                'notes' => 'حساب فرع جدة',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'bank_name' => 'بنك سامبا',
                'account_name' => 'Link2u للتجارة',
                'account_number' => '1122334455',
                'iban' => 'SA0380000000608010167521',
                'swift_code' => 'SABBSARI',
                'branch_name' => 'فرع الدمام',
                'branch_code' => '003',
                'notes' => 'حساب فرع الدمام',
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'bank_name' => 'بنك الإنماء',
                'account_name' => 'Link2u للتجارة',
                'account_number' => '5566778899',
                'iban' => 'SA0380000000608010167522',
                'swift_code' => 'INMASARI',
                'branch_name' => 'فرع مكة',
                'branch_code' => '004',
                'notes' => 'حساب فرع مكة',
                'status' => 'active',
                'sort_order' => 4,
            ],
        ];

        foreach ($bankAccounts as $account) {
            BankAccount::create($account);
        }

        $this->command->info('تم إنشاء الحسابات البنكية والمحافظ الإلكترونية بنجاح!');
    }
}
