<?php

namespace App\Notifications;

use App\Models\InstallmentPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstallmentApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public InstallmentPayment $installment;

    /**
     * Create a new notification instance.
     */
    public function __construct(InstallmentPayment $installment)
    {
        $this->installment = $installment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'installment_approved',
            'title' => 'Pembayaran Cicilan Disetujui',
            'message' => "Pembayaran cicilan ke-{$this->installment->installment_number} Anda telah disetujui",
            'installment_id' => $this->installment->id,
            'installment_number' => $this->installment->installment_number,
            'amount' => $this->installment->amount,
            'approved_at' => $this->installment->approved_at,
            'jamaah_name' => $this->installment->jamaahProfile->nama_lengkap_bin_binti,
        ];
    }

    /**
     * Generate WhatsApp message content
     */
    public function getWhatsAppMessage(): string
    {
        $jamaah = $this->installment->jamaahProfile;
        $remainingInstallments = 5 - $this->installment->installment_number;
        $progressPercentage = ($this->installment->installment_number / 5) * 100;

        $message = "🎉 *PEMBAYARAN CICILAN DISETUJUI* 🎉\n\n";
        $message .= "Assalamualaikum *{$jamaah->nama_lengkap_bin_binti}*,\n\n";
        $message .= "Alhamdulillah, pembayaran cicilan Anda telah disetujui!\n\n";

        $message .= "📋 *Detail Pembayaran:*\n";
        $message .= "• Cicilan ke-{$this->installment->installment_number} dari 5\n";
        $message .= "• Jumlah: Rp " . number_format($this->installment->amount, 0, ',', '.') . "\n";
        $message .= "• Disetujui: " . $this->installment->approved_at->format('d M Y, H:i') . " WIB\n\n";

        $message .= "📊 *Progress Pembayaran:*\n";
        $message .= "• {$this->installment->installment_number} dari 5 cicilan selesai (" . number_format($progressPercentage, 0) . "%)\n";

        if ($remainingInstallments > 0) {
            $message .= "• Sisa {$remainingInstallments} cicilan lagi\n\n";
            $message .= "🔄 *Langkah Selanjutnya:*\n";
            $message .= "• Pantau jadwal cicilan berikutnya\n";
            $message .= "• Siapkan pembayaran sebelum jatuh tempo\n";
            $message .= "• Cek dashboard: " . url('/jamaah/dashboard') . "\n\n";
        } else {
            $message .= "\n🎊 *SELAMAT!* Seluruh cicilan telah lunas!\n";
            $message .= "Tim kami akan memproses persiapan keberangkatan Anda.\n\n";
        }

        $message .= "📞 *Butuh Bantuan?*\n";
        $message .= "WhatsApp CS: +62 812-3456-7890\n\n";
        $message .= "Barakallahu fiikum 🤲\n";
        $message .= "*Travel Umroh SMB*";

        return $message;
    }
}
