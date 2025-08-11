<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDebt extends Model
{
    use HasFactory;

    protected $fillable = [
        'funding_request_id',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'amount',
        'due_date',
        'invoice_document',
        'status',
        'created_user_id',
        'created_invoice_id',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACCOUNT_CREATED = 'account_created';
    const STATUS_INVOICE_SENT = 'invoice_sent';
    const STATUS_PAID = 'paid';

    /**
     * Get the funding request that this debt belongs to
     */
    public function fundingRequest()
    {
        return $this->belongsTo(FundingRequest::class);
    }

    /**
     * Get the created user (service company)
     */
    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    /**
     * Get the created invoice
     */
    public function createdInvoice()
    {
        return $this->belongsTo(Invoice::class, 'created_invoice_id');
    }

    /**
     * Get status labels in Arabic
     */
    public static function getStatusLabels()
    {
        return [
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_ACCOUNT_CREATED => 'تم إنشاء الحساب',
            self::STATUS_INVOICE_SENT => 'تم إرسال الفاتورة',
            self::STATUS_PAID => 'تم السداد',
        ];
    }

    /**
     * Get status label attribute
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatusLabels()[$this->status] ?? $this->status;
    }

    /**
     * Scope for pending debts
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for paid debts
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }
}