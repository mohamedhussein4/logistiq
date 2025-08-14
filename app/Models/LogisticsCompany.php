<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticsCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_type',
        'contact_person',
        'email',
        'phone',
        'address',
        'commercial_register',
        'credit_limit',
        'used_credit',
        'available_balance',
        'total_funded',
        'total_requests',
        'last_request_status',
        'status',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'used_credit' => 'decimal:2',
        'available_balance' => 'decimal:2',
        'total_funded' => 'decimal:2',
        'total_requests' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fundingRequests()
    {
        return $this->hasMany(FundingRequest::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function linkingServices()
    {
        return $this->hasMany(LinkingService::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('status', 'active');
        });
    }

    // Helper methods
    public function getFormattedBalanceAttribute()
    {
        return number_format((float) ($this->available_balance ?? 0), 0) . ' ر.س';
    }

    public function getFormattedTotalFundedAttribute()
    {
        return number_format((float) ($this->total_funded ?? 0), 0) . ' ر.س';
    }
}
