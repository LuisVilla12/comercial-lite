<?php

namespace App\Mail;

use App\Models\ConfiguracionEmpresa;
use App\Models\Documento;
use App\Models\DatosBancario;
use App\Models\Sucursal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\FacturaApiService;
use Illuminate\Support\Facades\Storage;


class DocumentoMail extends Mailable  implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Documento $documento;
    public Sucursal $sucursal;
    public ConfiguracionEmpresa $empresa;

    public function __construct(Sucursal $sucursal, Documento $documento,ConfiguracionEmpresa $empresa)
    {
        $this->documento = $documento;
        $this->sucursal = $sucursal;
        $this->empresa = $empresa;
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
        $attachments = [];

    //INVOCA EL SERVICIO DE FACTURAMA
    $facturama = app(FacturaApiService::class);

    $banco = DatosBancario::where('predeterminado', true)->first();

    $this->documento->load([
        'cliente',
        'detalles.producto'
    ]);
    // dd($this->documento);
    if($this->documento->documento_modelo_id == '2' && $this->documento->estatus == '4'){
            // LEER EL XML
            $xml = $facturama->leerXml($this->documento->uuid);
            // //OBTENER LA INFORMACION NECESARIA
            $datosXML = $facturama->extraerTimbreCfdi($xml);
            // //GENERAR LA URL
            $urlQr = $facturama->generarUrl($datosXML, $this->documento->total);
            // // GENERAR QR
             $qr = $facturama->generarQrPng($urlQr);
        $pdf = Pdf::loadView('documentos.pdf_factura', [
                'documento'=> $this->documento,
                'sucursal' => $this->sucursal,
                'banco' => $banco,
                'empresa' => $this->empresa,
                'datosXML' => $datosXML,
                'qr' => $qr
            ])
                ->setPaper('letter');
    }
    elseif($this->documento->documento_modelo_id == '2' && $this->documento->estatus == '1') {
            $datosXML = '';
            $qr = '';
            $pdf = Pdf::loadView('documentos.pdf_factura', [
                'documento'=> $this->documento,
                'sucursal' => $this->sucursal,
                'banco' => $banco,
                'empresa' => $this->empresa,
                'datosXML' => $datosXML,
                'qr' => $qr
            ])
                ->setPaper('letter');
    }
    else{
        $pdf = Pdf::loadView('documentos.pdf', [
        'documento' => $this->documento,
        'sucursal' => $this->sucursal,
        'banco' => $banco,
        'empresa'=>$this->empresa,
    ]);
    }
    // ADJUNTA PDF
    $attachments[] = Attachment::fromData(
            fn () => $pdf->output(),
            'Documento_'.$this->documento->folio.'.pdf'
        )->withMime('application/pdf');
    // XML (si existe el archivo es decir si esta timbrada)

    $rutaXml = "cfdi/{$this->documento->uuid}.xml";
      if (Storage::exists($rutaXml)) {
        $attachments[] = Attachment::fromStorage($rutaXml)
            ->as('Factura_'.$this->documento->serie.$this->documento->folio.'.xml')
            ->withMime('application/xml');
    }

return  $attachments;
}
}
