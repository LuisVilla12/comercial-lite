<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends TenantModel
{
    protected $fillable = [
        'fecha',
        'folio',
        'total',
        'descripcion',
        'tipo',
        'caja_id',
        'user_id',
    ];
}
