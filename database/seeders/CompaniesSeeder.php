<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LogisticsCompany;
use App\Models\ServiceCompany;
use App\Models\FundingRequest;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get logistics companies users
        $logisticsUsers = User::where('user_type', User::TYPE_LOGISTICS)
                             ->where('status', User::STATUS_ACTIVE)
                             ->get();

        // Create logistics companies data
        foreach ($logisticsUsers as $user) {
            $logisticsCompany = LogisticsCompany::create([
                'user_id' => $user->id,
                'available_balance' => rand(50000, 500000),
                'total_funded' => rand(100000, 1000000),
                'total_requests' => rand(5, 25),
                'last_request_status' => 'تم الصرف',
            ]);

            // Create some funding requests for each logistics company
            $requestCount = rand(3, 8);
            for ($i = 0; $i < $requestCount; $i++) {
                $amount = rand(25000, 200000);
                $reasons = ['operational', 'expansion', 'equipment', 'emergency'];
                $statuses = ['disbursed', 'approved', 'pending', 'under_review'];

                // Most requests should be approved/disbursed for realistic data
                $status = $i < 2 ? 'disbursed' : (rand(0, 100) > 20 ? 'approved' : 'pending');

                $createdAt = now()->subDays(rand(1, 90));

                FundingRequest::create([
                    'logistics_company_id' => $logisticsCompany->id,
                    'amount' => $amount,
                    'reason' => $reasons[array_rand($reasons)],
                    'description' => $this->getFundingDescription(),
                    'status' => $status,
                    'requested_at' => $createdAt,
                    'approved_at' => $status === 'approved' || $status === 'disbursed' ? $createdAt->addHours(rand(2, 48)) : null,
                    'disbursed_at' => $status === 'disbursed' ? $createdAt->addHours(rand(48, 120)) : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        // Get service companies users
        $serviceUsers = User::where('user_type', User::TYPE_SERVICE_COMPANY)
                           ->where('status', User::STATUS_ACTIVE)
                           ->get();

        // Create service companies data
        foreach ($serviceUsers as $user) {
            $totalOutstanding = rand(50000, 800000);
            $totalPaid = rand(100000, 1500000);

            $paymentStatuses = ['regular', 'overdue', 'under_review'];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];

            ServiceCompany::create([
                'user_id' => $user->id,
                'total_outstanding' => $totalOutstanding,
                'total_paid' => $totalPaid,
                'payment_status' => $paymentStatus,
                'credit_limit' => rand(200000, 2000000),
            ]);
        }
    }

    /**
     * Get random funding request description
     */
    private function getFundingDescription(): string
    {
        $descriptions = [
            'نحتاج تمويل لتوسيع أسطول الشاحنات لتلبية الطلب المتزايد على خدماتنا في منطقة الرياض',
            'طلب تمويل لشراء معدات جديدة لتحسين كفاءة عمليات التحميل والتفريغ',
            'تمويل عاجل لتغطية تكاليف الوقود والصيانة للشهر الحالي',
            'نطلب تمويل لافتتاح فرع جديد في مدينة جدة لخدمة عملاء المنطقة الغربية',
            'تمويل لشراء أنظمة تتبع متقدمة وتحديث البرمجيات اللوجستية',
            'نحتاج تمويل لتوسيع المستودعات وزيادة القدرة التخزينية',
            'طلب تمويل لتدريب الموظفين وتحسين مستوى الخدمة المقدمة',
            'تمويل لشراء معدات السلامة والأمان المطلوبة حسب اللوائح الجديدة',
            'طلب تمويل لتطوير نظام إدارة المخزون وربطه بالعملاء',
            'تمويل لتوسيع شبكة التوزيع لتشمل المناطق النائية',
        ];

        return $descriptions[array_rand($descriptions)];
    }
}
