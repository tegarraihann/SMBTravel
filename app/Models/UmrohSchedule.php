<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class UmrohSchedule extends Model
{
    protected $fillable = [
        'umroh_package_id',
        'nama_jadwal',
        'tahun',
        'bulan',
        'tanggal_keberangkatan',
        'tanggal_lengkap',
        'kuota',
        'terisi',
        'status',
        'catatan',
        'biaya_tambahan'
    ];

    protected $casts = [
        'tanggal_lengkap' => 'date',
        'biaya_tambahan' => 'decimal:2',
        'tahun' => 'integer',
        'bulan' => 'integer',
        'tanggal_keberangkatan' => 'integer',
        'kuota' => 'integer',
        'terisi' => 'integer'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($schedule) {
            // Auto-generate tanggal_lengkap from tahun, bulan, tanggal_keberangkatan
            if ($schedule->tahun && $schedule->bulan && $schedule->tanggal_keberangkatan) {
                $schedule->tanggal_lengkap = Carbon::create(
                    $schedule->tahun,
                    $schedule->bulan,
                    $schedule->tanggal_keberangkatan
                )->format('Y-m-d');
            }

            // Auto-generate nama_jadwal if not provided
            if (!$schedule->nama_jadwal && $schedule->tahun && $schedule->bulan) {
                $bulanNames = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];

                $batchNumber = static::where('umroh_package_id', $schedule->umroh_package_id)
                    ->where('tahun', $schedule->tahun)
                    ->where('bulan', $schedule->bulan)
                    ->count() + 1;

                $schedule->nama_jadwal = $bulanNames[$schedule->bulan] . ' ' . $schedule->tahun . ' Batch ' . $batchNumber;
            }

            // Update status based on kuota
            if ($schedule->terisi >= $schedule->kuota) {
                $schedule->status = 'penuh';
            } elseif ($schedule->terisi > 0 && $schedule->status === 'penuh') {
                $schedule->status = 'tersedia';
            }
        });
    }

    /**
     * Relationship to UmrohPackage
     */
    public function umrohPackage(): BelongsTo
    {
        return $this->belongsTo(UmrohPackage::class);
    }

    /**
     * Relationship to JamaahProfile
     */
    public function jamaahProfiles(): HasMany
    {
        return $this->hasMany(JamaahProfile::class, 'umroh_schedule_id');
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->tanggal_lengkap->format('d F Y');
    }

    /**
     * Get formatted month year
     */
    public function getMonthYearAttribute()
    {
        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $bulanNames[$this->bulan] . ' ' . $this->tahun;
    }

    /**
     * Get available slot
     */
    public function getAvailableSlotAttribute()
    {
        return $this->kuota - $this->terisi;
    }

    /**
     * Get total price (package price + additional fee)
     */
    public function getTotalPriceAttribute()
    {
        return $this->umrohPackage->harga + $this->biaya_tambahan;
    }

    /**
     * Check if schedule is available for booking
     */
    public function isAvailable()
    {
        return $this->status === 'tersedia' &&
               $this->available_slot > 0 &&
               $this->tanggal_lengkap > now();
    }

    /**
     * Check if schedule is in the past
     */
    public function isPast()
    {
        return $this->tanggal_lengkap < now()->startOfDay();
    }

    /**
     * Increment terisi count
     */
    public function incrementTerisi()
    {
        $this->increment('terisi');
        $this->refresh();
    }

    /**
     * Decrement terisi count
     */
    public function decrementTerisi()
    {
        if ($this->terisi > 0) {
            $this->decrement('terisi');
            $this->refresh();
        }
    }

    /**
     * Scope for available schedules
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'tersedia')
                    ->where('tanggal_lengkap', '>', now())
                    ->whereRaw('terisi < kuota');
    }

    /**
     * Scope for specific year
     */
    public function scopeYear($query, $year)
    {
        return $query->where('tahun', $year);
    }

    /**
     * Scope for specific month
     */
    public function scopeMonth($query, $month)
    {
        return $query->where('bulan', $month);
    }

    /**
     * Scope for specific package
     */
    public function scopeForPackage($query, $packageId)
    {
        return $query->where('umroh_package_id', $packageId);
    }
}