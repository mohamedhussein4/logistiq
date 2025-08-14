<?php

namespace App\Mail;

use App\Models\ClientDebt;
use App\Models\FundingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceCompanyInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientDebt;
    public $fundingRequest;
    public $registrationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(ClientDebt $clientDebt, string $registrationUrl)
    {
        $this->clientDebt = $clientDebt;
        $this->fundingRequest = $clientDebt->fundingRequest;
        $this->registrationUrl = $registrationUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'دعوة للتسجيل وسداد المستحقات - منصة LogistiQ',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.service-company-invitation',
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
