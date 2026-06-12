<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
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
        //DATOS DE CONEXIÓN
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
    ];

}

