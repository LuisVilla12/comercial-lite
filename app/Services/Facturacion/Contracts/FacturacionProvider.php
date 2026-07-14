<?php

namespace App\Services\Facturacion\Contracts;

interface FacturacionProvider
{
    public function crearCfdi(array $payload);

    public function cancelarCfdi(
        string $uuid,
        string $motivo,
        ?string $folioSustitucion = null
    );

    public function obtenerXml(string $uuid);


}
