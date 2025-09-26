<?php

namespace App\Console\Commands;

use App\Services\WhatsAppService;
use App\Mail\InstallmentApprovedMail;
use App\Models\InstallmentPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notifications
                            {--email= : Test email address}
                            {--phone= : Test phone number}
                            {--installment= : Installment ID to use for test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email and WhatsApp notifications for installment approval';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Notification Systems');
        $this->info('=' . str_repeat('=', 50));

        // Test WhatsApp
        $this->testWhatsApp();

        $this->info('');

        // Test Email
        $this->testEmail();

        $this->info('');
        $this->info('✅ Notification testing completed!');
        $this->info('Check logs for detailed results: storage/logs/laravel.log');
    }

    protected function testWhatsApp()
    {
        $this->info('📱 Testing WhatsApp (Fonnte)');
        $this->info('-' . str_repeat('-', 30));

        $whatsAppService = new WhatsAppService();

        // Test connection first
        $this->line('Testing connection...');
        $connectionResult = $whatsAppService->testConnection();

        if ($connectionResult['success']) {
            $this->info('✅ Connection: ' . $connectionResult['message']);
        } else {
            $this->error('❌ Connection: ' . $connectionResult['message']);
            return;
        }

        // Test message sending
        $phone = $this->option('phone') ?: $this->ask('Enter phone number to test (e.g., 08123456789)');

        if ($phone) {
            $testMessage = "🧪 *TEST NOTIFICATION* 🧪\n\n";
            $testMessage .= "Ini adalah pesan test dari Travel Umroh SMB.\n\n";
            $testMessage .= "Jika Anda menerima pesan ini, berarti konfigurasi WhatsApp sudah benar!\n\n";
            $testMessage .= "⏰ " . now()->format('d M Y, H:i:s') . " WIB\n";
            $testMessage .= "🚀 *System Test - Travel Umroh SMB*";

            $this->line("Sending test message to: {$phone}");
            $result = $whatsAppService->sendMessage($phone, $testMessage);

            if ($result['success']) {
                $this->info('✅ WhatsApp: ' . $result['message']);
            } else {
                $this->error('❌ WhatsApp: ' . $result['message']);
            }
        } else {
            $this->warn('⚠️ Skipping WhatsApp message test - no phone number provided');
        }
    }

    protected function testEmail()
    {
        $this->info('📧 Testing Email (SMTP)');
        $this->info('-' . str_repeat('-', 30));

        // Check mail configuration
        $this->line('Checking mail configuration...');

        $driver = config('mail.default');
        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $username = config('mail.mailers.smtp.username');

        $this->line("Driver: {$driver}");
        $this->line("Host: {$host}");
        $this->line("Port: {$port}");
        $this->line("Username: {$username}");

        if (empty($host) || empty($username)) {
            $this->error('❌ Email configuration incomplete in .env file');
            return;
        }

        // Test email sending
        $email = $this->option('email') ?: $this->ask('Enter email address to test');

        if ($email) {
            try {
                // Get or create a test installment
                $installmentId = $this->option('installment');

                if ($installmentId) {
                    $installment = InstallmentPayment::with('jamaahProfile.user')->find($installmentId);
                    if (!$installment) {
                        $this->error("❌ Installment with ID {$installmentId} not found");
                        return;
                    }
                } else {
                    $installment = InstallmentPayment::with('jamaahProfile.user')
                        ->where('status', 'paid')
                        ->first();

                    if (!$installment) {
                        $this->warn('⚠️ No paid installments found. Creating mock data...');
                        return;
                    }
                }

                $this->line("Sending test email to: {$email}");
                $this->line("Using installment ID: {$installment->id}");

                Mail::to($email)->send(new InstallmentApprovedMail($installment));

                $this->info('✅ Email: Test email sent successfully!');
                $this->line('Check your inbox and spam folder.');

            } catch (\Exception $e) {
                $this->error('❌ Email: Failed to send - ' . $e->getMessage());
            }
        } else {
            $this->warn('⚠️ Skipping email test - no email address provided');
        }
    }
}
