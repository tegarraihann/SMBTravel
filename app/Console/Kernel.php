<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check overdue payments every hour
        $schedule->job(\App\Jobs\CheckOverduePayments::class)
            ->hourly()
            ->name('check-overdue-payments')
            ->description('Check and mark overdue installment payments');

        // Send payment reminders daily at 9 AM
        $schedule->job(\App\Jobs\SendPaymentReminders::class)
            ->dailyAt('09:00')
            ->name('send-payment-reminders')
            ->description('Send reminders for upcoming and overdue payments');

        // Clean up old jobs and failed jobs weekly
        $schedule->command('queue:prune-batches --hours=48')
            ->weekly()
            ->sundays()
            ->at('02:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}