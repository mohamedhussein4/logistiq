<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'total_amount',
        'installment_count',
        'monthly_amount',
        'start_date',
        'status',
        'reason',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'monthly_amount' => 'decimal:2',
        'start_date' => 'date',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function installmentPayments()
    {
        return $this->hasMany(InstallmentPayment::class);
    }

    // Static methods
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'قيد المراجعة',
            self::STATUS_APPROVED => 'موافق عليه',
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
        ];
    }

    // Helper methods
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 0) . ' ر.س';
    }

    public function getFormattedMonthlyAmountAttribute()
    {
        return number_format($this->monthly_amount, 0) . ' ر.س';
    }

    public function getStatusNameAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getPaidInstallmentsCountAttribute()
    {
        return $this->installmentPayments()->where('status', 'paid')->count();
    }

    public function getRemainingInstallmentsCountAttribute()
    {
        return $this->installment_count - $this->getPaidInstallmentsCountAttribute();
    }

    public function getRemainingAmountAttribute()
    {
        $paidAmount = $this->installmentPayments()
                          ->where('status', 'paid')
                          ->sum('amount');
        return $this->total_amount - $paidAmount;
    }

    // Create installment payments when plan is approved
    public function createInstallmentPayments()
    {
        if ($this->status === self::STATUS_APPROVED) {
            for ($i = 1; $i <= $this->installment_count; $i++) {
                $dueDate = $this->start_date->copy()->addMonths($i - 1);

                InstallmentPayment::create([
                    'installment_plan_id' => $this->id,
                    'installment_number' => $i,
                    'amount' => $this->monthly_amount,
                    'due_date' => $dueDate,
                    'status' => 'pending',
                ]);
            }
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
}
