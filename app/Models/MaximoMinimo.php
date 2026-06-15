<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaximoMinimo extends TenantModel
{

    protected $fillable = [
        'producto_id',
        'almacen_id',
        'minimo',
        'maximo',
        'zona',
        'pasillo',
        'anaquel'
    ];
   public function producto()
{
    return $this->belongsTo(Producto::class);
}

public function almacen()
{
    return $this->belongsTo(Almacen::class);
}
}
