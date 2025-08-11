<?php

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FundingRequest;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Payment;
use App\Models\ServiceCompany;
use App\Models\LogisticsCompany;
use App\Models\ClientDebt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogisticsController extends Controller
{
    /**
     * عرض الصفحة العامة للشركات اللوجستية (بدون تسجيل دخول)
     */
    public function publicPage()
    {
        // بيانات تجريبية للصفحة العامة
        $stats = [
            'available_balance' => 125000,
            'total_funded' => 850000,
            'total_requests' => 23,
            'last_request_status' => 'تم الصرف',
            'last_request_date' => '2024-01-15'
        ];

        return view('logistics', compact('stats'));
    }

    /**
     * عرض لوحة تحكم الشركة اللوجستية
     */
    public function dashboard()
    {
        $user = Auth::user();
        $logisticsCompany = $user->logisticsCompany;


        // إذا لم تكن بيانات الشركة موجودة، أنشئ بيانات افتراضية
        if (!$logisticsCompany) {
            $logisticsCompany = (object) [
                'id' => $user->id,
                'available_balance' => 0,
                'credit_limit' => 100000,
                'used_credit' => 0
            ];
        }

        // حساب الإحصائيات الحقيقية من قاعدة البيانات
        $creditLimit = $logisticsCompany->credit_limit ?? 100000;
        $usedCredit = FundingRequest::where('logistics_company_id', $user->id)
            ->whereIn('status', ['approved', 'disbursed'])
            ->sum('amount');
        $availableCredit = $creditLimit - $usedCredit;

        // حساب الرصيد المتاح الفعلي (من الفواتير المدفوعة)
        $totalPaidInvoices = Invoice::where('logistics_company_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('paid_amount');
        $totalOutstandingInvoices = Invoice::where('logistics_company_id', $user->id)
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->sum('remaining_amount');
        $availableBalance = max(0, $totalPaidInvoices - $totalOutstandingInvoices); // لا يمكن أن يكون سالباً

        // الإحصائيات الرئيسية
        $stats = [
            'available_balance' => $availableBalance,
            'credit_limit' => $creditLimit,
            'used_credit' => $usedCredit,
            'total_requests' => FundingRequest::where('logistics_company_id', $user->id)->count(),
            'pending_requests' => FundingRequest::where('logistics_company_id', $user->id)
                ->where('status', 'pending')->count(),
            'approved_requests' => FundingRequest::where('logistics_company_id', $user->id)
                ->where('status', 'approved')->count(),
            'total_invoices' => Invoice::where('logistics_company_id', $user->id)->count(),
            'paid_invoices' => Invoice::where('logistics_company_id', $user->id)
                ->where('status', 'paid')->count(),
            'pending_invoices' => Invoice::where('logistics_company_id', $user->id)
                ->where('status', 'pending')->count(),
        ];

        // آخر طلبات التمويل
        $recentFundingRequests = FundingRequest::where('logistics_company_id', $user->id)
            ->with(['logisticsCompany'])
            ->latest()
            ->take(5)
            ->get();

        // آخر الفواتير
        $recentInvoices = Invoice::where('logistics_company_id', $user->id)
            ->with(['serviceCompany'])
            ->latest()
            ->take(5)
            ->get();

        // الإحصائيات الشهرية
        $monthlyStats = $this->getMonthlyStats($user->id);

        return view('logistics.dashboard', compact(
            'logisticsCompany',
            'stats',
            'recentFundingRequests',
            'recentInvoices',
            'monthlyStats'
        ));
    }

    /**
     * عرض طلبات التمويل
     */
    public function fundingRequests(Request $request)
    {
        $user = Auth::user();
        $logisticsCompany = $user->logisticsCompany;

        $query = FundingRequest::where('logistics_company_id', $logisticsCompany->id);

        // التصفية
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->whereHas('serviceCompany.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $fundingRequests = $query->latest()->paginate(15);

        return view('logistics.funding.index', compact('fundingRequests'));
    }

    /**
     * إنشاء طلب تمويل جديد
     */
    public function createFundingRequest()
    {
        $user = Auth::user();
        $logisticsCompany = $user->logisticsCompany;

        // جلب الشركات الطالبة للخدمة المرتبطة
        $serviceCompanies = ServiceCompany::whereHas('invoices', function ($q) use ($logisticsCompany) {
            $q->where('logistics_company_id', $logisticsCompany->id);
        })->with('user')->get();

        return view('logistics.funding.create', compact('serviceCompanies', 'logisticsCompany'));
    }

    /**
     * حفظ طلب تمويل جديد
     */
    public function storeFundingRequest(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000|max:1000000',
            'reason' => 'required|string|in:operational,expansion,equipment,emergency,other',
            'description' => 'nullable|string|max:1000',
            'documents' => 'nullable|array|max:5',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'clients' => 'required|array|min:1',
            'clients.*.company_name' => 'required|string|max:255',
            'clients.*.contact_person' => 'required|string|max:255',
            'clients.*.email' => 'required|email|max:255',
            'clients.*.phone' => 'required|string|max:20',
            'clients.*.amount' => 'required|numeric|min:1000',
            'clients.*.due_date' => 'required|date|after:today',
            'clients.*.invoice_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

                $user = Auth::user();

        // الحصول على بيانات الشركة اللوجستية أو إنشاء بيانات افتراضية
        $logisticsCompany = $user->logisticsCompany;
        if (!$logisticsCompany) {
            $logisticsCompany = (object) [
                'id' => $user->id,
                'credit_limit' => 100000,
                'used_credit' => 0,
                'available_balance' => 0,
            ];
        }

        // حساب الائتمان المستخدم من طلبات التمويل المعتمدة
        $usedCredit = FundingRequest::where('logistics_company_id', $user->id)
            ->whereIn('status', ['approved', 'disbursed'])
            ->sum('amount');

        // التحقق من الحد الائتماني
        $creditLimit = $logisticsCompany->credit_limit ?? 100000;
        $availableCredit = max(0, $creditLimit - $usedCredit); // لا يمكن أن يكون سالباً

        // إذا تجاوز الائتمان المستخدم الحد الائتماني
        if ($usedCredit > $creditLimit) {
            $excessAmount = $usedCredit - $creditLimit;
            $errorMessage = 'تم تجاوز الحد الائتماني! ';
            $errorMessage .= 'الائتمان المستخدم: ' . number_format($usedCredit) . ' ر.س ';
            $errorMessage .= 'الحد الائتماني: ' . number_format($creditLimit) . ' ر.س ';
            $errorMessage .= 'المتجاوز: ' . number_format($excessAmount) . ' ر.س. ';
            $errorMessage .= 'يجب سداد المتجاوز أولاً قبل طلب تمويل جديد.';

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }

        if ($request->amount > $availableCredit) {
            $errorMessage = 'المبلغ المطلوب يتجاوز الحد الائتماني المتاح. ';
            $errorMessage .= 'الائتمان المتاح: ' . number_format($availableCredit) . ' ر.س ';
            $errorMessage .= 'من أصل ' . number_format($creditLimit) . ' ر.س. ';
            $errorMessage .= 'الائتمان المستخدم: ' . number_format($usedCredit) . ' ر.س. ';

            if ($availableCredit <= 0) {
                $errorMessage .= 'لا يمكن طلب تمويل جديد حتى يتم سداد الائتمان المستخدم.';
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }

        // التأكد من تطابق إجمالي المبالغ
        $clientsTotal = collect($request->clients)->sum('amount');
        if (abs($clientsTotal - $request->amount) > 0.01) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'إجمالي مبالغ العملاء (' . number_format($clientsTotal) . ' ر.س) لا يطابق مبلغ التمويل المطلوب (' . number_format($request->amount) . ' ر.س)');
        }

        DB::beginTransaction();
        try {
            // إنشاء طلب التمويل
            $fundingRequest = FundingRequest::create([
                'logistics_company_id' => $user->id, // استخدام ID المستخدم مباشرة
                'service_company_id' => null, // طلب للشركة اللوجستية نفسها
                'amount' => $request->amount,
                'reason' => $request->reason,
                'description' => $request->description,
                'status' => 'pending',
            ]);

            // حفظ بيانات العملاء المدينين
            foreach ($request->clients as $clientData) {
                $invoiceDocument = null;

                // رفع ملف الفاتورة الأصلية إذا وُجد
                if (isset($clientData['invoice_document']) && $clientData['invoice_document']->isValid()) {
                    $invoiceDocument = $clientData['invoice_document']->store(
                        'client_invoices/' . $fundingRequest->id,
                        'public'
                    );
                }

                ClientDebt::create([
                    'funding_request_id' => $fundingRequest->id,
                    'company_name' => $clientData['company_name'],
                    'contact_person' => $clientData['contact_person'],
                    'email' => $clientData['email'],
                    'phone' => $clientData['phone'],
                    'amount' => $clientData['amount'],
                    'due_date' => $clientData['due_date'],
                    'invoice_document' => $invoiceDocument,
                    'status' => ClientDebt::STATUS_PENDING,
                ]);
            }

            // رفع المستندات العامة
            if ($request->hasFile('documents')) {
                $documents = [];
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('funding_requests/' . $fundingRequest->id, 'public');
                    $documents[] = $path;
                }
                $fundingRequest->update(['documents' => $documents]);
            }

            DB::commit();

            return redirect()->route('logistics.dashboard')
                ->with('success', 'تم إرسال طلب التمويل بنجاح مع بيانات ' . count($request->clients) . ' عملاء. الطلب قيد المراجعة.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إرسال الطلب: ' . $e->getMessage());
        }
    }

    /**
     * سداد الائتمان المتجاوز
     */
    public function payCreditExcess(Request $request)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:bank_transfer,check,cash,other',
        ]);

        // التحقق من وجود فواتير محددة
        if (!$request->has('selected_invoices')) {
            return redirect()->back()
                ->with('error', 'لم يتم إرسال بيانات الفواتير المحددة');
        }

        if (empty($request->selected_invoices)) {
            return redirect()->back()
                ->with('error', 'قائمة الفواتير المحددة فارغة. نوع البيانات: ' . gettype($request->selected_invoices));
        }

        // إزالة القيم الفارغة من المصفوفة
        $selectedInvoices = array_filter($request->selected_invoices, function($value) {
            return !empty($value) && $value !== '';
        });

        if (empty($selectedInvoices)) {
            return redirect()->back()
                ->with('error', 'يرجى تحديد فاتورة واحدة على الأقل');
        }

        // التحقق من صحة IDs الفواتير
        $request->validate([
            'selected_invoices' => 'array|min:1',
            'selected_invoices.*' => 'exists:invoices,id',
        ], [
            'selected_invoices.min' => 'يرجى تحديد فاتورة واحدة على الأقل',
            'selected_invoices.*.exists' => 'إحدى الفواتير المحددة غير صحيحة',
        ]);

        // استخدام المصفوفة المفلترة
        $request->merge(['selected_invoices' => $selectedInvoices]);

        $user = Auth::user();

        // حساب الائتمان المستخدم والحد الائتماني
        $usedCredit = FundingRequest::where('logistics_company_id', $user->id)
            ->whereIn('status', ['approved', 'disbursed'])
            ->sum('amount');
        $creditLimit = 100000; // الحد الائتماني الافتراضي

        $excessAmount = max(0, $usedCredit - $creditLimit);

        // لا نتحقق من الحد الأدنى للمبلغ - يمكن سداد أي مبلغ
        // if ($request->payment_amount < $excessAmount) {
        //     return redirect()->back()
        //         ->with('error', 'مبلغ السداد يجب أن يكون على الأقل ' . number_format($excessAmount) . ' ر.س');
        // }

        if ($request->payment_amount > $usedCredit) {
            return redirect()->back()
                ->with('error', 'مبلغ السداد لا يمكن أن يتجاوز الائتمان المستخدم (' . number_format($usedCredit) . ' ر.س)');
        }

        DB::beginTransaction();
        try {
            // البحث عن الفواتير المحددة
            $selectedInvoices = Invoice::where('logistics_company_id', $user->id)
                ->whereIn('id', $request->selected_invoices)
                ->whereIn('payment_status', ['unpaid', 'partial'])
                ->get();

            if ($selectedInvoices->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'الفواتير المحددة غير صحيحة أو مدفوعة مسبقاً.');
            }

            // لا نتحقق من أن مبلغ السداد يغطي الفواتير المحددة - يمكن سداد جزء من الفواتير
            // $totalSelectedAmount = $selectedInvoices->sum('remaining_amount');
            // if ($request->payment_amount < $totalSelectedAmount) {
            //     return redirect()->back()
            //         ->with('error', 'مبلغ السداد (' . number_format($request->payment_amount) . ' ر.س) لا يغطي الفواتير المحددة (' . number_format($totalSelectedAmount) . ' ر.س)');
            // }

            $remainingPaymentAmount = $request->payment_amount;
            $processedInvoices = [];

            // توزيع مبلغ السداد على الفواتير المحددة
            foreach ($selectedInvoices as $invoice) {
                if ($remainingPaymentAmount <= 0) break;

                $invoiceRemaining = $invoice->remaining_amount;
                $paymentAmount = min($remainingPaymentAmount, $invoiceRemaining);

                // إنشاء سجل السداد للفاتورة
                $payment = \App\Models\Payment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $paymentAmount,
                    'payment_method' => $request->payment_method,
                    'payment_date' => now(),
                    'status' => 'pending', // يحتاج موافقة الأدمن
                    'notes' => 'سداد الائتمان المتجاوز - ' . $request->payment_method,
                    'reference_number' => 'CREDIT-' . date('Ymd') . '-' . str_pad(\App\Models\Payment::count() + 1, 4, '0', STR_PAD_LEFT),
                ]);

                $processedInvoices[] = [
                    'invoice_number' => $invoice->invoice_number,
                    'amount' => $paymentAmount,
                    'payment_reference' => $payment->reference_number
                ];

                $remainingPaymentAmount -= $paymentAmount;
            }

            DB::commit();

            $successMessage = 'تم إرسال طلب السداد بنجاح! ';
            $successMessage .= 'تم سداد ' . count($processedInvoices) . ' فاتورة محددة. ';
            $successMessage .= 'أرقام المراجع: ' . implode(', ', array_column($processedInvoices, 'payment_reference'));

            return redirect()->back()->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إرسال طلب السداد: ' . $e->getMessage());
        }
    }

    /**
     * الحصول على الإحصائيات الشهرية
     */
    private function getMonthlyStats($logisticsCompanyId)
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $months[] = [
                'month' => $date->format('M Y'),
                'funding_requests' => FundingRequest::where('logistics_company_id', $logisticsCompanyId)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count(),
                'original_amount' => FundingRequest::where('logistics_company_id', $logisticsCompanyId)
                    ->where('status', 'disbursed')
                    ->whereBetween('disbursed_at', [$monthStart, $monthEnd])
                    ->sum('amount'),
                'invoices' => Invoice::where('logistics_company_id', $logisticsCompanyId)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count(),
            ];
        }

        return $months;
    }
}
