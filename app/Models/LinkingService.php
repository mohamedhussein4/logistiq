<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkingService extends Model
{
    use HasFactory;

    protected $fillable = [
        'logistics_company_id',
        'service_company_id',
        'service_type',
        'status',
        'amount',
        'commission',
        'linked_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission' => 'decimal:2',
        'linked_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Service type constants
    const SERVICE_FINANCING = 'financing';
    const SERVICE_LOGISTICS = 'logistics';
    const SERVICE_WAREHOUSING = 'warehousing';
    const SERVICE_DISTRIBUTION = 'distribution';

    // Relationships
    public function logisticsCompany()
    {
        return $this->belongsTo(LogisticsCompany::class);
    }

    public function serviceCompany()
    {
        return $this->belongsTo(ServiceCompany::class);
    }

    // Static methods
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'قيد المراجعة',
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
        ];
    }

    public static function getServiceTypes()
    {
        return [
            self::SERVICE_FINANCING => 'خدمات التمويل',
            self::SERVICE_LOGISTICS => 'خدمات لوجستية',
            self::SERVICE_WAREHOUSING => 'خدمات التخزين',
            self::SERVICE_DISTRIBUTION => 'خدمات التوزيع',
        ];
    }

    // Helper methods
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0) . ' ر.س';
    }

    public function getFormattedCommissionAttribute()
    {
        return number_format($this->commission, 0) . ' ر.س';
    }

    public function getStatusNameAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getServiceTypeNameAttribute()
    {
        return self::getServiceTypes()[$this->service_type] ?? $this->service_type;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
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
