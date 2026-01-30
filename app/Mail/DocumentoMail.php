<?php

namespace App\Mail;

use App\Models\Documento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentoMail extends Mailable
{
    use Queueable, SerializesModels;

    public Documento $documento;

    public function __construct(Documento $documento)
    {
        $this->documento = $documento;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Envio de " . match ($this->documento->documento_modelo_id) {
                1 => 'Cotización ',
                2 => 'Factura ',
                3 => 'Remisión ',
                default => 'Documento ',
            } . $this->documento->serie . $this->documento->folio,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.documentos',
            with: [
                'documento' => $this->documento,
            ],
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('documentos.pdf', [
            'documento' => $this->documento
        ]);

        return [
            Attachment::fromData(
                fn(): string => $pdf->output(),
                match ($this->documento->documento_modelo_id) {
                    1 => 'Cotización ',
                    2 => 'Factura ',
                    3 => 'Remisión ',
                    default => 'Documento ',
                } . $this->documento->folio . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
