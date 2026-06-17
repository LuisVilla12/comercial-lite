<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\Model;

class Agente extends TenantModel implements Auditable
{
        use AuditableTrait;

    protected $fillable = ['codigo', 'nombre', 'apellidoP', 'apellidoM'];

}
