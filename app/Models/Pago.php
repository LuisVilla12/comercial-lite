<?php

namespace App\Models;
use  App\Models\Documento;


class Pago extends TenantModel
{
    protected $fillable = [
        'id',
        'cliente_id',
        'user_id',
        'fecha',
        'forma_pago',
        'estatus',
        'monto',
        'observaciones',
        //FACTURAMA
        'facturama_id',
        'uuid',
    ];
    public function documento()
{
    return $this->belongsTo(Documento::class);
}
}
