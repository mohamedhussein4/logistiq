<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectronicWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_name',
        'wallet_type',
        'account_number',
        'account_name',
        'phone_number',
        'email',
        'notes',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    // Wallet types
    const TYPE_STC_PAY = 'stc_pay';
    const TYPE_MADA = 'mada';
    const TYPE_APPLE_PAY = 'apple_pay';
    const TYPE_GOOGLE_PAY = 'google_pay';
    const TYPE_PAYPAL = 'paypal';
    const TYPE_OTHER = 'other';

    // Relationships
    public function paymentRequests()
    {
        return $this->hasMany(PaymentRequest::class, 'payment_account_id')
                    ->where('payment_account_type', 'electronic_wallet');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('wallet_name');
    }

    // Helper methods
    public function getStatusNameAttribute()
    {
        return [
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_INACTIVE => 'غير نشط',
        ][$this->status] ?? $this->status;
    }

    public function getWalletTypeNameAttribute()
    {
        return [
            self::TYPE_STC_PAY => 'STC Pay',
            self::TYPE_MADA => 'مدى',
            self::TYPE_APPLE_PAY => 'Apple Pay',
            self::TYPE_GOOGLE_PAY => 'Google Pay',
            self::TYPE_PAYPAL => 'PayPal',
            self::TYPE_OTHER => 'أخرى',
        ][$this->wallet_type] ?? $this->wallet_type;
    }

    public function getFormattedAccountNumberAttribute()
    {
        return $this->account_number;
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_INACTIVE => 'غير نشط',
        ];
    }

    public static function getWalletTypes()
    {
        return [
            self::TYPE_STC_PAY => 'STC Pay',
            self::TYPE_MADA => 'مدى',
            self::TYPE_APPLE_PAY => 'Apple Pay',
            self::TYPE_GOOGLE_PAY => 'Google Pay',
            self::TYPE_PAYPAL => 'PayPal',
            self::TYPE_OTHER => 'أخرى',
        ];
    }
}
