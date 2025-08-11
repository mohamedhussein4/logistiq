<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_address',
        'contact_person',
        'website',
        'description',
        'logo',
        'documents',
        'verification_status',
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Verification status constants
    const VERIFICATION_PENDING = 'pending';
    const VERIFICATION_APPROVED = 'approved';
    const VERIFICATION_REJECTED = 'rejected';

    public static function getVerificationStatuses()
    {
        return [
            self::VERIFICATION_PENDING => 'قيد المراجعة',
            self::VERIFICATION_APPROVED => 'موافق عليه',
            self::VERIFICATION_REJECTED => 'مرفوض',
        ];
    }
}
