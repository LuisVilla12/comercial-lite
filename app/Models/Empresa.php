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
        'activo',

        //DATOS DE CONEXIÓN
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
    ];

}

