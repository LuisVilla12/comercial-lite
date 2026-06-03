<?php

namespace App\Mail;

use App\Models\Documento;
use App\Models\DatosBancario;
use App\Models\Sucursal;
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
    public Sucursal $sucursal;

    public function __construct(Sucursal $sucursal, Documento $documento)
    {
        $this->documento = $documento;
        $this->sucursal = $sucursal;
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
                'sucursal' => $this->sucursal,
            ],
        );
    }

    public function attachments(): array
{
    $banco = DatosBancario::where('predeterminado', true)->first();

    $this->documento->load([
        'cliente',
        'detalles.producto'
    ]);

    $pdf = Pdf::loadView('documentos.pdf', [
        'documento' => $this->documento,
        'sucursal' => $this->sucursal,
        'banco' => $banco,
    ]);

    return [
        Attachment::fromData(
            fn () => $pdf->output(),
            'Documento_'.$this->documento->folio.'.pdf'
        )->withMime('application/pdf'),
    ];
}
}
