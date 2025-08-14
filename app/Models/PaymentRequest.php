<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_number',
        'payment_type',
        'related_id',
        'related_type',
        'amount',
        'payment_method',
        'payment_account_id',
        'payment_account_type',
        'payment_notes',
        'status',
        'payment_date',
        'processed_at',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'processed_at' => 'datetime',
    ];

    // Payment types
    const TYPE_PRODUCT_ORDER = 'product_order';
    const TYPE_INVOICE = 'invoice';
    const TYPE_FUNDING_REQUEST = 'funding_request';
    const TYPE_OTHER = 'other';

    // Payment methods
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_ELECTRONIC_WALLET = 'electronic_wallet';
    const METHOD_CASH = 'cash';
    const METHOD_CHECK = 'check';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentProofs()
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'payment_account_id');
    }


    public function paymentAccount()
    {
        return $this->morphTo('paymentAccount', 'payment_account_type', 'payment_account_id');
    }

    // Helper method للحصول على نوع الحساب
    public function getPaymentAccountTypeTextAttribute()
    {
        if ($this->attributes['payment_account_type'] === BankAccount::class) {
            return 'bank_account';
        }
        return 'unknown';
    }

    // Accessor للحصول على اسم الحساب
    public function getPaymentAccountNameAttribute()
    {
        if ($this->payment_method === 'bank_transfer' && $this->paymentAccount) {
            return $this->paymentAccount->bank_name . ' - ' . $this->paymentAccount->account_number;
        }
        return 'غير محدد';
    }

    // Polymorphic relationships
    public function related()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('payment_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    // Helper methods
    public function getPaymentTypeNameAttribute()
    {
        return [
            self::TYPE_PRODUCT_ORDER => 'طلب منتج',
            self::TYPE_INVOICE => 'فاتورة',
            self::TYPE_FUNDING_REQUEST => 'طلب تمويل',
            self::TYPE_OTHER => 'أخرى',
        ][$this->payment_type] ?? $this->payment_type;
    }

    public function getPaymentMethodNameAttribute()
    {
        return [
            self::METHOD_BANK_TRANSFER => 'تحويل بنكي',
            self::METHOD_ELECTRONIC_WALLET => 'محفظة إلكترونية',
            self::METHOD_CASH => 'نقداً',
            self::METHOD_CHECK => 'شيك',
        ][$this->payment_method] ?? $this->payment_method;
    }

    public function getStatusNameAttribute()
    {
        return [
            self::STATUS_PENDING => 'معلق',
            self::STATUS_PROCESSING => 'قيد المعالجة',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_FAILED => 'فشل',
            self::STATUS_CANCELLED => 'ملغي',
        ][$this->status] ?? $this->status;
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0) . ' ر.س';
    }

    public function getFormattedRequestNumberAttribute()
    {
        return 'PAY-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function canBeProcessed()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    public function markAsProcessing()
    {
        $this->update([
            'status' => self::STATUS_PROCESSING,
            'processed_at' => now(),
        ]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'processed_at' => now(),
        ]);
    }

    public function markAsFailed($notes = null)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'admin_notes' => $notes,
            'processed_at' => now(),
        ]);
    }

    public static function getPaymentTypes()
    {
        return [
            self::TYPE_PRODUCT_ORDER => 'طلب منتج',
            self::TYPE_INVOICE => 'فاتورة',
            self::TYPE_FUNDING_REQUEST => 'طلب تمويل',
            self::TYPE_OTHER => 'أخرى',
        ];
    }

    public static function getPaymentMethods()
    {
        return [
            self::METHOD_BANK_TRANSFER => 'تحويل بنكي',
            self::METHOD_ELECTRONIC_WALLET => 'محفظة إلكترونية',
            self::METHOD_CASH => 'نقداً',
            self::METHOD_CHECK => 'شيك',
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'معلق',
            self::STATUS_PROCESSING => 'قيد المعالجة',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_FAILED => 'فشل',
            self::STATUS_CANCELLED => 'ملغي',
        ];
    }
}
