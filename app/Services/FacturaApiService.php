<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\Facturacion\Contracts\FacturacionProvider;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class FacturaApiService implements FacturacionProvider
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.facturaapi.url');
        $this->token = config('services.facturaapi.token');
    }


    private function client()
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->baseUrl($this->baseUrl);
    }


    public function crearCfdi(array $payload)
    {
        try {

            return $this->client()
                ->post('/v2/invoices', $payload)
                ->throw()
                ->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {

            $response = $e->response?->json();
            throw new \Exception(
                $response['message'] ?? 'Error al generar CFDI'
            );
        }
    }

    public function obtenerXml(string $id)
    {
        try {
            return $this->client()
                ->get("/v2/invoices/{$id}/xml")
                ->throw()
                ->body();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $response = $e->response?->json();
            throw new \Exception(
                $response['message'] ?? 'Error al generar CFDI'
            );
        }
    }

    public function cancelarCfdi(string $id,string $motivo,?string $uuidSustitucion = null) {
    try {
        $payload = [
            "motive" => $motivo
        ];

        if ($uuidSustitucion) {
            $payload["replacement_uuid"] = $uuidSustitucion;
        }


        return $this->client()
            ->delete("/v2/invoices/{$id}", $payload)
            ->throw()
            ->json();


    } catch (\Illuminate\Http\Client\RequestException $e) {

        $response = $e->response?->json();

        throw new \Exception(
            $response['message']
            ?? 'Error al cancelar CFDI.'
        );
    }
}


    public function obtenerCfdi(string $uuid)
    {
        throw new \Exception('Pendiente');
    }

    //GENERAR buildpayload
    public function buildPayload($documento, $empresa)
    {
        return [
            "payment_form" => $documento->forma_pago,
            "use" => $documento->uso_cfdi,
            "customer" => [
                "legal_name" => $documento->cliente->nombre,
                "tax_id" => $documento->cliente->rfc,
                "tax_system" => $documento->cliente->regimen_fiscal,
                "email" => $documento->cliente->email1,
                "address" => [
                    "zip" => $documento->cliente->domicilios->first()?->cp
                ]
            ],
            "items" => $this->buildItems($documento)
        ];
    }
    private function buildItems($documento){
        $items = [];
        foreach ($documento->detalles as $detalle) {
            $item = [
                "quantity" => (float) $detalle->cantidad,
                "product" => [
                    "description" => $detalle->producto->nombre_producto,
                    "price" => $detalle->costo_unitario,
                    "product_key" => $detalle->producto->clave_sat,
                    "unit_key" => $detalle->producto->unidad->clave,
                    "tax_included" => false,
                    "taxes" => [
                        [
                            "type" => "IVA",
                            "rate" => $detalle->producto->impuesto1 / 100,
                            "factor" => "Tasa"
                        ]
                    ]
                ]
            ];

            if ($detalle->descuento > 0) {
                $item["discount"] = (float) $detalle->descuento;
            }


            $items[] = $item;
        }

        return $items;
    }

    public function leerXml(string $uuid): \SimpleXMLElement{
        $ruta = storage_path("app/private/facturas/{$uuid}.xml");
        if (!file_exists($ruta)) {
            throw new \Exception("No existe el XML: {$ruta}");
        }
        $xml = simplexml_load_file($ruta);

        if ($xml === false) {
            throw new \Exception("No fue posible leer el XML.");
        }
        return $xml;
    }
    public function extraerTimbreCfdi(\SimpleXMLElement $xml): array{
        $xml->registerXPathNamespace(
            'cfdi',
            'http://www.sat.gob.mx/cfd/4'
        );

        $xml->registerXPathNamespace(
            'tfd',
            'http://www.sat.gob.mx/TimbreFiscalDigital'
        );


        $timbre = $xml->xpath('//tfd:TimbreFiscalDigital')[0];

        $comprobante = $xml->xpath('/cfdi:Comprobante')[0];

        $emisor = $xml->xpath('//cfdi:Emisor')[0];

        $receptor = $xml->xpath('//cfdi:Receptor')[0];


        return [
            'uuid' => (string) $timbre['UUID'],

            'fecha_timbrado' => (string) $timbre['FechaTimbrado'],

            'rfc_emisor' => (string) $emisor['Rfc'],

            'rfc_receptor' => (string) $receptor['Rfc'],

            'no_cert_emisor' => (string) $comprobante['NoCertificado'],

            'no_cert_sat' => (string) $timbre['NoCertificadoSAT'],

            'sello_cfdi' => (string) $timbre['SelloCFD'],

            'sello_sat' => (string) $timbre['SelloSAT'],
        ];
    }
    public function generarUrl(array $datos, float $total): string{
        $sello = substr($datos['sello_cfdi'], -8);
        $total = number_format($total, 6, '.', '');

        return "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx"
            . "?id={$datos['uuid']}"
            . "&re={$datos['rfc_emisor']}"
            . "&rr={$datos['rfc_receptor']}"
            . "&tt={$total}"
            . "&fe={$sello}";
    }
    public function generarQrPng(string $url): string{
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->size(150)
            ->margin(5)
            ->build();

        return base64_encode($result->getString());
    }
}
