<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserProfile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@Link2u.com',
            'password' => Hash::make('password123'),
            'user_type' => User::TYPE_ADMIN,
            'phone' => '+966501234567',
            'company_name' => 'Link2u',
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        UserProfile::create([
            'user_id' => $admin->id,
            'company_address' => 'الرياض، المملكة العربية السعودية',
            'contact_person' => 'مدير النظام',
            'website' => 'https://Link2u.com',
            'description' => 'منصة ربط الشركات اللوجستية بشركات التمويل المرخصة',
            'verification_status' => 'approved',
        ]);

        // Create Logistics Companies
        $logisticsCompanies = [
            [
                'name' => 'شركة النقل السريع',
                'email' => 'info@fastlogistics.com',
                'company_name' => 'شركة النقل السريع المحدودة',
                'phone' => '+966502345678',
                'company_registration' => '1010123456',
                'address' => 'شارع الملك فهد، الرياض',
                'description' => 'شركة متخصصة في خدمات النقل السريع والتوصيل',
            ],
            [
                'name' => 'مؤسسة الشحن الذكي',
                'email' => 'contact@smartshipping.com',
                'company_name' => 'مؤسسة الشحن الذكي',
                'phone' => '+966503456789',
                'company_registration' => '1010234567',
                'address' => 'طريق الملك عبدالعزيز، جدة',
                'description' => 'خدمات الشحن والتخزين باستخدام أحدث التقنيات',
            ],
            [
                'name' => 'شركة اللوجستيات المتقدمة',
                'email' => 'admin@advancedlogistics.com',
                'company_name' => 'شركة اللوجستيات المتقدمة',
                'phone' => '+966504567890',
                'company_registration' => '1010345678',
                'address' => 'شارع الأمير محمد بن عبدالعزيز، الدمام',
                'description' => 'حلول لوجستية شاملة للشركات الكبيرة والمتوسطة',
            ],
        ];

        foreach ($logisticsCompanies as $company) {
            $user = User::create([
                'name' => $company['name'],
                'email' => $company['email'],
                'password' => Hash::make('password123'),
                'user_type' => User::TYPE_LOGISTICS,
                'phone' => $company['phone'],
                'company_name' => $company['company_name'],
                'company_registration' => $company['company_registration'],
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'company_address' => $company['address'],
                'contact_person' => $company['name'],
                'description' => $company['description'],
                'verification_status' => 'approved',
            ]);
        }

        // Create Service Companies
        $serviceCompanies = [
            [
                'name' => 'شركة التجارة الإلكترونية',
                'email' => 'orders@ecommerce.com',
                'company_name' => 'شركة التجارة الإلكترونية المحدودة',
                'phone' => '+966505678901',
                'company_registration' => '1010456789',
                'address' => 'حي العليا، الرياض',
                'description' => 'متجر إلكتروني متخصص في بيع المنتجات الاستهلاكية',
            ],
            [
                'name' => 'مجموعة الأزياء العصرية',
                'email' => 'info@modernfashion.com',
                'company_name' => 'مجموعة الأزياء العصرية',
                'phone' => '+966506789012',
                'company_registration' => '1010567890',
                'address' => 'شارع التحلية، جدة',
                'description' => 'تصميم وتوزيع الأزياء النسائية والرجالية',
            ],
            [
                'name' => 'شركة الإلكترونيات الذكية',
                'email' => 'support@smartelectronics.com',
                'company_name' => 'شركة الإلكترونيات الذكية',
                'phone' => '+966507890123',
                'company_registration' => '1010678901',
                'address' => 'مجمع الظهران التجاري، الدمام',
                'description' => 'استيراد وتوزيع الأجهزة الإلكترونية والذكية',
            ],
            [
                'name' => 'شركة المواد الغذائية',
                'email' => 'sales@foodcompany.com',
                'company_name' => 'شركة المواد الغذائية المحدودة',
                'phone' => '+966508901234',
                'company_registration' => '1010789012',
                'address' => 'المنطقة الصناعية، الرياض',
                'description' => 'إنتاج وتوزيع المواد الغذائية المعلبة',
            ],
        ];

        foreach ($serviceCompanies as $company) {
            $user = User::create([
                'name' => $company['name'],
                'email' => $company['email'],
                'password' => Hash::make('password123'),
                'user_type' => User::TYPE_SERVICE_COMPANY,
                'phone' => $company['phone'],
                'company_name' => $company['company_name'],
                'company_registration' => $company['company_registration'],
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'company_address' => $company['address'],
                'contact_person' => $company['name'],
                'description' => $company['description'],
                'verification_status' => 'approved',
            ]);
        }

        // Create Regular Users
        $regularUsers = [
            [
                'name' => 'أحمد محمد السالم',
                'email' => 'ahmed.salem@gmail.com',
                'phone' => '+966509012345',
            ],
            [
                'name' => 'فاطمة عبدالله النصر',
                'email' => 'fatima.alnaser@gmail.com',
                'phone' => '+966510123456',
            ],
            [
                'name' => 'محمد علي الحربي',
                'email' => 'mohammed.alharbi@gmail.com',
                'phone' => '+966511234567',
            ],
            [
                'name' => 'نورا سعد الغامدي',
                'email' => 'nora.alghamdi@gmail.com',
                'phone' => '+966512345678',
            ],
            [
                'name' => 'خالد يوسف الزهراني',
                'email' => 'khalid.alzahrani@gmail.com',
                'phone' => '+966513456789',
            ],
        ];

        foreach ($regularUsers as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'user_type' => User::TYPE_REGULAR,
                'phone' => $userData['phone'],
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ]);
        }

        // Create some pending users
        $pendingUsers = [
            [
                'name' => 'شركة النقل الجديد',
                'email' => 'info@newtransport.com',
                'user_type' => User::TYPE_LOGISTICS,
                'company_name' => 'شركة النقل الجديد المحدودة',
                'phone' => '+966514567890',
                'company_registration' => '1010890123',
            ],
            [
                'name' => 'متجر الهدايا العصرية',
                'email' => 'gifts@moderngifts.com',
                'user_type' => User::TYPE_SERVICE_COMPANY,
                'company_name' => 'متجر الهدايا العصرية',
                'phone' => '+966515678901',
                'company_registration' => '1010901234',
            ],
        ];

        foreach ($pendingUsers as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'user_type' => $userData['user_type'],
                'phone' => $userData['phone'],
                'company_name' => $userData['company_name'],
                'company_registration' => $userData['company_registration'],
                'status' => User::STATUS_PENDING,
                'email_verified_at' => now(),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'contact_person' => $userData['name'],
                'verification_status' => 'pending',
            ]);
        }
    }
}
