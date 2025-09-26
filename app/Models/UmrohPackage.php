<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UmrohPackage extends Model
{
    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga',
        'durasi_hari',
        'fasilitas',
        'jadwal_keberangkatan',
        'status'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'fasilitas' => 'array',
        'jadwal_keberangkatan' => 'array'
    ];

    /**
     * Get available schedules for this package
     */
    public function getAvailableSchedules()
    {
        if (!$this->jadwal_keberangkatan) {
            return collect();
        }

        return collect($this->jadwal_keberangkatan)
            ->where('status', 'tersedia')
            ->where('tanggal', '>=', now()->format('Y-m-d'))
            ->sortBy('tanggal');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Get package display name with price
     */
    public function getDisplayNameAttribute()
    {
        return $this->nama_paket . ' - ' . $this->formatted_price;
    }

    /**
     * Check if package is active
     */
    public function isActive()
    {
        return $this->status === 'aktif';
    }

    /**
     * Get jamaah profiles using this package
     */
    public function jamaahProfiles(): HasMany
    {
        return $this->hasMany(JamaahProfile::class, 'paket_id');
    }

    /**
     * Get schedules for this package
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(UmrohSchedule::class, 'umroh_package_id');
    }

    /**
     * Get available schedules for this package
     */
    public function availableSchedules()
    {
        return $this->schedules()->available()->orderBy('tanggal_lengkap');
    }

    /**
     * Scope for active packages only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Get total quota for a specific schedule
     */
    public function getScheduleQuota($tanggal)
    {
        if (!$this->jadwal_keberangkatan) {
            return 0;
        }

        $schedule = collect($this->jadwal_keberangkatan)
            ->firstWhere('tanggal', $tanggal);

        return $schedule ? $schedule['kuota'] : 0;
    }

    /**
     * Get booked count for a specific schedule
     */
    public function getScheduleBookedCount($tanggal)
    {
        return $this->jamaahProfiles()
            ->where('rencana_keberangkatan', $tanggal)
            ->count();
    }

    /**
     * Check if schedule is available for booking
     */
    public function isScheduleAvailable($tanggal)
    {
        $quota = $this->getScheduleQuota($tanggal);
        $booked = $this->getScheduleBookedCount($tanggal);

        return $quota > $booked;
    }

    /**
     * Update schedule status based on availability
     */
    public function updateScheduleStatus($tanggal)
    {
        if (!$this->jadwal_keberangkatan) {
            return false;
        }

        $schedules = collect($this->jadwal_keberangkatan);

        $schedules = $schedules->map(function ($schedule) use ($tanggal) {
            if ($schedule['tanggal'] === $tanggal) {
                $schedule['status'] = $this->isScheduleAvailable($tanggal) ? 'tersedia' : 'penuh';
            }
            return $schedule;
        });

        $this->jadwal_keberangkatan = $schedules->toArray();
        return $this->save();
    }
}