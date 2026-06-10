<?php

namespace App\Models;
use App\Models\Producto;
use App\Models\AjustesAlmacen;
use Illuminate\Database\Eloquent\Model;

class AjustesAlmacenDetalles extends Model
{
        protected $fillable = [
        'ajustes_almacen_id',
        'producto_id',
        'cantidad',
    ];


    public function ajuste()
    {
        return $this->belongsTo(AjustesAlmacen::class);
    }
      public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}

