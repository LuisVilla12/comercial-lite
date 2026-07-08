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
    public function detalles() {
        return $this->hasMany(PagosDetalle::class);
    }

    public function cliente()
{
    return $this->belongsTo(Cliente::class);
}
    public function domicilios()
{
    return $this->morphMany(Domicilio::class, 'domiciliable');
}

}
