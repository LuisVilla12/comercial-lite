<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
class ConfiguracionEmpresa extends TenantModel implements Auditable
{
    use AuditableTrait;
    protected $fillable = [
        // DATOS GENERALES
        'codigo',
        'nombre',
        'rfc',
        'regimen_fiscal',
        'curp',
        'email',
        'telefono',
        'activo',
        //DOMICILIO DE LA EMPRESA
        'pais',
        'estado',
        'municipio',
        'ciudad',
        'colonia',
        'calle',
        'numero_interior',
        'numero_exterior',
        'cp',
    ];
}


