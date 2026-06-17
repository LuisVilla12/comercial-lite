<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regimen extends TenantModel
{
    protected $table = 'regimenes';
    protected $fillable = [
        'codigo',
        'nombre'
    ];
}
