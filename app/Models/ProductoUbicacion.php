<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoUbicacion extends TenantModel
{
    //
    protected $fillable = [
        'producto_id',
        'almacen_id',
        'zona',
        'pasillo',
        'anaquel',
        'repisa'
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
