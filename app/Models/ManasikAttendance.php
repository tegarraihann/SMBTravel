<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManasikAttendance extends Model
{
    protected $fillable = [
        'manasik_session_id',
        'user_id',
        'status',
        'registered_at',
        'attended_at',
        'notes',
        'completion_score'
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'attended_at' => 'datetime',
        'completion_score' => 'decimal:2'
    ];

    public function manasikSession(): BelongsTo
    {
        return $this->belongsTo(ManasikSession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'registered' => 'Terdaftar',
            'attended' => 'Hadir',
            'absent' => 'Tidak Hadir',
            'excused' => 'Izin',
            default => ucfirst($this->status)
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'registered' => 'bg-blue-100 text-blue-800',
            'attended' => 'bg-green-100 text-green-800',
            'absent' => 'bg-red-100 text-red-800',
            'excused' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function markAttended(): bool
    {
        $this->status = 'attended';
        $this->attended_at = now();
        return $this->save();
    }

    public function markAbsent(): bool
    {
        $this->status = 'absent';
        return $this->save();
    }

    public function markExcused(string $reason = null): bool
    {
        $this->status = 'excused';
        if ($reason) {
            $this->notes = $reason;
        }
        return $this->save();
    }
}