<?php

namespace App\Services;

use App\Models\InstallmentPayment;
use App\Models\JamaahProfile;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InstallmentService
{
    /**
     * Generate payment schedule for jamaah (installments or lump sum payment)
     */
    public function generateScheduleFromJamaah(JamaahProfile $jamaah): array
    {
        if (!$jamaah->rencana_keberangkatan) {
            return [
                'success' => false,
                'message' => 'Tanggal keberangkatan belum ditentukan'
            ];
        }

        // Check if installments already exist
        if ($jamaah->installmentPayments()->count() > 0) {
            return [
                'success' => false,
                'message' => 'Jadwal cicilan sudah pernah dibuat untuk jamaah ini'
            ];
        }

        try {
            DB::beginTransaction();

            if ($jamaah->program_talangan) {
                // Generate installments for program talangan
                $result = $jamaah->generateInstallmentSchedule();
                $message = 'Jadwal cicilan berhasil dibuat';
                $paymentType = 'installment';
            } else {
                // Generate single lump sum payment for non-talangan
                $result = $this->generateLumpSumPayment($jamaah);
                $message = 'Jadwal pelunasan berhasil dibuat';
                $paymentType = 'lump_sum';
            }

            if ($result) {
                DB::commit();

                Log::info('Payment schedule generated for jamaah', [
                    'jamaah_id' => $jamaah->id,
                    'payment_type' => $paymentType,
                    'program_talangan' => $jamaah->program_talangan
                ]);

                return [
                    'success' => true,
                    'message' => $message,
                    'payment_type' => $paymentType,
                    'data' => $jamaah->installmentPayments()->orderBy('installment_number')->get()
                ];
            }

            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Gagal membuat jadwal cicilan'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating installment schedule', [
                'jamaah_id' => $jamaah->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat jadwal cicilan'
            ];
        }
    }

    /**
     * Generate single lump sum payment for non-talangan jamaah
     */
    private function generateLumpSumPayment(JamaahProfile $jamaah): bool
    {
        // Calculate remaining balance from actual package price
        $packagePrice = $jamaah->umrohPackage?->harga ?? 24500000; // Fallback to default if package not set
        $biayaTambahan = $jamaah->umrohSchedule?->biaya_tambahan ?? 0;
        $totalPrice = $packagePrice + $biayaTambahan;

        $dpPaid = $jamaah->dp_amount_paid ?? $jamaah->dp_paid ?? 0;
        $remainingBalance = $totalPrice - $dpPaid;

        if ($remainingBalance <= 0) {
            Log::info('No remaining balance for jamaah', [
                'jamaah_id' => $jamaah->id,
                'package_price' => $packagePrice,
                'biaya_tambahan' => $biayaTambahan,
                'total_price' => $totalPrice,
                'dp_paid' => $dpPaid
            ]);
            return true; // Already fully paid
        }

        // Set due date 30 days before departure or minimum 14 days from now
        $departureDate = Carbon::parse($jamaah->rencana_keberangkatan);
        $dueDate = $departureDate->copy()->subDays(30);
        $minimumDate = Carbon::now()->addDays(14);

        if ($dueDate->lt($minimumDate)) {
            $dueDate = $minimumDate;
        }

        // Create single installment payment record
        $installment = InstallmentPayment::create([
            'jamaah_profile_id' => $jamaah->id,
            'installment_number' => 1,
            'amount' => $remainingBalance,
            'due_date' => $dueDate,
            'status' => 'pending',
            'payment_type' => 'lump_sum',
            'description' => 'Pelunasan pembayaran paket umroh'
        ]);

        Log::info('Lump sum payment created for jamaah', [
            'jamaah_id' => $jamaah->id,
            'installment_id' => $installment->id,
            'amount' => $remainingBalance,
            'due_date' => $dueDate->format('Y-m-d')
        ]);

        return true;
    }

    /**
     * Process installment payment
     */
    public function processInstallmentPayment(
        InstallmentPayment $installment,
        UploadedFile $paymentProof,
        string $notes = null
    ): array {
        if ($installment->status === 'paid') {
            return [
                'success' => false,
                'message' => 'Cicilan ini sudah dibayar sebelumnya'
            ];
        }

        try {
            DB::beginTransaction();

            // Upload payment proof
            $proofPath = $this->uploadPaymentProof($paymentProof, $installment);

            // Mark as paid (but not approved yet)
            $installment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_proof' => $proofPath,
                'notes' => $notes
            ]);

            DB::commit();

            Log::info('Installment payment processed', [
                'installment_id' => $installment->id,
                'jamaah_id' => $installment->jamaah_profile_id,
                'amount' => $installment->amount
            ]);

            return [
                'success' => true,
                'message' => 'Pembayaran cicilan berhasil diproses. Menunggu verifikasi admin.',
                'data' => $installment->fresh()
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing installment payment', [
                'installment_id' => $installment->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal memproses pembayaran cicilan'
            ];
        }
    }

    /**
     * Approve installment payment (Admin function)
     */
    public function approveInstallmentPayment(
        InstallmentPayment $installment,
        int $approvedBy,
        string $notes = null
    ): array {
        if ($installment->status !== 'paid') {
            return [
                'success' => false,
                'message' => 'Cicilan belum dibayar atau sudah disetujui sebelumnya'
            ];
        }

        try {
            $installment->approve($approvedBy, $notes);

            Log::info('Installment payment approved', [
                'installment_id' => $installment->id,
                'approved_by' => $approvedBy,
                'jamaah_id' => $installment->jamaah_profile_id
            ]);

            // Send notifications after successful approval
            $this->sendApprovalNotifications($installment);

            return [
                'success' => true,
                'message' => 'Pembayaran cicilan berhasil disetujui',
                'data' => $installment->fresh()
            ];

        } catch (\Exception $e) {
            Log::error('Error approving installment payment', [
                'installment_id' => $installment->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal menyetujui pembayaran cicilan'
            ];
        }
    }

    /**
     * Send approval notifications (Email + WhatsApp + Database)
     */
    protected function sendApprovalNotifications(InstallmentPayment $installment): void
    {
        try {
            $jamaah = $installment->jamaahProfile;
            $user = $jamaah->user;

            // Send email notification
            if ($user->email) {
                try {
                    \Mail::to($user->email)->send(new \App\Mail\InstallmentApprovedMail($installment));
                    Log::info('Approval email sent', [
                        'installment_id' => $installment->id,
                        'email' => $user->email
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send approval email', [
                        'installment_id' => $installment->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Send database notification
            try {
                $notification = new \App\Notifications\InstallmentApprovedNotification($installment);
                $user->notify($notification);

                // Send WhatsApp message if phone number exists
                if ($jamaah->no_telepon) {
                    $whatsappService = new \App\Services\WhatsAppService();
                    $whatsappMessage = $notification->getWhatsAppMessage();
                    $whatsappResult = $whatsappService->sendMessage($jamaah->no_telepon, $whatsappMessage);

                    Log::info('WhatsApp notification result', [
                        'installment_id' => $installment->id,
                        'phone' => $jamaah->no_telepon,
                        'result' => $whatsappResult
                    ]);
                }

                Log::info('Approval notifications sent', [
                    'installment_id' => $installment->id,
                    'jamaah_id' => $jamaah->id
                ]);

            } catch (\Exception $e) {
                Log::error('Failed to send approval notification', [
                    'installment_id' => $installment->id,
                    'error' => $e->getMessage()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send approval notifications', [
                'installment_id' => $installment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Calculate and mark overdue payments
     */
    public function calculateOverduePayments(): array
    {
        $overdueCount = 0;
        $processedJamaah = [];

        try {
            // Get all pending installments that are past due
            $overdueInstallments = InstallmentPayment::where('status', 'pending')
                ->where('due_date', '<', now()->startOfDay())
                ->get();

            foreach ($overdueInstallments as $installment) {
                $installment->markAsOverdue();
                $overdueCount++;

                if (!in_array($installment->jamaah_profile_id, $processedJamaah)) {
                    $processedJamaah[] = $installment->jamaah_profile_id;
                }
            }

            Log::info('Overdue payments processed', [
                'overdue_count' => $overdueCount,
                'jamaah_affected' => count($processedJamaah)
            ]);

            return [
                'success' => true,
                'overdue_count' => $overdueCount,
                'jamaah_affected' => count($processedJamaah)
            ];

        } catch (\Exception $e) {
            Log::error('Error calculating overdue payments', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal memproses pembayaran terlambat'
            ];
        }
    }

    /**
     * Get payment reminders data
     */
    public function getPaymentReminders(): array
    {
        try {
            // Get installments due in 7 days
            $upcomingPayments = InstallmentPayment::with('jamaahProfile.user')
                ->where('status', 'pending')
                ->whereBetween('due_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
                ->orderBy('due_date')
                ->get();

            // Get overdue payments
            $overduePayments = InstallmentPayment::with('jamaahProfile.user')
                ->where('status', 'overdue')
                ->orderBy('due_date')
                ->get();

            return [
                'success' => true,
                'upcoming' => $upcomingPayments,
                'overdue' => $overduePayments
            ];

        } catch (\Exception $e) {
            Log::error('Error getting payment reminders', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mengambil data reminder pembayaran'
            ];
        }
    }

    /**
     * Get installment statistics
     */
    public function getInstallmentStatistics(): array
    {
        try {
            $stats = [
                'total_installments' => InstallmentPayment::count(),
                'paid_installments' => InstallmentPayment::where('status', 'paid')->count(),
                'pending_installments' => InstallmentPayment::where('status', 'pending')->count(),
                'overdue_installments' => InstallmentPayment::where('status', 'overdue')->count(),
                'total_amount_paid' => InstallmentPayment::where('status', 'paid')->sum('amount'),
                'total_amount_pending' => InstallmentPayment::whereIn('status', ['pending', 'overdue'])->sum('amount'),
                'jamaah_with_installments' => JamaahProfile::whereHas('installmentPayments')->count(),
                'jamaah_completed_payments' => JamaahProfile::whereHas('installmentPayments', function($query) {
                    $query->whereNotIn('status', ['paid', 'waived']);
                }, '=', 0)->count()
            ];

            return [
                'success' => true,
                'data' => $stats
            ];

        } catch (\Exception $e) {
            Log::error('Error getting installment statistics', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mengambil statistik cicilan'
            ];
        }
    }

    /**
     * Upload payment proof file
     */
    private function uploadPaymentProof(UploadedFile $file, InstallmentPayment $installment): string
    {
        $filename = 'installment_' . $installment->jamaah_profile_id . '_' . $installment->installment_number . '_' . time() . '.' . $file->getClientOriginalExtension();

        return $file->storeAs('payment_proofs/installments', $filename, 'public');
    }

    /**
     * Auto-generate installments for new program talangan registrations
     */
    public function autoGenerateForNewRegistration(JamaahProfile $jamaah): void
    {
        if ($jamaah->program_talangan && $jamaah->rencana_keberangkatan) {
            $this->generateScheduleFromJamaah($jamaah);
        }
    }

    /**
     * Get jamaah payment dashboard data
     */
    public function getJamaahPaymentDashboard(JamaahProfile $jamaah): array
    {
        try {
            $data = [
                'payment_progress' => $jamaah->getPaymentProgress(),
                'next_due' => $jamaah->getNextDuePayment(),
                'total_outstanding' => $jamaah->getTotalOutstanding(),
                'paid_count' => $jamaah->getPaidInstallmentsCount(),
                'total_count' => $jamaah->getTotalInstallmentsCount(),
                'overdue_payments' => $jamaah->getOverdueInstallments(),
                'upcoming_payments' => $jamaah->getUpcomingInstallments(),
                'all_installments' => $jamaah->installmentPayments()->orderBy('installment_number')->get(),
                'is_program_talangan' => $jamaah->program_talangan,
                'departure_date' => $jamaah->rencana_keberangkatan,
                'is_payment_complete' => $jamaah->isPaymentComplete()
            ];

            return [
                'success' => true,
                'data' => $data
            ];

        } catch (\Exception $e) {
            Log::error('Error getting jamaah payment dashboard', [
                'jamaah_id' => $jamaah->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mengambil data dashboard pembayaran'
            ];
        }
    }
}