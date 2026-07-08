<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedioPago extends TenantModel
{
    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
    ];
}
