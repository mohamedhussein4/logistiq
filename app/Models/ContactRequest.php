<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'phone',
        'email',
        'service_type',
        'message',
        'status',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_READ = 'read';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Service type constants
    const SERVICE_FINANCING_LINK = 'financing_link';
    const SERVICE_CLIENT_LINK = 'client_link';
    const SERVICE_TRACKING = 'tracking';
    const SERVICE_CONSULTATION = 'consultation';
    const SERVICE_PARTNERSHIP = 'partnership';

    // Static methods
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'جديد',
            self::STATUS_READ => 'تم قراءته',
            self::STATUS_IN_PROGRESS => 'قيد المعالجة',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
        ];
    }

    public static function getServiceTypes()
    {
        return [
            self::SERVICE_FINANCING_LINK => 'الربط مع شركات التمويل',
            self::SERVICE_CLIENT_LINK => 'الربط مع الشركات الطالبة للخدمة',
            self::SERVICE_TRACKING => 'أجهزة التتبع',
            self::SERVICE_CONSULTATION => 'استشارة عامة',
            self::SERVICE_PARTNERSHIP => 'شراكة استراتيجية',
        ];
    }

    // Helper methods
    public function getStatusNameAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getServiceTypeNameAttribute()
    {
        return self::getServiceTypes()[$this->service_type] ?? $this->service_type;
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeByServiceType($query, $serviceType)
    {
        return $query->where('service_type', $serviceType);
    }
}
