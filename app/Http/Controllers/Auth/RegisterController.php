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
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'user_type' => ['required', 'string', 'in:logistics,regular'],
            'company_name' => ['nullable:user_type,logistics', 'string', 'max:255'],
            'company_license' => ['nullable:user_type,logistics', 'string', 'max:255'],
            'company_address' => ['nullable:user_type,logistics', 'string', 'max:500'],
            'contact_person' => ['nullable:user_type,logistics', 'string', 'max:255'],
        ], [
            'company_name.required_if' => 'اسم الشركة مطلوب للشركات اللوجستية',
            'company_license.required_if' => 'رقم الترخيص مطلوب للشركات اللوجستية',
            'company_address.required_if' => 'عنوان الشركة مطلوب للشركات اللوجستية',
            'contact_person.required_if' => 'الشخص المسؤول مطلوب للشركات اللوجستية',
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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'user_type' => $data['user_type'],
        ]);

        // إذا كان المستخدم شركة لوجستية، إنشاء سجل في جدول logistics_companies
        if ($data['user_type'] === 'logistics') {
            \App\Models\LogisticsCompany::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'],
                'license_number' => $data['company_license'],
                'address' => $data['company_address'],
                'contact_person' => $data['contact_person'],
                'credit_limit' => 100000, // الحد الائتماني الافتراضي
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
