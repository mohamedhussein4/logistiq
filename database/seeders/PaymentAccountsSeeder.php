<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankAccount;
use App\Models\ElectronicWallet;

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

        // إنشاء المحافظ الإلكترونية
        $electronicWallets = [
            [
                'wallet_name' => 'STC Pay',
                'wallet_type' => 'stc_pay',
                'account_number' => '966501234567',
                'account_name' => 'Link2u للتجارة',
                'phone_number' => '966501234567',
                'email' => 'payments@link2u.com',
                'notes' => 'محفظة STC Pay الرئيسية',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'wallet_name' => 'مدى',
                'wallet_type' => 'mada',
                'account_number' => '1234567890123456',
                'account_name' => 'Link2u للتجارة',
                'phone_number' => '966501234567',
                'email' => 'payments@link2u.com',
                'notes' => 'محفظة مدى الرئيسية',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'wallet_name' => 'Apple Pay',
                'wallet_type' => 'apple_pay',
                'account_number' => 'link2u.applepay',
                'account_name' => 'Link2u للتجارة',
                'phone_number' => '966501234567',
                'email' => 'payments@link2u.com',
                'notes' => 'محفظة Apple Pay',
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'wallet_name' => 'Google Pay',
                'wallet_type' => 'google_pay',
                'account_number' => 'link2u.googlepay',
                'account_name' => 'Link2u للتجارة',
                'phone_number' => '966501234567',
                'email' => 'payments@link2u.com',
                'notes' => 'محفظة Google Pay',
                'status' => 'active',
                'sort_order' => 4,
            ],
            [
                'wallet_name' => 'PayPal',
                'wallet_type' => 'paypal',
                'account_number' => 'payments@link2u.com',
                'account_name' => 'Link2u للتجارة',
                'phone_number' => '966501234567',
                'email' => 'payments@link2u.com',
                'notes' => 'محفظة PayPal الدولية',
                'status' => 'active',
                'sort_order' => 5,
            ],
        ];

        foreach ($electronicWallets as $wallet) {
            ElectronicWallet::create($wallet);
        }

        $this->command->info('تم إنشاء الحسابات البنكية والمحافظ الإلكترونية بنجاح!');
    }
}
