<?php

namespace App\Mail;

use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ContractMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Contract $contract)
    {
    }

    public function envelope(): Envelope
    {
        $clientName = $this->contract->client->company->company_name
            ?? ($this->contract->client->first_name . ' ' . $this->contract->client->last_name);

        return new Envelope(
            subject: 'Votre Contrat - Universal Invest Strategy',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contract',
            with: [
                'contract' => $this->contract,
            ]
        );
    }

    public function attachments(): array
    {
        // Generate PDF in memory and attach it
        $pdf = Pdf::loadView('pdf.contract', ['contract' => $this->contract]);
        $clientName = $this->contract->client->last_name;

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'Contrat_' . $clientName . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
