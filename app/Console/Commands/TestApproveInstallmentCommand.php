<?php

namespace App\Console\Commands;

use App\Models\InstallmentPayment;
use App\Services\InstallmentService;
use Illuminate\Console\Command;

class TestApproveInstallmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:approve-installment {id? : Installment ID to approve}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test approve installment payment functionality';

    /**
     * Execute the console command.
     */
    public function handle(InstallmentService $installmentService)
    {
        $this->info('🧪 Testing Installment Approval');
        $this->info('=' . str_repeat('=', 40));

        // Get installment ID
        $installmentId = $this->argument('id');

        if (!$installmentId) {
            $paidInstallments = InstallmentPayment::where('status', 'paid')
                ->whereNull('approved_at')
                ->with('jamaahProfile')
                ->get();

            if ($paidInstallments->count() === 0) {
                $this->error('❌ No paid installments available for testing');
                return Command::FAILURE;
            }

            $this->line('Available paid installments:');
            foreach ($paidInstallments as $installment) {
                $this->line("  - ID {$installment->id}: {$installment->jamaahProfile->nama_lengkap_bin_binti} - Cicilan ke-{$installment->installment_number}");
            }

            $installmentId = $this->ask('Enter installment ID to approve');
        }

        $installment = InstallmentPayment::with('jamaahProfile.user')->find($installmentId);

        if (!$installment) {
            $this->error("❌ Installment with ID {$installmentId} not found");
            return Command::FAILURE;
        }

        $this->info("📋 Installment Details:");
        $this->line("ID: {$installment->id}");
        $this->line("Jamaah: {$installment->jamaahProfile->nama_lengkap_bin_binti}");
        $this->line("Email: {$installment->jamaahProfile->user->email}");
        $this->line("Phone: {$installment->jamaahProfile->no_telepon}");
        $this->line("Cicilan: ke-{$installment->installment_number}");
        $this->line("Amount: Rp " . number_format($installment->amount, 0, ',', '.'));
        $this->line("Status: {$installment->status}");
        $this->line("Approved: " . ($installment->approved_at ? 'Yes' : 'No'));

        if ($installment->status !== 'paid') {
            $this->error("❌ Installment status is not 'paid'. Current: {$installment->status}");
            return Command::FAILURE;
        }

        if ($installment->approved_at) {
            $this->warn("⚠️ Installment already approved at: {$installment->approved_at}");
            if (!$this->confirm('Continue anyway?')) {
                return Command::SUCCESS;
            }
        }

        $this->info("\n🚀 Testing approval...");

        // Test approval
        $result = $installmentService->approveInstallmentPayment(
            $installment,
            1, // Mock admin ID
            'Test approval via command'
        );

        if ($result['success']) {
            $this->info('✅ Success: ' . $result['message']);

            // Check if notifications were sent
            $this->info("\n📧📱 Checking notifications...");
            sleep(2); // Wait for async operations

            // Refresh installment data
            $installment->refresh();

            $this->line("Updated status: {$installment->status}");
            $this->line("Approved at: " . ($installment->approved_at ? $installment->approved_at->format('Y-m-d H:i:s') : 'Not approved'));
            $this->line("Approved by: {$installment->approved_by}");

            return Command::SUCCESS;
        } else {
            $this->error('❌ Failed: ' . $result['message']);
            return Command::FAILURE;
        }
    }
}
