<?php

namespace App\Console\Commands;

use App\Models\JamaahProfile;
use App\Models\InstallmentPayment;
use Illuminate\Console\Command;

class CheckJamaahDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:jamaah-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check jamaah data distribution and installment generation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking Jamaah Data Distribution');
        $this->info('=' . str_repeat('=', 50));

        $this->checkJamaahProfiles();
        $this->info('');
        $this->checkInstallmentPayments();
        $this->info('');
        $this->checkMissingInstallments();
        $this->info('');
        $this->checkInstallmentGeneration();
        $this->info('');
        $this->checkApprovalStatus();

        return Command::SUCCESS;
    }

    protected function checkJamaahProfiles()
    {
        $this->info('📋 JAMAAH PROFILES');
        $this->line('-' . str_repeat('-', 30));

        $jamaahProfiles = JamaahProfile::with('user')->get();

        $this->line("Total Jamaah: {$jamaahProfiles->count()}");

        $headers = ['ID', 'Nama', 'Program Talangan', 'Email', 'Step', 'Keberangkatan'];
        $rows = [];

        foreach($jamaahProfiles as $jamaah) {
            $rows[] = [
                $jamaah->id,
                $jamaah->nama_lengkap_bin_binti ?? 'N/A',
                $jamaah->program_talangan ? 'Ya' : 'Tidak',
                $jamaah->user->email ?? 'N/A',
                $jamaah->current_step ?? 'N/A',
                $jamaah->rencana_keberangkatan ?? 'N/A'
            ];
        }

        $this->table($headers, $rows);
    }

    protected function checkInstallmentPayments()
    {
        $this->info('💰 INSTALLMENT PAYMENTS');
        $this->line('-' . str_repeat('-', 30));

        $installments = InstallmentPayment::with('jamaahProfile.user')->get();

        $this->line("Total Installments: {$installments->count()}");

        if($installments->count() > 0) {
            $headers = ['ID', 'Jamaah ID', 'Nama Jamaah', 'Cicilan Ke-', 'Status', 'Amount'];
            $rows = [];

            foreach($installments as $installment) {
                $jamaahName = $installment->jamaahProfile ? $installment->jamaahProfile->nama_lengkap_bin_binti : 'N/A';
                $statusDisplay = $installment->status;
                if ($installment->status === 'paid' && $installment->approved_at) {
                    $statusDisplay .= ' (approved)';
                }

                $rows[] = [
                    $installment->id,
                    $installment->jamaah_profile_id,
                    $jamaahName,
                    $installment->installment_number,
                    $statusDisplay,
                    'Rp ' . number_format($installment->amount, 0, ',', '.')
                ];
            }

            $this->table($headers, $rows);
        } else {
            $this->warn('⚠️  Tidak ada installment payments ditemukan');
        }
    }

    protected function checkMissingInstallments()
    {
        $this->info('🚨 JAMAAH DENGAN PROGRAM TALANGAN TANPA INSTALLMENTS');
        $this->line('-' . str_repeat('-', 50));

        $jamaahTanpaInstallments = JamaahProfile::where('program_talangan', true)
            ->whereDoesntHave('installmentPayments')
            ->with('user')
            ->get();

        if($jamaahTanpaInstallments->count() > 0) {
            $this->error("Found {$jamaahTanpaInstallments->count()} jamaah with program talangan but no installments!");

            $headers = ['ID', 'Nama', 'Email', 'Step', 'Keberangkatan', 'Created At'];
            $rows = [];

            foreach($jamaahTanpaInstallments as $jamaah) {
                $rows[] = [
                    $jamaah->id,
                    $jamaah->nama_lengkap_bin_binti ?? 'N/A',
                    $jamaah->user->email ?? 'N/A',
                    $jamaah->current_step ?? 'N/A',
                    $jamaah->rencana_keberangkatan ?? 'N/A',
                    $jamaah->created_at->format('Y-m-d H:i:s')
                ];
            }

            $this->table($headers, $rows);
        } else {
            $this->info('✅ All jamaah with program talangan have installments');
        }
    }

    protected function checkInstallmentGeneration()
    {
        $this->info('⚙️  INSTALLMENT GENERATION CHECK');
        $this->line('-' . str_repeat('-', 35));

        // Check if we have the method to generate installments
        $jamaahSample = JamaahProfile::first();
        if($jamaahSample && method_exists($jamaahSample, 'generateInstallmentSchedule')) {
            $this->info('✅ generateInstallmentSchedule method exists in JamaahProfile');
        } else {
            $this->error('❌ generateInstallmentSchedule method NOT found in JamaahProfile');
        }

        // Check InstallmentService
        if(class_exists('App\Services\InstallmentService')) {
            $this->info('✅ InstallmentService exists');
        } else {
            $this->error('❌ InstallmentService NOT found');
        }

        // Check when installments should be generated
        $jamaahProgram = JamaahProfile::where('program_talangan', true)->first();
        if($jamaahProgram) {
            $this->line("Sample jamaah with program talangan:");
            $this->line("- ID: {$jamaahProgram->id}");
            $this->line("- Step: {$jamaahProgram->current_step}");
            $this->line("- Keberangkatan: {$jamaahProgram->rencana_keberangkatan}");
            $this->line("- Installments count: {$jamaahProgram->installmentPayments()->count()}");
        }
    }

    protected function checkApprovalStatus()
    {
        $this->info('🔐 APPROVAL STATUS CHECK');
        $this->line('-' . str_repeat('-', 30));

        $jamaahTalangan = JamaahProfile::where('program_talangan', true)->get();

        if($jamaahTalangan->count() > 0) {
            $headers = ['ID', 'Nama', 'CS Approved', 'Admin Approved', 'Step', 'Installments', 'Trigger Status'];
            $rows = [];

            foreach($jamaahTalangan as $jamaah) {
                $csApproved = $jamaah->data_approved_by_cs ? 'Ya' : 'Tidak';
                $adminApproved = $jamaah->payment_approved_by_admin ? 'Ya' : 'Tidak';
                $installmentCount = $jamaah->installmentPayments()->count();

                // Check if conditions met for installment generation
                $triggerStatus = 'Tidak';
                if ($jamaah->data_approved_by_cs &&
                    $jamaah->payment_approved_by_admin &&
                    $jamaah->program_talangan &&
                    $jamaah->rencana_keberangkatan) {
                    $triggerStatus = $installmentCount > 0 ? 'Generated' : 'Should Generate';
                }

                $rows[] = [
                    $jamaah->id,
                    $jamaah->nama_lengkap_bin_binti ?? 'N/A',
                    $csApproved,
                    $adminApproved,
                    $jamaah->current_step ?? 'N/A',
                    $installmentCount,
                    $triggerStatus
                ];
            }

            $this->table($headers, $rows);

            // Analysis
            $this->info("\n📊 Analysis:");
            $notGenerated = $jamaahTalangan->filter(function($jamaah) {
                return $jamaah->data_approved_by_cs &&
                       $jamaah->payment_approved_by_admin &&
                       $jamaah->program_talangan &&
                       $jamaah->rencana_keberangkatan &&
                       $jamaah->installmentPayments()->count() === 0;
            });

            if($notGenerated->count() > 0) {
                $this->error("❌ Found {$notGenerated->count()} jamaah yang harusnya punya installments tapi belum di-generate!");
                foreach($notGenerated as $jamaah) {
                    $this->line("   - {$jamaah->nama_lengkap_bin_binti} (ID: {$jamaah->id})");
                }
            } else {
                $this->info("✅ All eligible jamaah have installments generated");
            }
        }
    }
}
