<?php

namespace App\Console\Commands;

use App\Models\JamaahProfile;
use App\Services\InstallmentService;
use App\Jobs\GenerateInstallmentSchedule;
use App\Jobs\CheckOverduePayments;
use App\Jobs\SendPaymentReminders;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestInstallmentSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installment:test {action} {--jamaah-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test installment system functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $jamaahId = $this->option('jamaah-id');

        switch ($action) {
            case 'generate':
                return $this->generateSchedule($jamaahId);
            case 'check-overdue':
                return $this->checkOverdue();
            case 'send-reminders':
                return $this->sendReminders();
            case 'stats':
                return $this->showStatistics();
            default:
                $this->error('Unknown action. Available actions: generate, check-overdue, send-reminders, stats');
                return 1;
        }
    }

    /**
     * Generate installment schedule for a jamaah
     */
    private function generateSchedule($jamaahId)
    {
        if (!$jamaahId) {
            $this->error('--jamaah-id is required for generate action');
            return 1;
        }

        $jamaah = JamaahProfile::find($jamaahId);
        if (!$jamaah) {
            $this->error("Jamaah with ID {$jamaahId} not found");
            return 1;
        }

        $this->info("Testing schedule generation for jamaah: {$jamaah->nama_lengkap_bin_binti}");
        $this->info("Program Talangan: " . ($jamaah->program_talangan ? 'Yes' : 'No'));
        $this->info("Departure Date: " . ($jamaah->rencana_keberangkatan ? $jamaah->rencana_keberangkatan->format('d/m/Y') : 'Not set'));

        if (!$jamaah->program_talangan) {
            $this->warn('Jamaah is not using program talangan');
            return 0;
        }

        if (!$jamaah->rencana_keberangkatan) {
            $this->error('Jamaah does not have departure date set');
            return 1;
        }

        // Check if schedule already exists
        $existingCount = $jamaah->installmentPayments()->count();
        $this->info("Existing installments: {$existingCount}");

        // Dispatch job
        GenerateInstallmentSchedule::dispatch($jamaah);
        $this->info('✅ Schedule generation job dispatched');

        return 0;
    }

    /**
     * Test overdue checking
     */
    private function checkOverdue()
    {
        $this->info('Testing overdue payment check...');

        CheckOverduePayments::dispatch();
        $this->info('✅ Overdue check job dispatched');

        return 0;
    }

    /**
     * Test reminder sending
     */
    private function sendReminders()
    {
        $this->info('Testing reminder sending...');

        SendPaymentReminders::dispatch();
        $this->info('✅ Reminder sending job dispatched');

        return 0;
    }

    /**
     * Show installment statistics
     */
    private function showStatistics()
    {
        $installmentService = app(InstallmentService::class);
        $stats = $installmentService->getInstallmentStatistics();

        if (!$stats['success']) {
            $this->error('Failed to get statistics: ' . $stats['message']);
            return 1;
        }

        $data = $stats['data'];

        $this->info('📊 Installment System Statistics:');
        $this->line('');
        $this->info("Total Installments: {$data['total_installments']}");
        $this->info("Paid Installments: {$data['paid_installments']}");
        $this->info("Pending Installments: {$data['pending_installments']}");
        $this->info("Overdue Installments: {$data['overdue_installments']}");
        $this->line('');
        $this->info("Total Amount Paid: Rp " . number_format($data['total_amount_paid'], 0, ',', '.'));
        $this->info("Total Amount Pending: Rp " . number_format($data['total_amount_pending'], 0, ',', '.'));
        $this->line('');
        $this->info("Jamaah with Installments: {$data['jamaah_with_installments']}");
        $this->info("Jamaah with Completed Payments: {$data['jamaah_completed_payments']}");

        // Show recent installments
        $recentInstallments = \App\Models\InstallmentPayment::with('jamaahProfile')
            ->latest()
            ->take(5)
            ->get();

        if ($recentInstallments->count() > 0) {
            $this->line('');
            $this->info('📋 Recent Installments:');

            $headers = ['ID', 'Jamaah', 'Installment #', 'Amount', 'Due Date', 'Status'];
            $rows = [];

            foreach ($recentInstallments as $installment) {
                $rows[] = [
                    $installment->id,
                    $installment->jamaahProfile->nama_lengkap_bin_binti ?? 'N/A',
                    $installment->installment_number,
                    'Rp ' . number_format($installment->amount, 0, ',', '.'),
                    $installment->due_date->format('d/m/Y'),
                    $installment->status
                ];
            }

            $this->table($headers, $rows);
        }

        return 0;
    }
}