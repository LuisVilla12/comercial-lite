<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use  App\Models\Domicilio;

class Empresa extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'rfc',
        'regimen_fiscal',
        'curp',
        'email',
        'telefono',
        'activo',

        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
    ];

public function domicilios()
{
    return $this->morphMany(Domicilio::class, 'domiciliable');
}
}

