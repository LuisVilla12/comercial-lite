<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsoCfdi extends TenantModel
{
    protected $table = 'uso_cfdis';

    protected $fillable = [
        'clave',
        'descripcion',
        'activo'
    ];
}
