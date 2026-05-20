<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use  App\Models\Compra;
use  App\Models\Producto;


class Compras_detalle extends Model
{
    protected $table = 'compras_detalles';
    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'costo_unitario',
        'importe'
    ];
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }
      public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
