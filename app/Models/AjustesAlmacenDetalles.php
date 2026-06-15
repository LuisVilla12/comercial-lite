<?php

namespace App\Models;
use App\Models\Producto;
use App\Models\AjustesAlmacen;
use Illuminate\Database\Eloquent\Model;

class AjustesAlmacenDetalles extends TenantModel
{
        protected $fillable = [
        'ajustes_almacen_id',
        'producto_id',
        'cantidad',
    ];


    public function ajuste()
    {
        return $this->belongsTo(AjustesAlmacen::class,'ajustes_almacen_id');
    }
      public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}

