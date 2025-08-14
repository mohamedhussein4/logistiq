<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
        'company_name',
        'company_registration',
        'status',
        'available_balance',
        'used_balance',
        'total_balance',
        'admin_notes',
        'address',
        'contact_person',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'available_balance' => 'decimal:2',
            'used_balance' => 'decimal:2',
            'total_balance' => 'decimal:2',
        ];
    }

    // User type constants
    const TYPE_ADMIN = 'admin';
    const TYPE_LOGISTICS = 'logistics';
    const TYPE_SERVICE_COMPANY = 'service_company';
    const TYPE_REGULAR = 'regular';

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_PENDING = 'pending';
    const STATUS_SUSPENDED = 'suspended';

    // Relationships
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function logisticsCompany()
    {
        return $this->hasOne(LogisticsCompany::class);
    }

    public function serviceCompany()
    {
        return $this->hasOne(ServiceCompany::class);
    }

    public function productOrders()
    {
        return $this->hasMany(ProductOrder::class);
    }

    // العلاقات للفواتير والمدفوعات (سيتم استخدام serviceInvoices بدلاً منها)
    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, ServiceCompany::class, 'user_id', 'service_company_id');
    }

    public function logisticsInvoices()
    {
        return $this->hasManyThrough(Invoice::class, LogisticsCompany::class, 'user_id', 'logistics_company_id');
    }

    public function serviceInvoices()
    {
        return $this->hasManyThrough(Invoice::class, ServiceCompany::class, 'user_id', 'service_company_id');
    }

    public function fundingRequests()
    {
        return $this->hasManyThrough(FundingRequest::class, LogisticsCompany::class, 'user_id', 'logistics_company_id');
    }

    // Static methods
    public static function getUserTypes()
    {
        return [
            self::TYPE_ADMIN => 'مدير النظام',
            self::TYPE_LOGISTICS => 'شركة لوجستية',
            self::TYPE_SERVICE_COMPANY => 'شركة طالبة للخدمة',
            self::TYPE_REGULAR => 'مستخدم عادي',
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_INACTIVE => 'غير نشط',
            self::STATUS_PENDING => 'قيد المراجعة',
            self::STATUS_SUSPENDED => 'معلق',
        ];
    }

    // Helper methods
    public function getUserTypeNameAttribute()
    {
        return self::getUserTypes()[$this->user_type] ?? $this->user_type;
    }

    public function getStatusNameAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function isAdmin()
    {
        return $this->user_type === self::TYPE_ADMIN;
    }

    public function isLogisticsCompany()
    {
        return $this->user_type === self::TYPE_LOGISTICS;
    }

    public function isServiceCompany()
    {
        return $this->user_type === self::TYPE_SERVICE_COMPANY;
    }

    public function isRegularUser()
    {
        return $this->user_type === self::TYPE_REGULAR;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('user_type', $type);
    }

    public function scopeLogisticsCompanies($query)
    {
        return $query->where('user_type', self::TYPE_LOGISTICS);
    }

    public function scopeServiceCompanies($query)
    {
        return $query->where('user_type', self::TYPE_SERVICE_COMPANY);
    }

    public function scopeRegularUsers($query)
    {
        return $query->where('user_type', self::TYPE_REGULAR);
    }

    // Balance Management Methods (للشركات اللوجستية فقط)

    /**
     * الحصول على الرصيد المتبقي
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->available_balance - $this->used_balance;
    }

    /**
     * إضافة رصيد للمستخدم
     */
    public function addBalance($amount, $description = null)
    {
        if (!$this->isLogisticsCompany()) {
            throw new \Exception('إدارة الرصيد متاحة للشركات اللوجستية فقط');
        }

        $this->increment('available_balance', $amount);
        $this->increment('total_balance', $amount);

        // تسجيل العملية في سجل الرصيد
        $this->balanceTransactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'description' => $description ?: 'إضافة رصيد',
            'balance_before' => $this->available_balance - $amount,
            'balance_after' => $this->available_balance,
        ]);

        return $this;
    }

    /**
     * استخدام رصيد من المستخدم
     */
    public function useBalance($amount, $description = null)
    {
        if (!$this->isLogisticsCompany()) {
            throw new \Exception('إدارة الرصيد متاحة للشركات اللوجستية فقط');
        }

        if ($this->remaining_balance < $amount) {
            throw new \Exception('الرصيد المتبقي غير كافي');
        }

        $this->increment('used_balance', $amount);

        // تسجيل العملية في سجل الرصيد
        $this->balanceTransactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'description' => $description ?: 'استخدام رصيد',
            'balance_before' => $this->remaining_balance + $amount,
            'balance_after' => $this->remaining_balance,
        ]);

        return $this;
    }

    /**
     * تعيين الرصيد الافتراضي للشركات اللوجستية الجديدة
     */
    public function setDefaultBalance()
    {
        if (!$this->isLogisticsCompany()) {
            return $this;
        }

        $defaultBalance = \App\Models\Setting::get('default_logistics_balance', 200000);

        $this->update([
            'available_balance' => $defaultBalance,
            'total_balance' => $defaultBalance,
            'used_balance' => 0,
        ]);

        // تسجيل العملية
        $this->balanceTransactions()->create([
            'type' => 'initial',
            'amount' => $defaultBalance,
            'description' => 'رصيد افتراضي للشركة الجديدة',
            'balance_before' => 0,
            'balance_after' => $defaultBalance,
        ]);

        return $this;
    }

    /**
     * علاقة مع معاملات الرصيد
     */
    public function balanceTransactions()
    {
        return $this->hasMany(\App\Models\BalanceTransaction::class);
    }

    /**
     * Boot method لتطبيق الأحداث على Model
     */
    protected static function boot()
    {
        parent::boot();

        // منح الرصيد الافتراضي للشركات اللوجستية الجديدة
        static::created(function ($user) {
            if ($user->isLogisticsCompany()) {
                $user->setDefaultBalance();
            }
        });
    }
}
