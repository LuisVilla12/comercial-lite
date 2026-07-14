<?php

namespace App\Models;
use  App\Models\Domicilio;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Cliente extends TenantModel implements Auditable{
        use AuditableTrait;

    protected $fillable = [
        'tipo',
        'codigo',
        'nombre',
        'rfc',
        'curp',
        'email1',
        'email2',
        'whatsapp',
        'telefono',
        'regimen_fiscal',
        'activo',
        'saldo',
        'credito',
        'diasCredito'
    ];
    public function domicilios()
{
    return $this->morphMany(Domicilio::class, 'domiciliable');
}

}
