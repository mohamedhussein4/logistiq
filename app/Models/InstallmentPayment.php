<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'installment_plan_id',
        'installment_number',
        'amount',
        'due_date',
        'paid_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    // Static methods
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_PAID => 'مدفوع',
            self::STATUS_OVERDUE => 'متأخر',
            self::STATUS_CANCELLED => 'ملغي',
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

    public function getDaysOverdueAttribute()
    {
        if ($this->due_date->isPast() && $this->status !== self::STATUS_PAID) {
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
                    ->where('status', '!=', self::STATUS_PAID);
    }

    public function scopeDueThisMonth($query)
    {
        return $query->whereBetween('due_date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->where('status', '!=', self::STATUS_PAID);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }
}
