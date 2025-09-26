<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JamaahProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nama_lengkap_bin_binti',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'usia',
        'nik',
        'pekerjaan',
        'alamat',
        'no_telepon',
        'no_darurat',
        'hubungan_darurat',
        'nama_marketing',
        'marketing_id',
        'agent_id',
        'paket_id',
        'umroh_schedule_id',
        'rencana_keberangkatan',
        'program_talangan',
        'cicilan_data',
        'catatan',
        'current_step',
        'status',
        'dp_paid',
        'dp_amount_paid',
        'sistem_pembayaran',
        'tgl_pelunasan',
        'jamaah_rombongan',
        'sumber_info_mmb',
        'kelengkapan_dokumen',
        'tanggal_diterima_perlengkapan',
        'detail_perlengkapan_diterima',
        'pic_penerima',
        'request_khusus',
        'bukti_transfer',
        'data_approved_by_cs',
        'payment_approved_by_admin',
        'cs_approval_at',
        'admin_approval_at',
        'foto_ktp',
        'foto_kk',
        'foto_diri',
        'foto_paspor',
        'scan_buku_nikah',
        'scan_akta_lahir',
        'documents_verified',
        'documents_uploaded_at',
        'document_notes',
        'registration_completed_at',
        // Payment fields baru
        'payment_type',
        'remaining_amount',
        'is_full_payment_upfront',
        'pelunasan_amount_paid',
        'bukti_pelunasan',
        'pelunasan_paid_at',
        'pelunasan_approved_by_admin',
        // Ticket & Visa fields
        'ticket_status',
        'ticket_processed_by',
        'ticket_processed_at',
        'ticket_notes',
        'ticket_file',
        'visa_status',
        'visa_processed_by',
        'visa_processed_at',
        'visa_notes',
        'visa_file'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'rencana_keberangkatan' => 'date',
        'tgl_pelunasan' => 'date',
        'tanggal_diterima_perlengkapan' => 'date',
        'dp_paid' => 'decimal:2',
        'dp_amount_paid' => 'decimal:2',
        'program_talangan' => 'boolean',
        'data_approved_by_cs' => 'boolean',
        'payment_approved_by_admin' => 'boolean',
        'documents_verified' => 'boolean',
        'cicilan_data' => 'array',
        'jamaah_rombongan' => 'array',
        'cs_approval_at' => 'datetime',
        'admin_approval_at' => 'datetime',
        'documents_uploaded_at' => 'datetime',
        'registration_completed_at' => 'datetime',
        // Payment fields casts
        'remaining_amount' => 'decimal:2',
        'is_full_payment_upfront' => 'boolean',
        'pelunasan_amount_paid' => 'decimal:2',
        'pelunasan_paid_at' => 'datetime',
        'pelunasan_approved_by_admin' => 'boolean',
        // Ticket & Visa casts
        'ticket_processed_at' => 'datetime',
        'visa_processed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function umrohPackage(): BelongsTo
    {
        return $this->belongsTo(UmrohPackage::class, 'paket_id');
    }

    public function umrohSchedule(): BelongsTo
    {
        return $this->belongsTo(UmrohSchedule::class, 'umroh_schedule_id');
    }

    public function marketing(): BelongsTo
    {
        return $this->belongsTo(Marketing::class, 'marketing_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(MarketingAgent::class, 'agent_id');
    }

    public function ticketProcessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ticket_processed_by');
    }

    public function visaProcessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visa_processed_by');
    }

    public function installmentPayments()
    {
        return $this->hasMany(InstallmentPayment::class);
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

    public function getStepName(): string
    {
        $steps = [
            1 => 'Pendaftaran & Dokumen',
            2 => 'Pembayaran DP',
            3 => 'Pelunasan & Manasik',
            4 => 'Keberangkatan'
        ];

        return $steps[$this->getCurrentStep()] ?? 'Unknown';
    }

    public function getStatusDisplay(): string
    {
        switch ($this->current_step) {
            case 1:
                return 'Menunggu input data & dokumen';
            case 2:
                return 'Menunggu pembayaran DP';
            case 3:
                return 'Proses pelunasan & manasik';
            case 4:
                return 'Siap keberangkatan';
            default:
                return 'Status tidak diketahui';
        }
    }

    public function canAdvanceToNextStep(): bool
    {
        $currentStep = $this->getCurrentStep();

        switch ($currentStep) {
            case 1:
                // Lanjut ke step 2 setelah submit data
                return true;
            case 2:
                // Lanjut ke step 3 setelah upload bukti transfer
                return !is_null($this->bukti_transfer);
            case 3:
                // Lanjut ke step 4 jika payment complete dan dokumen sudah diverifikasi
                return $this->isPaymentComplete() && $this->documents_verified;
            default:
                return false;
        }
    }

    public function advanceToNextStep(): bool
    {
        if (!$this->canAdvanceToNextStep()) {
            return false;
        }

        // Normal progression: 1 -> 2 -> 3 -> 4
        $this->current_step = min($this->current_step + 1, 4);

        return $this->save();
    }

    public function isAwaitingCSApproval(): bool
    {
        return $this->current_step == 2 && $this->bukti_transfer && !$this->data_approved_by_cs;
    }

    public function isAwaitingAdminApproval(): bool
    {
        return $this->current_step == 2 && $this->bukti_transfer && !$this->payment_approved_by_admin;
    }

    public function canCSApprove(): bool
    {
        return $this->current_step >= 2 && !$this->data_approved_by_cs;
    }

    public function canAdminApprove(): bool
    {
        return $this->current_step >= 2 && !$this->payment_approved_by_admin && !is_null($this->bukti_transfer);
    }

    public function hasAllRequiredDocuments(): bool
    {
        return !is_null($this->foto_ktp) &&
               !is_null($this->foto_kk) &&
               !is_null($this->foto_diri) &&
               !is_null($this->foto_paspor);
    }

    public function getRequiredDocuments(): array
    {
        return [
            'foto_ktp' => 'Foto KTP',
            'foto_kk' => 'Foto Kartu Keluarga',
            'foto_diri' => 'Foto Diri',
            'foto_paspor' => 'Foto Paspor'
        ];
    }

    public function getOptionalDocuments(): array
    {
        return [
            'scan_buku_nikah' => 'Scan Buku Nikah',
            'scan_akta_lahir' => 'Scan Akta Lahir'
        ];
    }

    public function getUploadedDocuments(): array
    {
        $documents = [];
        $allDocs = array_merge($this->getRequiredDocuments(), $this->getOptionalDocuments());

        foreach ($allDocs as $field => $label) {
            if (!is_null($this->$field)) {
                $documents[$field] = [
                    'label' => $label,
                    'path' => $this->$field,
                    'uploaded_at' => $this->documents_uploaded_at
                ];
            }
        }

        return $documents;
    }

    public function canUploadDocuments(): bool
    {
        return $this->current_step >= 1; // Documents can be uploaded from step 1
    }

    // ===== INSTALLMENT PAYMENT METHODS =====

    /**
     * Generate installment schedule for program talangan
     */
    public function generateInstallmentSchedule(): bool
    {
        if (!$this->program_talangan || !$this->rencana_keberangkatan) {
            return false;
        }

        // Check if installments already exist
        if ($this->installmentPayments()->count() > 0) {
            return false;
        }

        // Calculate installment amount from existing cicilan_data or default calculation
        $totalAmount = $this->getTotalPackageAmount();
        $dpAmount = $this->dp_paid ?? 0;
        $remainingAmount = $totalAmount - $dpAmount;
        $installmentAmount = $remainingAmount / 5; // 5 installments

        // Generate 5 installments starting from 1 month after registration
        $baseDate = $this->registration_completed_at ?? now();

        for ($i = 1; $i <= 5; $i++) {
            $dueDate = $baseDate->copy()->addMonths($i);

            // Ensure due date is before departure date
            if ($dueDate >= $this->rencana_keberangkatan) {
                $monthsUntilDeparture = $baseDate->diffInMonths($this->rencana_keberangkatan);
                $dueDate = $baseDate->copy()->addMonths(max(1, floor($monthsUntilDeparture * $i / 5)));
            }

            InstallmentPayment::create([
                'jamaah_profile_id' => $this->id,
                'installment_number' => $i,
                'due_date' => $dueDate,
                'amount' => $installmentAmount,
                'status' => 'pending'
            ]);
        }

        return true;
    }

    /**
     * Get next due payment
     */
    public function getNextDuePayment()
    {
        return $this->installmentPayments()
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->first();
    }

    /**
     * Get total outstanding balance
     */
    public function getTotalOutstanding(): float
    {
        return $this->installmentPayments()
            ->whereIn('status', ['pending', 'overdue'])
            ->sum('amount');
    }

    /**
     * Get payment progress percentage
     */
    public function getPaymentProgress(): int
    {
        if (!$this->program_talangan) {
            return $this->payment_approved_by_admin ? 100 : 0;
        }

        $totalInstallments = $this->installmentPayments()->count();
        if ($totalInstallments === 0) {
            return 0;
        }

        $paidInstallments = $this->installmentPayments()->where('status', 'paid')->count();
        return round(($paidInstallments / $totalInstallments) * 100);
    }

    /**
     * Check if all installments are paid
     */
    public function isPaymentComplete(): bool
    {
        if (!$this->program_talangan) {
            // Untuk non-talangan: cek apakah DP = harga paket (lunas langsung) atau perlu pelunasan
            $packageAmount = $this->getTotalPackageAmount();
            $dpPaid = (float) $this->dp_amount_paid;

            // Jika DP >= harga paket, maka lunas langsung (full payment)
            if ($dpPaid >= $packageAmount) {
                return $this->payment_approved_by_admin && $this->data_approved_by_cs;
            }
            // Jika DP < harga paket, perlu pelunasan juga
            else {
                return $this->payment_approved_by_admin &&
                       $this->data_approved_by_cs &&
                       $this->pelunasan_approved_by_admin &&
                       ($this->pelunasan_amount_paid + $dpPaid) >= $packageAmount;
            }
        }

        // Untuk program talangan: cek installments
        return $this->installmentPayments()
            ->whereNotIn('status', ['paid', 'waived'])
            ->count() === 0;
    }

    /**
     * Get overdue installments
     */
    public function getOverdueInstallments()
    {
        return $this->installmentPayments()
            ->where('status', 'pending')
            ->where('due_date', '<', now()->startOfDay())
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get upcoming installments (within 7 days)
     */
    public function getUpcomingInstallments()
    {
        return $this->installmentPayments()
            ->where('status', 'pending')
            ->whereBetween('due_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get paid installments count
     */
    public function getPaidInstallmentsCount(): int
    {
        return $this->installmentPayments()->where('status', 'paid')->count();
    }

    /**
     * Get total installments count
     */
    public function getTotalInstallmentsCount(): int
    {
        return $this->installmentPayments()->count();
    }

    /**
     * Enhanced getCurrentStep for program talangan
     */
    public function getCurrentStepEnhanced(): int
    {
        if (!$this->isRegistrationComplete()) {
            return 1;
        }

        // Normal flow: check if can advance based on approvals
        if ($this->current_step == 2 && $this->payment_approved_by_admin && $this->data_approved_by_cs) {
            return 3; // Move to step 3 (Pelunasan & Manasik)
        }

        return $this->current_step;
    }

    /**
     * Check if can proceed to departure (final validation)
     */
    public function canProceedToKeberangkatan(): bool
    {
        return $this->hasAllRequiredDocuments() &&
               $this->current_step >= 4 &&
               $this->isPaymentComplete(); // Only check payment completion at departure
    }

    /**
     * Get total package amount (placeholder - implement based on your package system)
     */
    private function getTotalPackageAmount(): float
    {
        if ($this->umrohPackage) {
            return (float) $this->umrohPackage->harga;
        }

        // Fallback to default amount if no package found
        return 25000000; // 25 million IDR default
    }

    /**
     * Set payment type and calculate remaining amount
     */
    public function setPaymentType(): void
    {
        $packageAmount = $this->getTotalPackageAmount();
        $dpPaid = (float) $this->dp_amount_paid;

        if ($dpPaid >= $packageAmount) {
            // Full payment upfront
            $this->payment_type = 'full_payment';
            $this->is_full_payment_upfront = true;
            $this->remaining_amount = 0;
        } else {
            // DP only, needs pelunasan
            $this->payment_type = 'dp_only';
            $this->is_full_payment_upfront = false;
            $this->remaining_amount = $packageAmount - $dpPaid;
        }
    }

    /**
     * Check if needs pelunasan payment
     */
    public function needsPelunasan(): bool
    {
        return !$this->is_full_payment_upfront && $this->remaining_amount > 0;
    }

    /**
     * Get payment progress percentage
     */
    public function getPaymentProgressPercentage(): float
    {
        if ($this->program_talangan) {
            return $this->getPaymentProgress(); // Existing method for installments
        }

        $packageAmount = $this->getTotalPackageAmount();
        if ($packageAmount <= 0) return 0;

        $totalPaid = (float) $this->dp_amount_paid + (float) $this->pelunasan_amount_paid;
        return min(($totalPaid / $packageAmount) * 100, 100);
    }
}
