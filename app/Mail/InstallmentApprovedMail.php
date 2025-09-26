<?php

namespace App\Mail;

use App\Models\InstallmentPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstallmentApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public InstallmentPayment $installment;

    /**
     * Create a new message instance.
     */
    public function __construct(InstallmentPayment $installment)
    {
        $this->installment = $installment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembayaran Cicilan Anda Telah Disetujui - Travel Umroh SMB',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.installment-approved',
            with: [
                'installment' => $this->installment,
                'jamaah' => $this->installment->jamaahProfile,
                'user' => $this->installment->jamaahProfile->user,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
