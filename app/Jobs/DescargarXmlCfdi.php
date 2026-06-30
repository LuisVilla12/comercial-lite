<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Documento;

class DescargarXmlCfdi implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $facturamaId,
        public string $uuid
    ) {}

    public function handle(): void
    {
        try {

            $xml = null;
            $intentos = 0;

            while ($intentos < 10) {

                $response = Http::withBasicAuth(
                    config('services.facturama.user'),
                    config('services.facturama.password')
                )->get(
                    config('services.facturama.url') . "/cfdi/xml/issued/{$this->facturamaId}"
                );

                if ($response->successful()) {
                    $data = $response->json();


                    if (!empty($data['Content'])) {

                        $xml = base64_decode($data['Content']);

                        if (str_contains($xml, '<cfdi:Comprobante')) {
                            break;
                        }
                    }
                }

                sleep(1);
                $intentos++;
            }

            if (empty($xml)) {

                Log::error('Error Facturama XML vacío', [
                    'uuid' => $this->uuid,
                    'facturamaId' => $this->facturamaId
                ]);

                throw new \Exception('XML no disponible en Facturama');
            }

            Storage::put("cfdi/{$this->uuid}.xml", $xml);

            Log::info('XML guardado correctamente', [
                'uuid' => $this->uuid,
                'bytes' => strlen($xml)
            ]);
        } catch (\Throwable $e) {

            Log::error('Job DescargarXmlCfdi falló', [
                'uuid' => $this->uuid,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
