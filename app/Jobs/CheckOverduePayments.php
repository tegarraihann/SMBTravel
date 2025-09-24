<?php

namespace App\Jobs;

use App\Services\InstallmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckOverduePayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(InstallmentService $installmentService): void
    {
        try {
            Log::info('Starting overdue payment check');

            $result = $installmentService->calculateOverduePayments();

            if ($result['success']) {
                Log::info('Overdue payment check completed successfully', [
                    'overdue_count' => $result['overdue_count'],
                    'jamaah_affected' => $result['jamaah_affected']
                ]);
            } else {
                Log::warning('Overdue payment check completed with issues', [
                    'message' => $result['message']
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in CheckOverduePayments job', [
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
        Log::error('CheckOverduePayments job failed', [
            'exception' => $exception->getMessage()
        ]);
    }
}