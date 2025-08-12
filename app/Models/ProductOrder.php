<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_amount',
        'delivery_address',
        'notes',
        'status',
        'ordered_at',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'ordered_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function paymentRequests()
    {
        return $this->morphMany(PaymentRequest::class, 'related');
    }

    // Static methods
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_CONFIRMED => 'مؤكد',
            self::STATUS_PROCESSING => 'قيد التجهيز',
            self::STATUS_SHIPPED => 'تم الشحن',
            self::STATUS_DELIVERED => 'تم التسليم',
            self::STATUS_CANCELLED => 'ملغي',
        ];
    }

    // Helper methods
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 0) . ' ر.س';
    }

    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 0) . ' ر.س';
    }

    public function getStatusNameAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getOrderNumberAttribute()
    {
        return 'ORD-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_CONFIRMED,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED
        ]);
    }

    // Calculate total amount based on quantity and unit price
    public static function calculateTotal($quantity, $unitPrice)
    {
        return $quantity * $unitPrice;
    }

    // Generate order number
    public static function generateOrderNumber()
    {
        $lastOrder = self::orderBy('id', 'desc')->first();
        $nextId = $lastOrder ? $lastOrder->id + 1 : 1;
        return 'ORD-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }
}
