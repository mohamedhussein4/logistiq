<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array'
    ];

    // Constants for transaction types
    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';
    const TYPE_INITIAL = 'initial';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeCredits($query)
    {
        return $query->where('type', self::TYPE_CREDIT);
    }

    public function scopeDebits($query)
    {
        return $query->where('type', self::TYPE_DEBIT);
    }

    public function scopeInitial($query)
    {
        return $query->where('type', self::TYPE_INITIAL);
    }
}
