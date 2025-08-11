<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    // Payment method constants
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_ONLINE_PAYMENT = 'online_payment';
    const METHOD_CHECK = 'check';
    const METHOD_CASH = 'cash';

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Static methods
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'قيد المراجعة',
            self::STATUS_CONFIRMED => 'مؤكد',
            self::STATUS_FAILED => 'فشل',
            self::STATUS_CANCELLED => 'ملغي',
        ];
    }

    public static function getPaymentMethods()
    {
        return [
            self::METHOD_BANK_TRANSFER => 'تحويل بنكي',
            self::METHOD_ONLINE_PAYMENT => 'دفع إلكتروني',
            self::METHOD_CHECK => 'شيك',
            self::METHOD_CASH => 'نقداً',
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

    public function getPaymentMethodNameAttribute()
    {
        return self::getPaymentMethods()[$this->payment_method] ?? $this->payment_method;
    }

    // Scopes
    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
