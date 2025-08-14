<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // تعيين القيمة الافتراضية لنوع المستخدم
        if (empty($data['user_type'])) {
            $data['user_type'] = 'regular';
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'user_type' => ['required', 'string', 'in:logistics,regular'],
        ];

        // إضافة قواعد الشركة فقط إذا كان النوع logistics
        if (isset($data['user_type']) && $data['user_type'] === 'logistics') {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['company_license'] = ['required', 'string', 'max:255'];
            $rules['company_address'] = ['required', 'string', 'max:500'];
            $rules['contact_person'] = ['required', 'string', 'max:255'];
            $rules['company_type'] = ['nullable', 'string', 'in:transport,warehouse,freight,mixed'];
        }

        return Validator::make($data, $rules, [
            'company_name.required' => 'اسم الشركة مطلوب للشركات اللوجستية',
            'company_license.required' => 'رقم الترخيص مطلوب للشركات اللوجستية',
            'company_address.required' => 'عنوان الشركة مطلوب للشركات اللوجستية',
            'contact_person.required' => 'الشخص المسؤول مطلوب للشركات اللوجستية',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // تعيين القيمة الافتراضية لنوع المستخدم
        $userType = $data['user_type'] ?? 'regular';

        // إنشاء المستخدم مع البيانات الأساسية فقط
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'user_type' => $userType,
        ];

        // إضافة بيانات الشركة فقط إذا كان المستخدم شركة لوجيستية
        if ($userType === 'logistics') {
            $userData['company_name'] = $data['company_name'] ?? null;
            $userData['company_registration'] = $data['company_license'] ?? null;
            $userData['address'] = $data['company_address'] ?? null;
            $userData['contact_person'] = $data['contact_person'] ?? null;
        }

        $user = User::create($userData);

        // إذا كان المستخدم شركة لوجيستية، إنشاء سجل في جدول logistics_companies
        if ($userType === 'logistics') {
            \App\Models\LogisticsCompany::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'],
                'contact_person' => $data['contact_person'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['company_address'],
                'commercial_register' => $data['company_license'],
                'company_type' => $data['company_type'] ?? 'mixed',
                'credit_limit' => 100000, // الحد الائتماني الافتراضي
                'available_balance' => 0,
                'total_funded' => 0,
                'total_requests' => 0,
                'status' => 'pending', // في انتظار الموافقة
            ]);
        }

        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}
