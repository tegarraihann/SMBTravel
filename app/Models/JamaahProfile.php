<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JamaahProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_ktp',
        'pekerjaan',
        'alamat',
        'no_telepon',
        'no_darurat',
        'hubungan_darurat',
        'paket_id',
        'catatan',
        'current_step',
        'status',
        'dp_paid',
        'registration_completed_at'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'dp_paid' => 'decimal:2',
        'registration_completed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isRegistrationComplete(): bool
    {
        return !is_null($this->registration_completed_at);
    }

    public function getCurrentStep(): int
    {
        if (!$this->isRegistrationComplete()) {
            return 1; // Still in registration phase
        }
        return $this->current_step;
    }
}
