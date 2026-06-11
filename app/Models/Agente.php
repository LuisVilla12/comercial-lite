<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agente extends TenantModel
{
    //
    protected $fillable = ['codigo', 'nombre', 'apellidoP', 'apellidoM'];

}
