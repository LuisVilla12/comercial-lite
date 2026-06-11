<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Clasificacion extends TenantModel implements Auditable
{
    use AuditableTrait;
    protected $fillable = [
        'nombre',
        'codigo'
    ];
}
