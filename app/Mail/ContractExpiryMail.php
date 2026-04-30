<?php

namespace App\Mail;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContractExpiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Contract $contract, public int $daysLeft)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Échéance de contrat dans {$this->daysLeft} jours - Universal Invest Strategy",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contract_expiry',
            with: [
                'contract' => $this->contract,
                'daysLeft' => $this->daysLeft,
            ]
        );
    }
}
