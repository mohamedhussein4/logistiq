<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'logistics_company_id',
        'amount',
        'reason',
        'description',
        'status',
        'requested_at',
        'approved_at',
        'disbursed_at',
        'documents',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'documents' => 'array',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_DISBURSED = 'disbursed';
    const STATUS_REJECTED = 'rejected';

    // Reason constants
    const REASON_OPERATIONAL = 'operational';
    const REASON_EXPANSION = 'expansion';
    const REASON_EQUIPMENT = 'equipment';
    const REASON_EMERGENCY = 'emergency';
    const REASON_OTHER = 'other';

    // Relationships
    public function logisticsCompany()
    {
        return $this->belongsTo(User::class, 'logistics_company_id');
    }

    /**
     * Get the client debts for this funding request
     */
    public function clientDebts()
    {
        return $this->hasMany(ClientDebt::class);
    }

    // Static methods
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_UNDER_REVIEW => 'قيد المراجعة',
            self::STATUS_APPROVED => 'تم الموافقة',
            self::STATUS_DISBURSED => 'تم الصرف',
            self::STATUS_REJECTED => 'مرفوض',
        ];
    }

    public static function getReasons()
    {
        return [
            self::REASON_OPERATIONAL => 'تمويل العمليات التشغيلية',
            self::REASON_EXPANSION => 'التوسع في الأعمال',
            self::REASON_EQUIPMENT => 'شراء معدات جديدة',
            self::REASON_EMERGENCY => 'طارئ',
            self::REASON_OTHER => 'أخرى',
        ];
    }

    // Helper methods
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0) . ' ر.س';
    }

    public function getStatusNameAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getReasonNameAttribute()
    {
        return self::getReasons()[$this->reason] ?? $this->reason;
    }

    /**
     * Get total amount of client debts
     */
    public function getTotalClientDebtsAttribute()
    {
        return $this->clientDebts->sum('amount');
    }

    /**
     * Get paid amount from client debts
     */
    public function getPaidClientDebtsAttribute()
    {
        return $this->clientDebts->where('status', ClientDebt::STATUS_PAID)->sum('amount');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeDisbursed($query)
    {
        return $query->where('status', self::STATUS_DISBURSED);
    }
}
