<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\FacturaApiService;
use Illuminate\Support\Facades\Storage;

class DescargarFacturaAPI implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct( public $facturaID, public $uuid ){}

    /**
     * Execute the job.
     */
    public function handle(FacturaApiService $facturaApi): void
    {

        // Descargar XML
        $xml = $facturaApi->obtenerXml(
           $this->facturaID
        );


        $rutaXml = "facturas/{$this->uuid}.xml";

        Storage::put(
            $rutaXml,
            $xml
        );
    }
}
