<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionEmpresa extends TenantModel
{
    //
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


