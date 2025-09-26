<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class InstallmentPayment extends Model
{
    protected $fillable = [
        'jamaah_profile_id',
        'installment_number',
        'due_date',
        'amount',
        'status',
        'payment_type',
        'description',
        'paid_at',
        'payment_proof',
        'approved_by',
        'approved_at',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function jamaahProfile(): BelongsTo
    {
        return $this->belongsTo(JamaahProfile::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', now());
                    });
    }

    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('status', 'pending')
                    ->whereBetween('due_date', [now(), now()->addDays($days)]);
    }

    // Accessors
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'overdue' => 'Terlambat',
            'waived' => 'Dibebaskan',
            default => ucfirst($this->status)
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'overdue' => 'bg-red-100 text-red-800',
            'waived' => 'bg-gray-100 text-gray-600',
            default => 'bg-gray-100 text-gray-600'
        };
    }

    public function getDaysUntilDueAttribute(): int
    {
        return now()->diffInDays($this->due_date, false);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'pending' && $this->due_date < now()->startOfDay();
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->status === 'pending' &&
               $this->due_date >= now()->startOfDay() &&
               $this->due_date <= now()->addDays(7)->endOfDay();
    }

    // Methods
    public function markAsPaid(string $paymentProof = null, string $notes = null): bool
    {
        return $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_proof' => $paymentProof,
            'notes' => $notes
        ]);
    }

    public function approve(int $approvedBy, string $notes = null): bool
    {
        return $this->update([
            'approved_by' => $approvedBy,
            'approved_at' => now(),
            'notes' => $notes
        ]);
    }

    public function markAsOverdue(): bool
    {
        if ($this->status === 'pending' && $this->due_date < now()->startOfDay()) {
            return $this->update(['status' => 'overdue']);
        }
        return false;
    }

    public function waive(int $waivedBy, string $reason): bool
    {
        return $this->update([
            'status' => 'waived',
            'approved_by' => $waivedBy,
            'approved_at' => now(),
            'notes' => 'Dibebaskan: ' . $reason
        ]);
    }
}