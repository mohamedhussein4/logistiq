<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 بدء تشغيل Seeders لنظام Link2u...');

        // تشغيل جميع Seeders بالترتيب الصحيح
        $this->call([
            UserSeeder::class,          // إنشاء المستخدمين والشركات
            CompaniesSeeder::class,     // إنشاء بيانات الشركات وطلبات التمويل
            ProductsSeeder::class,      // إنشاء المنتجات والطلبات
            InvoicesSeeder::class,      // إنشاء الفواتير والمدفوعات
            PaymentAccountsSeeder::class, // إضافة Seeder الحسابات البنكية والمحافظ
        ]);

        $this->command->info('✅ تم إنشاء جميع البيانات التجريبية بنجاح!');
        $this->command->line('');
        $this->command->info('📊 البيانات المنشأة:');
        $this->command->line('👤 المستخدمين: أدمن + شركات لوجستية + شركات طالبة + مستخدمين عاديين');
        $this->command->line('🏢 بيانات الشركات: أرصدة، طلبات تمويل، مستحقات');
        $this->command->line('📦 المنتجات: أجهزة تتبع مع تصنيفات وطلبات شراء');
        $this->command->line('🧾 الفواتير: فواتير مع مدفوعات وخطط تقسيط');
        $this->command->line('📞 طلبات التواصل: طلبات من عملاء محتملين');
        $this->command->line('🔗 خدمات الربط: ربط بين الشركات');
        $this->command->line('');
        $this->command->info('🔑 بيانات تسجيل الدخول:');
        $this->command->line('📧 الأدمن: admin@Link2u.com');
        $this->command->line('🔒 كلمة المرور: password123');
        $this->command->line('');
        $this->command->info('🌐 يمكنك الآن الدخول إلى لوحة التحكم: /admin');
    }
}
