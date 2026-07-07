<?php

namespace App\Models;
use  App\Models\Documento;


class Pago extends TenantModel
{
    protected $fillable = [
        'id',
        'folio',
        'cliente_id',
        'user_id',
        'fecha',
        'forma_pago',
        'estatus',
        'monto',
        //FACTURAMA
        'facturama_id',
        'uuid',
    ];
    public function documento()
{
    return $this->belongsTo(Documento::class);
}
    public function cliente()
{
    return $this->belongsTo(Cliente::class);
}
}
