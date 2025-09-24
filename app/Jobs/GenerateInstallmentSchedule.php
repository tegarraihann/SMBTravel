<?php

namespace App\Jobs;

use App\Models\JamaahProfile;
use App\Services\InstallmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateInstallmentSchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public JamaahProfile $jamaah;

    /**
     * Create a new job instance.
     */
    public function __construct(JamaahProfile $jamaah)
    {
        $this->jamaah = $jamaah;
    }

    /**
     * Execute the job.
     */
    public function handle(InstallmentService $installmentService): void
    {
        try {
            Log::info('Generating installment schedule for jamaah', [
                'jamaah_id' => $this->jamaah->id,
                'program_talangan' => $this->jamaah->program_talangan
            ]);

            if (!$this->jamaah->program_talangan) {
                Log::info('Jamaah does not use program talangan, skipping schedule generation', [
                    'jamaah_id' => $this->jamaah->id
                ]);
                return;
            }

            $result = $installmentService->generateScheduleFromJamaah($this->jamaah);

            if ($result['success']) {
                Log::info('Successfully generated installment schedule', [
                    'jamaah_id' => $this->jamaah->id,
                    'installments_count' => 5
                ]);
            } else {
                Log::warning('Failed to generate installment schedule', [
                    'jamaah_id' => $this->jamaah->id,
                    'message' => $result['message']
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in GenerateInstallmentSchedule job', [
                'jamaah_id' => $this->jamaah->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateInstallmentSchedule job failed', [
            'jamaah_id' => $this->jamaah->id,
            'exception' => $exception->getMessage()
        ]);
    }
}