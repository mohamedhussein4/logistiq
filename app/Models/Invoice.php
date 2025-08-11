<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_company_id',
        'logistics_company_id',
        'invoice_number',
        'original_amount',
        'paid_amount',
        'remaining_amount',
        'due_date',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    // Payment status constants
    const PAYMENT_STATUS_UNPAID = 'unpaid';
    const PAYMENT_STATUS_PARTIAL = 'partial';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_INSTALLMENT = 'installment';

    // Relationships
    public function serviceCompany()
    {
        return $this->belongsTo(ServiceCompany::class, 'service_company_id');
    }

    public function logisticsCompany()
    {
        return $this->belongsTo(LogisticsCompany::class, 'logistics_company_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function installmentPlan()
    {
        return $this->hasOne(InstallmentPlan::class);
    }

    // Static methods
    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT => 'مسودة',
            self::STATUS_SENT => 'مرسلة',
            self::STATUS_OVERDUE => 'متأخرة',
            self::STATUS_PAID => 'مدفوعة',
            self::STATUS_CANCELLED => 'ملغية',
        ];
    }

    public static function getPaymentStatuses()
    {
        return [
            self::PAYMENT_STATUS_UNPAID => 'غير مدفوع',
            self::PAYMENT_STATUS_PARTIAL => 'مدفوع جزئياً',
            self::PAYMENT_STATUS_PAID => 'مدفوع',
            self::PAYMENT_STATUS_INSTALLMENT => 'تقسيط',
        ];
    }

    // Helper methods
    public function getFormattedOriginalAmountAttribute()
    {
        return number_format($this->original_amount, 0) . ' ر.س';
    }

    public function getFormattedPaidAmountAttribute()
    {
        return number_format($this->paid_amount, 0) . ' ر.س';
    }

    public function getFormattedRemainingAmountAttribute()
    {
        return number_format($this->remaining_amount, 0) . ' ر.س';
    }

    public function getStatusNameAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getPaymentStatusNameAttribute()
    {
        return self::getPaymentStatuses()[$this->payment_status] ?? $this->payment_status;
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->due_date->isPast() && $this->payment_status !== self::PAYMENT_STATUS_PAID) {
            return $this->due_date->diffInDays(now());
        }
        return 0;
    }

    public function getDaysUntilDueAttribute()
    {
        if ($this->due_date->isFuture()) {
            return now()->diffInDays($this->due_date);
        }
        return 0;
    }

    // Scopes
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('payment_status', '!=', self::PAYMENT_STATUS_PAID);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>', now())
                    ->where('payment_status', '!=', self::PAYMENT_STATUS_PAID);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_STATUS_PAID);
    }

    // Generate invoice number
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-';
        $date = now()->format('Ymd');
        $lastInvoice = self::whereDate('created_at', today())
                          ->orderBy('id', 'desc')
                          ->first();

        $sequence = $lastInvoice ? intval(substr($lastInvoice->invoice_number, -2)) + 1 : 1;

        return $prefix . $date . str_pad($sequence, 2, '0', STR_PAD_LEFT);
    }
}
