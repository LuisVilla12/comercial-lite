<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AjustesAlmacen extends Model
{
    //
        protected $fillable = [
        'id',
        'agente_id',
        'almacen_id',
        'fecha',
        'observaciones',
        'tipo',
        'estatus',
    ];

    public function agente()
    {
        return $this->belongsTo(Agente::class, 'agente_id');
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_id');
    }
}
