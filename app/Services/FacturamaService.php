<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FacturamaService
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
        return $this->client()
            ->post('/3/cfdis', $payload)
            ->throw()
            ->json();
    }
    public function obtenerXml($id)
{
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
}
}
