<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_request_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'description',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Relationships
    public function paymentRequest()
    {
        return $this->belongsTo(PaymentRequest::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // Helper methods
    public function getStatusNameAttribute()
    {
        return [
            self::STATUS_PENDING => 'معلق',
            self::STATUS_APPROVED => 'موافق عليه',
            self::STATUS_REJECTED => 'مرفوض',
        ][$this->status] ?? $this->status;
    }

    public function getFileTypeNameAttribute()
    {
        $extensions = [
            'image/jpeg' => 'صورة JPEG',
            'image/png' => 'صورة PNG',
            'image/gif' => 'صورة GIF',
            'application/pdf' => 'ملف PDF',
            'application/msword' => 'ملف Word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'ملف Word',
        ];

        return $extensions[$this->file_type] ?? 'ملف';
    }

    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function canBeApproved()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canBeRejected()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function approve($approvedBy = null)
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => $approvedBy ?? auth()->id(),
        ]);
    }

    public function reject($reason = null, $rejectedBy = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'approved_by' => $rejectedBy ?? auth()->id(),
        ]);
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'معلق',
            self::STATUS_APPROVED => 'موافق عليه',
            self::STATUS_REJECTED => 'مرفوض',
        ];
    }

    public static function getFileTypes()
    {
        return [
            'image/jpeg' => 'صورة JPEG',
            'image/png' => 'صورة PNG',
            'image/gif' => 'صورة GIF',
            'application/pdf' => 'ملف PDF',
            'application/msword' => 'ملف Word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'ملف Word',
        ];
    }
}
