<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCompany;
use App\Models\LogisticsCompany;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\InstallmentPlan;
use App\Models\InstallmentPayment;
use App\Models\ContactRequest;
use App\Models\LinkingService;

class InvoicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceCompanies = ServiceCompany::with('user')->get();
        $logisticsCompanies = LogisticsCompany::with('user')->get();

        // Create invoices for each service company
        foreach ($serviceCompanies as $serviceCompany) {
            $invoiceCount = rand(5, 15);

            for ($i = 0; $i < $invoiceCount; $i++) {
                $logisticsCompany = $logisticsCompanies->random();
                $originalAmount = rand(25000, 150000);
                $paidAmount = 0;
                $remainingAmount = $originalAmount;

                // Determine invoice status and payment
                $dueDate = now()->subDays(rand(-30, 90)); // Some past due, some future
                $isOverdue = $dueDate->isPast();

                // 60% of invoices should have some payment
                if (rand(1, 100) <= 60) {
                    if (rand(1, 100) <= 70) {
                        // Full payment
                        $paidAmount = $originalAmount;
                        $remainingAmount = 0;
                        $paymentStatus = 'paid';
                        $status = 'paid';
                    } else {
                        // Partial payment
                        $paidAmount = rand(10000, $originalAmount - 5000);
                        $remainingAmount = $originalAmount - $paidAmount;
                        $paymentStatus = 'partial';
                        $status = $isOverdue ? 'overdue' : 'sent';
                    }
                } else {
                    // No payment
                    $paymentStatus = 'unpaid';
                    $status = $isOverdue ? 'overdue' : 'sent';
                }

                $invoice = Invoice::create([
                    'service_company_id' => $serviceCompany->id,
                    'logistics_company_id' => $logisticsCompany->id,
                    'invoice_number' => 'INV-' . now()->format('Ymd') . str_pad(($serviceCompany->id * 100) + $i + 1, 3, '0', STR_PAD_LEFT),
                    'original_amount' => $originalAmount,
                    'paid_amount' => $paidAmount,
                    'remaining_amount' => $remainingAmount,
                    'due_date' => $dueDate,
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'created_at' => now()->subDays(rand(1, 120)),
                ]);

                // Create payments if any
                if ($paidAmount > 0) {
                    if ($paymentStatus === 'paid') {
                        // Single full payment
                        Payment::create([
                            'invoice_id' => $invoice->id,
                            'amount' => $paidAmount,
                            'payment_date' => $dueDate->subDays(rand(1, 10)),
                            'payment_method' => $this->getRandomPaymentMethod(),
                            'reference_number' => 'PAY-' . rand(100000, 999999),
                            'notes' => 'دفعة كاملة للفاتورة',
                            'status' => 'confirmed',
                        ]);
                    } else {
                        // Multiple partial payments
                        $paymentCount = rand(1, 3);
                        $amountPerPayment = floor($paidAmount / $paymentCount);

                        for ($j = 0; $j < $paymentCount; $j++) {
                            $paymentAmount = ($j === $paymentCount - 1) ?
                                ($paidAmount - ($amountPerPayment * ($paymentCount - 1))) :
                                $amountPerPayment;

                            Payment::create([
                                'invoice_id' => $invoice->id,
                                'amount' => $paymentAmount,
                                'payment_date' => $invoice->created_at->addDays(rand(10, 60)),
                                'payment_method' => $this->getRandomPaymentMethod(),
                                'reference_number' => 'PAY-' . rand(100000, 999999),
                                'notes' => "دفعة جزئية رقم " . ($j + 1),
                                'status' => 'confirmed',
                            ]);
                        }
                    }
                }

                // Create installment plans for some unpaid invoices (20% chance)
                if ($remainingAmount > 0 && rand(1, 100) <= 20) {
                    $installmentCount = rand(3, 12);
                    $monthlyAmount = ceil($remainingAmount / $installmentCount);

                    $installmentPlan = InstallmentPlan::create([
                        'invoice_id' => $invoice->id,
                        'total_amount' => $remainingAmount,
                        'installment_count' => $installmentCount,
                        'monthly_amount' => $monthlyAmount,
                        'start_date' => now()->addDays(rand(1, 30)),
                        'status' => rand(1, 100) <= 80 ? 'approved' : 'pending',
                        'reason' => $this->getRandomInstallmentReason(),
                    ]);

                    // Create installment payments
                    if ($installmentPlan->status === 'approved') {
                        for ($k = 0; $k < $installmentCount; $k++) {
                            $dueDate = $installmentPlan->start_date->copy()->addMonths($k);
                            $isPaid = $dueDate->isPast() && rand(1, 100) <= 70; // 70% chance of payment if due

                            InstallmentPayment::create([
                                'installment_plan_id' => $installmentPlan->id,
                                'installment_number' => $k + 1,
                                'amount' => ($k === $installmentCount - 1) ?
                                    ($remainingAmount - ($monthlyAmount * ($installmentCount - 1))) :
                                    $monthlyAmount,
                                'due_date' => $dueDate,
                                'paid_date' => $isPaid ? $dueDate->addDays(rand(-5, 5)) : null,
                                'status' => $isPaid ? 'paid' : ($dueDate->isPast() ? 'overdue' : 'pending'),
                            ]);
                        }

                        // Update invoice payment status
                        $invoice->update(['payment_status' => 'installment']);
                    }
                }
            }
        }

        // Create contact requests
        $this->createContactRequests();

        // Create linking services
        $this->createLinkingServices($logisticsCompanies, $serviceCompanies);
    }

    private function createContactRequests(): void
    {
        $contacts = [
            [
                'company_name' => 'شركة التوزيع الشامل',
                'contact_person' => 'محمد أحمد الزهراني',
                'phone' => '+966555123456',
                'email' => 'info@comprehensive-distribution.com',
                'service_type' => 'financing_link',
                'message' => 'نحن شركة توزيع ونرغب في الحصول على خدمات التمويل لتوسيع أعمالنا وشراء مركبات جديدة',
                'status' => 'new',
            ],
            [
                'company_name' => 'مجموعة البناء المتطورة',
                'contact_person' => 'سارة عبدالرحمن النصر',
                'phone' => '+966556789012',
                'email' => 'sara@advanced-construction.com',
                'service_type' => 'client_link',
                'message' => 'نحتاج لربطنا مع شركات لوجستية موثوقة لنقل مواد البناء إلى مواقع المشاريع',
                'status' => 'in_progress',
            ],
            [
                'company_name' => 'متجر الإلكترونيات الحديثة',
                'contact_person' => 'خالد يوسف الغامدي',
                'phone' => '+966557890123',
                'email' => 'khalid@modern-electronics.com',
                'service_type' => 'tracking',
                'message' => 'نريد شراء أجهزة تتبع متقدمة لمراقبة شحناتنا ومعرفة مواقعها في الوقت الفعلي',
                'status' => 'completed',
            ],
            [
                'company_name' => 'شركة الخدمات اللوجستية السريعة',
                'contact_person' => 'فاطمة محمد العتيبي',
                'phone' => '+966558901234',
                'email' => 'fatima@fast-logistics.com',
                'service_type' => 'consultation',
                'message' => 'نرغب في استشارة حول أفضل الطرق لتطوير خدماتنا اللوجستية وزيادة كفاءة العمليات',
                'status' => 'new',
            ],
            [
                'company_name' => 'مؤسسة التجارة الذكية',
                'contact_person' => 'عبدالله سعد الحربي',
                'phone' => '+966559012345',
                'email' => 'abdullah@smart-trade.com',
                'service_type' => 'partnership',
                'message' => 'نتطلع لشراكة استراتيجية معكم لتقديم خدمات لوجستية متكاملة لعملائنا',
                'status' => 'in_progress',
            ],
        ];

        foreach ($contacts as $contact) {
            ContactRequest::create(array_merge($contact, [
                'created_at' => now()->subDays(rand(1, 30)),
            ]));
        }
    }

    private function createLinkingServices($logisticsCompanies, $serviceCompanies): void
    {
        // Create some linking services
        for ($i = 0; $i < 10; $i++) {
            $logistics = $logisticsCompanies->random();
            $service = $serviceCompanies->random();

            // Ensure no duplicate linking
            $existing = LinkingService::where('logistics_company_id', $logistics->id)
                                   ->where('service_company_id', $service->id)
                                   ->exists();

            if (!$existing) {
                $serviceTypes = ['financing', 'logistics', 'warehousing', 'distribution'];
                $statuses = ['active', 'completed', 'pending'];

                $amount = rand(50000, 500000);
                $commission = $amount * (rand(2, 8) / 100); // 2-8% commission

                LinkingService::create([
                    'logistics_company_id' => $logistics->id,
                    'service_company_id' => $service->id,
                    'service_type' => $serviceTypes[array_rand($serviceTypes)],
                    'status' => $statuses[array_rand($statuses)],
                    'amount' => $amount,
                    'commission' => $commission,
                    'linked_at' => now()->subDays(rand(1, 90)),
                ]);
            }
        }
    }

    private function getRandomPaymentMethod(): string
    {
        $methods = ['bank_transfer', 'online_payment', 'check', 'cash'];
        return $methods[array_rand($methods)];
    }

    private function getRandomInstallmentReason(): string
    {
        $reasons = [
            'ظروف مالية مؤقتة تتطلب تقسيط المبلغ',
            'طلب تقسيط لتحسين التدفق النقدي للشركة',
            'ترتيبات خاصة بناء على اتفاق مسبق',
            'تقسيط لتسهيل إدارة المدفوعات الشهرية',
            'طلب تقسيط بسبب موسمية العمل',
        ];

        return $reasons[array_rand($reasons)];
    }
}
