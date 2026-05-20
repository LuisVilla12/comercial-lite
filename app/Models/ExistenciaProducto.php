<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use  App\Models\Producto;
use  App\Models\Almacen;

class ExistenciaProducto extends Model
{
    protected $fillable = [
        'producto_id',
        'almacen_id',
        'cantidad'
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
