<?php

namespace App\Models;
use  App\Models\Documento;


class Pago extends TenantModel
{
    protected $fillable = [
        'id',
        'fecha_pago',
        'forma_pago',
        'monto',
        'referencia',
        'observaciones',
        //FACTURAMA
        'facturama_id',
        'uuid',
        'estatus'
    ];
    public function documento()
{
    return $this->belongsTo(Documento::class);
}
}
