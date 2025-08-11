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

    // العلاقات للفواتير والمدفوعات
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'service_company_id');
    }

    public function logisticsInvoices()
    {
        return $this->hasMany(Invoice::class, 'logistics_company_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'service_company_id');
    }

    public function fundingRequests()
    {
        return $this->hasMany(FundingRequest::class, 'logistics_company_id');
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
}
