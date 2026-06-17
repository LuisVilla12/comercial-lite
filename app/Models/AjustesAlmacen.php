<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class AjustesAlmacen extends TenantModel implements Auditable
{
            use AuditableTrait;
        protected $fillable = [
        'id',
        'agente_id',
        'almacen_id',
        'fecha',
        'observaciones',
        'tipo',
        'estatus',
    ];

    public function detalles() {
        return $this->hasMany(AjustesAlmacenDetalles::class);
    }

    public function agente()
    {
        return $this->belongsTo(Agente::class, 'agente_id');
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_id');
    }
}

