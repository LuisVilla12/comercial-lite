<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Cliente extends Model implements Auditable
{
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
        'saldo'
    ];
    public function domicilios()
{
    return $this->morphMany(Domicilio::class, 'domiciliable');
}

}
