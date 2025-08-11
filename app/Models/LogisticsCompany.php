<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticsCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'available_balance',
        'total_funded',
        'total_requests',
        'last_request_status',
    ];

    protected $casts = [
        'available_balance' => 'decimal:2',
        'total_funded' => 'decimal:2',
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
        return number_format($this->available_balance, 0) . ' ر.س';
    }

    public function getFormattedTotalFundedAttribute()
    {
        return number_format($this->total_funded, 0) . ' ر.س';
    }
}
