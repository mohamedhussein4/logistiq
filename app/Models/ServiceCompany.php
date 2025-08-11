<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_outstanding',
        'total_paid',
        'payment_status',
        'credit_limit',
    ];

    protected $casts = [
        'total_outstanding' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'credit_limit' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function linkingServices()
    {
        return $this->hasMany(LinkingService::class);
    }

    // Payment status constants
    const PAYMENT_REGULAR = 'regular';
    const PAYMENT_OVERDUE = 'overdue';
    const PAYMENT_UNDER_REVIEW = 'under_review';

    public static function getPaymentStatuses()
    {
        return [
            self::PAYMENT_REGULAR => 'منتظم',
            self::PAYMENT_OVERDUE => 'متأخر',
            self::PAYMENT_UNDER_REVIEW => 'تحت المراقبة',
        ];
    }

    // Helper methods
    public function getFormattedOutstandingAttribute()
    {
        return number_format($this->total_outstanding, 0) . ' ر.س';
    }

    public function getFormattedTotalPaidAttribute()
    {
        return number_format($this->total_paid, 0) . ' ر.س';
    }

    public function getOverdueAmount()
    {
        return $this->invoices()
            ->where('due_date', '<', now())
            ->where('payment_status', '!=', 'paid')
            ->sum('remaining_amount');
    }

    public function getUpcomingAmount()
    {
        return $this->invoices()
            ->where('due_date', '>', now())
            ->where('payment_status', '!=', 'paid')
            ->sum('remaining_amount');
    }
}
