<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClientDebt;
use App\Models\ServiceCompany;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class ServiceCompanyRegistrationController extends Controller
{
    /**
     * عرض صفحة التسجيل للشركة الطالبة للخدمة
     */
    public function showRegistrationForm(Request $request)
    {
        $token = $request->get('token');
        $email = $request->get('email');

        if (!$token || !$email) {
            abort(404, 'رابط غير صحيح');
        }

        // التحقق من صحة التوكن والإيميل
        $clientDebt = ClientDebt::where('email', $email)
                                ->where('status', ClientDebt::STATUS_PENDING)
                                ->first();

        if (!$clientDebt) {
            abort(404, 'رابط غير صحيح أو منتهي الصلاحية');
        }

        // التحقق من التوكن المرسل
        $expectedToken = md5($clientDebt->id . $clientDebt->email . config('app.key'));
        if ($token !== $expectedToken) {
            abort(404, 'رابط غير صحيح');
        }

        return view('auth.service-company-register', compact('clientDebt', 'token'));
    }

    /**
     * معالجة تسجيل الشركة الطالبة للخدمة
     */
    public function register(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'client_debt_id' => 'required|exists:client_debts,id',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_address' => 'required|string|max:255',
            'commercial_register' => 'nullable|string|max:50',
            'tax_number' => 'nullable|string|max:50',
            'bank_account' => 'nullable|string|max:50',
            'agree_terms' => 'required|accepted',
        ]);

        // جلب بيانات الدين
        $clientDebt = ClientDebt::findOrFail($request->client_debt_id);

        // التحقق من التوكن
        $expectedToken = md5($clientDebt->id . $clientDebt->email . config('app.key'));
        if ($request->token !== $expectedToken) {
            return back()->withErrors(['token' => 'رابط غير صحيح']);
        }

        // التحقق من عدم وجود حساب مسبق
        if ($clientDebt->status !== ClientDebt::STATUS_PENDING) {
            return back()->withErrors(['email' => 'تم إنشاء حساب لهذه الشركة مسبقاً']);
        }

        // التحقق من عدم وجود مستخدم بنفس الإيميل
        $existingUser = User::where('email', $clientDebt->email)->first();
        if ($existingUser) {
            return back()->withErrors(['email' => 'يوجد حساب مسجل بهذا البريد الإلكتروني مسبقاً']);
        }

        DB::beginTransaction();
        try {
            // إنشاء المستخدم
            $user = User::create([
                'name' => $clientDebt->company_name,
                'email' => $clientDebt->email,
                'password' => Hash::make($request->password),
                'user_type' => 'service_company',
                'phone' => $clientDebt->phone,
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            // إنشاء سجل في جدول service_companies
            $serviceCompany = ServiceCompany::create([
                'user_id' => $user->id,
                'company_name' => $clientDebt->company_name,
                'contact_person' => $clientDebt->contact_person,
                'email' => $clientDebt->email,
                'phone' => $clientDebt->phone,
                'address' => $request->company_address,
                'commercial_register' => $request->commercial_register,
                'tax_number' => $request->tax_number,
                'bank_account' => $request->bank_account,
                'total_outstanding' => $clientDebt->amount,
                'total_paid' => 0,
                'credit_limit' => 0,
                'payment_status' => ServiceCompany::PAYMENT_REGULAR,
                'status' => 'active',
            ]);

            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'logistics_company_id' => $clientDebt->fundingRequest->logistics_company_id,
                'service_company_id' => $serviceCompany->id,
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 6, '0', STR_PAD_LEFT),
                'original_amount' => $clientDebt->amount,
                'paid_amount' => 0,
                'remaining_amount' => $clientDebt->amount,
                'due_date' => $clientDebt->due_date,
                'payment_status' => 'unpaid',
                'description' => 'فاتورة تحصيل مستحقات - طلب تمويل #' . $clientDebt->fundingRequest->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // تحديث حالة الدين
            $clientDebt->update([
                'status' => ClientDebt::STATUS_ACCOUNT_CREATED,
                'created_user_id' => $user->id,
                'created_invoice_id' => $invoice->id,
            ]);

            DB::commit();

            // تسجيل دخول المستخدم تلقائياً
            Auth::login($user);

            return redirect()->route('service_company.dashboard')
                           ->with('success', 'تم إنشاء حسابكم بنجاح! مرحباً بكم في منصة LogistiQ');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage()]);
        }
    }
}
