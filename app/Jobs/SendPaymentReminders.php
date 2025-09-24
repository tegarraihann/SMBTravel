<?php

namespace App\Jobs;

use App\Models\InstallmentPayment;
use App\Services\InstallmentService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(InstallmentService $installmentService): void
    {
        try {
            Log::info('Starting payment reminder process');

            $remindersData = $installmentService->getPaymentReminders();

            if (!$remindersData['success']) {
                Log::warning('Failed to get payment reminders data', [
                    'message' => $remindersData['message']
                ]);
                return;
            }

            $remindersSent = 0;

            // Send reminders for upcoming payments (due in 3 days)
            $upcomingDue = InstallmentPayment::with('jamaahProfile.user')
                ->where('status', 'pending')
                ->whereBetween('due_date', [now()->addDays(2)->startOfDay(), now()->addDays(4)->endOfDay()])
                ->get();

            foreach ($upcomingDue as $installment) {
                if ($this->sendUpcomingPaymentReminder($installment)) {
                    $remindersSent++;
                }
            }

            // Send reminders for overdue payments
            $overduePayments = InstallmentPayment::with('jamaahProfile.user')
                ->where('status', 'overdue')
                ->get();

            foreach ($overduePayments as $installment) {
                if ($this->sendOverduePaymentReminder($installment)) {
                    $remindersSent++;
                }
            }

            Log::info('Payment reminder process completed', [
                'reminders_sent' => $remindersSent,
                'upcoming_count' => $upcomingDue->count(),
                'overdue_count' => $overduePayments->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error in SendPaymentReminders job', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Send upcoming payment reminder
     */
    private function sendUpcomingPaymentReminder(InstallmentPayment $installment): bool
    {
        try {
            $jamaah = $installment->jamaahProfile;
            $user = $jamaah->user;

            // For now, just log the reminder
            // TODO: Implement actual email/SMS sending
            Log::info('Upcoming payment reminder', [
                'jamaah_id' => $jamaah->id,
                'jamaah_name' => $jamaah->nama_lengkap_bin_binti,
                'installment_number' => $installment->installment_number,
                'due_date' => $installment->due_date->format('d/m/Y'),
                'amount' => $installment->amount,
                'days_until_due' => $installment->days_until_due
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send upcoming payment reminder', [
                'installment_id' => $installment->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send overdue payment reminder
     */
    private function sendOverduePaymentReminder(InstallmentPayment $installment): bool
    {
        try {
            $jamaah = $installment->jamaahProfile;
            $user = $jamaah->user;

            // For now, just log the reminder
            // TODO: Implement actual email/SMS sending
            Log::info('Overdue payment reminder', [
                'jamaah_id' => $jamaah->id,
                'jamaah_name' => $jamaah->nama_lengkap_bin_binti,
                'installment_number' => $installment->installment_number,
                'due_date' => $installment->due_date->format('d/m/Y'),
                'amount' => $installment->amount,
                'days_overdue' => abs($installment->days_until_due)
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send overdue payment reminder', [
                'installment_id' => $installment->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendPaymentReminders job failed', [
            'exception' => $exception->getMessage()
        ]);
    }
}