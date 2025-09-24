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
        'paket_id',
        'rencana_keberangkatan',
        'program_talangan',
        'cicilan_data',
        'catatan',
        'current_step',
        'status',
        'dp_paid',
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
        'registration_completed_at'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'rencana_keberangkatan' => 'date',
        'tgl_pelunasan' => 'date',
        'tanggal_diterima_perlengkapan' => 'date',
        'dp_paid' => 'decimal:2',
        'program_talangan' => 'boolean',
        'data_approved_by_cs' => 'boolean',
        'payment_approved_by_admin' => 'boolean',
        'documents_verified' => 'boolean',
        'cicilan_data' => 'array',
        'jamaah_rombongan' => 'array',
        'cs_approval_at' => 'datetime',
        'admin_approval_at' => 'datetime',
        'documents_uploaded_at' => 'datetime',
        'registration_completed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
            1 => 'Pendaftaran Data Diri',
            2 => 'Pembayaran DP',
            4 => 'Upload Dokumen',
            5 => 'Manasik & Keberangkatan'
        ];

        return $steps[$this->getCurrentStep()] ?? 'Unknown';
    }

    public function getStatusDisplay(): string
    {
        switch ($this->current_step) {
            case 1:
                return 'Menunggu input data';
            case 2:
                return 'Menunggu pembayaran DP';
            case 4:
                return 'Upload dokumen';
            case 5:
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
                // Otomatis lanjut ke step 2 setelah submit data
                return true;
            case 2:
                // Otomatis lanjut ke step 4 setelah upload bukti transfer (skip step 3)
                return !is_null($this->bukti_transfer);
            case 4:
                // Lanjut ke step 5 jika dokumen sudah diverifikasi
                return $this->documents_verified ?? false;
            default:
                return false;
        }
    }

    public function advanceToNextStep(): bool
    {
        if (!$this->canAdvanceToNextStep()) {
            return false;
        }

        // Skip step 3 (verification) - go directly from step 2 to step 4
        if ($this->current_step == 2) {
            $this->current_step = 4;
        } else {
            $this->current_step = min($this->current_step + 1, 5);
        }

        return $this->save();
    }

    public function isAwaitingCSApproval(): bool
    {
        return $this->current_step == 3 && !$this->data_approved_by_cs;
    }

    public function isAwaitingAdminApproval(): bool
    {
        return $this->current_step == 3 && !$this->payment_approved_by_admin;
    }

    public function canCSApprove(): bool
    {
        return $this->current_step >= 3 && !$this->data_approved_by_cs;
    }

    public function canAdminApprove(): bool
    {
        return $this->current_step >= 3 && !$this->payment_approved_by_admin && !is_null($this->bukti_transfer);
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
        return $this->current_step >= 4;
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
            return $this->payment_approved_by_admin && $this->data_approved_by_cs;
        }

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

        // For program talangan, don't block on payment completion
        // Only require DP to be approved to proceed
        if ($this->program_talangan) {
            if ($this->current_step == 2 && $this->payment_approved_by_admin && $this->data_approved_by_cs) {
                return 4; // Skip to preparation step
            }
        } else {
            // Regular payment flow
            if ($this->current_step == 2 && $this->payment_approved_by_admin && $this->data_approved_by_cs) {
                return 4;
            }
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
        // TODO: Implement based on your package system
        // This should get the total amount from the selected package
        // For now, returning a default amount
        return 25000000; // 25 million IDR default
    }
}
