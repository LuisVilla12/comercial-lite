<?php

namespace App\Models;
use  App\Models\Pago;

class PagosDetalle extends TenantModel
{
        protected $fillable = [
        'id',
        'pago_id',
        'documento_id',
        'monto',
    ];
    public function pago(){
    return $this->belongsTo(Pago::class);
}
    public function documento()
{
    return $this->belongsTo(Documento::class);
}

}
