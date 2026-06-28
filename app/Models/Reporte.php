<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends TenantModel
{
        protected $fillable = [
        'user_id',
        'tipo',
        'archivo',
        'estado'
    ];
}
