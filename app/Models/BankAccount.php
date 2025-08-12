<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_name',
        'account_number',
        'iban',
        'swift_code',
        'branch_name',
        'branch_code',
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

    // Relationships
    public function paymentRequests()
    {
        return $this->hasMany(PaymentRequest::class, 'payment_account_id')
                    ->where('payment_account_type', 'bank_account');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('bank_name');
    }

    // Helper methods
    public function getStatusNameAttribute()
    {
        return [
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_INACTIVE => 'غير نشط',
        ][$this->status] ?? $this->status;
    }

    public function getFormattedAccountNumberAttribute()
    {
        return $this->account_number;
    }

    public function getFormattedIbanAttribute()
    {
        return $this->iban ? strtoupper($this->iban) : null;
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_INACTIVE => 'غير نشط',
        ];
    }
}
