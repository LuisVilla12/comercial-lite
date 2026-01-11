<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compras_detalle extends Model
{
    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'costo_unitario',
        'importe'
    ];
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
