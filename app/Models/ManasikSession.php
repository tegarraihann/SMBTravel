<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ManasikSession extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'session_date',
        'start_time',
        'end_time',
        'location',
        'online_link',
        'instructor_name',
        'instructor_contact',
        'materials',
        'is_mandatory',
        'max_participants',
        'status'
    ];

    protected $casts = [
        'session_date' => 'datetime',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'materials' => 'array',
        'is_mandatory' => 'boolean',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(ManasikAttendance::class);
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->session_date->format('d/m/Y');
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'online' => 'Online',
            'offline' => 'Offline',
            'hybrid' => 'Hybrid (Online & Offline)',
            default => ucfirst($this->type)
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'Terjadwal',
            'ongoing' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status)
        };
    }

    public function getParticipantCountAttribute(): int
    {
        return $this->attendances()->whereIn('status', ['registered', 'attended'])->count();
    }

    public function getAttendanceCountAttribute(): int
    {
        return $this->attendances()->where('status', 'attended')->count();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('session_date', '>=', now())
                    ->where('status', 'scheduled')
                    ->orderBy('session_date');
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function hasUserRegistered(int $userId): bool
    {
        return $this->attendances()->where('user_id', $userId)->exists();
    }

    public function getUserAttendance(int $userId): ?ManasikAttendance
    {
        return $this->attendances()->where('user_id', $userId)->first();
    }
}