<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Http;
use App\Services\Facturacion\Contracts\FacturacionProvider;


class FacturamaService implements FacturacionProvider
{
    public function __construct(
        protected string $baseUrl = '',
        protected string $user = '',
        protected string $password = ''
    ) {
        $this->baseUrl = config('services.facturama.url');
        $this->user = config('services.facturama.user');
        $this->password = config('services.facturama.password');
    }

    private function client()
    {
        return Http::withBasicAuth(
            $this->user,
            $this->password
        )->baseUrl($this->baseUrl);
    }

    public function crearCfdi(array $payload)
    {
        try {
            return $this->client()
                ->post('/3/cfdis', $payload)
                ->throw()
                ->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $response = $e->response->json();
            $errores = [];

            if (isset($response['ModelState'])) {
                foreach ($response['ModelState'] as $campo => $mensajes) {
                    foreach ($mensajes as $mensaje) {
                        $errores[] = $mensaje;
                    }
                }
            }

            throw new \Exception(
                count($errores)
                    ? implode("\n", $errores)
                    : ($response['Message'] ?? 'Error al generar el CFDI.')
            );
        }
    }
    public function obtenerCfdi(string $facturamaId)
{
    return $this->client()
        ->get("/cfdi/{$facturamaId}?type=issued")
        ->throw()
        ->json();
}

    public function cancelarCfdi(string $facturamaId, string $motivo,?string $uuidSustitucion = null) {
    $url = "/cfdi/{$facturamaId}?type=issued&motive={$motivo}";

    if (in_array($motivo, ['01', '04']) && $uuidSustitucion) {
        $url .= "&uuidReplacement={$uuidSustitucion}";
    }

    try {

        return $this->client()
            ->delete($url)
            ->throw()
            ->json();

    } catch (\Illuminate\Http\Client\RequestException $e) {

        $mensaje = $e->response->json()['Message']
            ?? $e->response->body()
            ?? 'Error al cancelar el CFDI.';

        throw new \Exception($mensaje);

    }
}

    //FUNCION PARA OBTENER EL XML DE LA FACTURA
    public function obtenerXml($id)
    {
        try {
            $response = Http::withBasicAuth(
                env('FACTURAMA_USER'),
                env('FACTURAMA_PASSWORD')
            )->get(
                env('FACTURAMA_URL') . "/cfdi/xml/issued/{$id}"
            );

            $data = $response->json();

            if (
                !isset($data['Content']) ||
                empty($data['Content'])
            ) {
                return null;
            }

            return base64_decode($data['Content']);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $response = $e->response->json();
            $errores = [];
            if (isset($response[''])) {
                foreach ($response[''] as $campo => $mensajes) {
                    foreach ($mensajes as $mensaje) {
                        $errores[] = $mensaje;
                    }
                }
            }
            throw new \Exception(
                count($errores)
                    ? implode("\n", $errores)
                    : ($response['Message'] ?? 'Error al obtener el XML.')
            );
        }
    }

    //FUNCION PARA LEER EL XML
    public function leerXml(string $uuid): \SimpleXMLElement
    {
        $ruta = storage_path("app/private/cfdi/{$uuid}.xml");

        if (!file_exists($ruta)) {
            throw new \Exception("No existe el XML: {$ruta}");
        }

        $xml = simplexml_load_file($ruta);

        if ($xml === false) {
            throw new \Exception("No fue posible leer el XML.");
        }

        return $xml;
    }

    public function extraerTimbreCfdi(\SimpleXMLElement $xml): array
    {
        $xml->registerXPathNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');

        $timbre = $xml->xpath('//tfd:TimbreFiscalDigital')[0];
        $comprobante = $xml->xpath('/cfdi:Comprobante')[0];
        // DATOS DE EMISOR Y RECEPTOR
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
            'cadena_original' => $documento->cadena_original ?? null,
        ];
    }
    // GENEAR URL PARA GENERAR EL QR
    public function generarUrl(array $datos, float $total): string
    {
        $sello = substr($datos['sello_cfdi'], -8);
        $total = number_format($total, 6, '.', '');

        return "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx"
            . "?id={$datos['uuid']}"
            . "&re={$datos['rfc_emisor']}"
            . "&rr={$datos['rfc_receptor']}"
            . "&tt={$total}"
            . "&fe={$sello}";
    }
    public function generarQrPng(string $url): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->size(150)
            ->margin(5)
            ->build();

        return base64_encode($result->getString());
    }
}
