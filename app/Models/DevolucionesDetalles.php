<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionesDetalles extends Model
{
    protected $table = 'devolucions_detalles';

    protected $fillable = [
        'devolucion_id',
        'producto_id',
        'cantidad',
        'costo_unitario',
        'importe'
    ];
     public function documento()
    {
        return $this->belongsTo(Devolucion::class);
    }
      public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

}
