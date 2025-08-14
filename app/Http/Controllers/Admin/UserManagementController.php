<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FundingRequest;
use App\Models\BalanceTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index(Request $request)
    {
        $query = User::with(['profile']);

        // تصفية حسب نوع المستخدم
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        // تصفية حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * عرض تفاصيل المستخدم
     */
    public function show(User $user)
    {
        $user->load(['profile', 'balanceTransactions' => function($query) {
            $query->latest()->limit(10);
        }]);

        $stats = [
            'total_balance' => $user->total_balance ?? 0,
            'available_balance' => $user->available_balance ?? 0,
            'used_balance' => $user->used_balance ?? 0,
            'remaining_balance' => ($user->available_balance ?? 0) - ($user->used_balance ?? 0),
        ];

        // جلب طلبات التمويل للشركات اللوجستية
        $fundingRequests = [];
        if ($user->isLogisticsCompany()) {
            $fundingRequests = FundingRequest::where('user_id', $user->id)
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('admin.users.show', compact('user', 'stats', 'fundingRequests'));
    }

    /**
     * تحديث رصيد المستخدم
     */
    public function updateBalance(Request $request, User $user)
    {
        if (!$user->isLogisticsCompany()) {
            return response()->json(['error' => 'هذه العملية متاحة للشركات اللوجستية فقط'], 400);
        }

        $request->validate([
            'action' => 'required|in:add,subtract,set',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $amount = $request->amount;
            $description = $request->description;

            switch ($request->action) {
                case 'add':
                    $user->addBalance($amount, $description . ' (من الإدارة)');
                    $message = "تم إضافة {$amount} ر.س للرصيد بنجاح";
                    break;

                case 'subtract':
                    if ($user->remaining_balance < $amount) {
                        throw new \Exception('الرصيد المتبقي غير كافي للخصم');
                    }
                    $user->useBalance($amount, $description . ' (من الإدارة)');
                    $message = "تم خصم {$amount} ر.س من الرصيد بنجاح";
                    break;

                case 'set':
                    // حساب الفرق وتطبيقه
                    $currentBalance = $user->available_balance;
                    $difference = $amount - $currentBalance;

                    if ($difference > 0) {
                        // إضافة رصيد
                        $user->addBalance($difference, $description . ' (تعديل الرصيد من الإدارة)');
                    } elseif ($difference < 0) {
                        // خصم رصيد
                        $user->decrement('available_balance', abs($difference));
                        $user->balanceTransactions()->create([
                            'type' => 'debit',
                            'amount' => abs($difference),
                            'description' => $description . ' (تعديل الرصيد من الإدارة)',
                            'balance_before' => $currentBalance,
                            'balance_after' => $amount,
                        ]);
                    }
                    $message = "تم تعديل الرصيد إلى {$amount} ر.س بنجاح";
                    break;
            }

            DB::commit();
            return response()->json(['success' => $message]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
