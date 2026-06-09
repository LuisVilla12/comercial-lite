<?php

namespace App\Models;
use App\Models\Producto;
use App\Models\EntradasAlmacen;
use App\Models\SalidasAlmacen;

use Illuminate\Database\Eloquent\Model;

class AjustesAlmacenDetalles extends Model
{
        protected $fillable = [
        'documento_id',
        'producto_id',
        'cantidad',
        'costo_unitario',
        'importe'
    ];

    public function entrada()
    {
        return $this->belongsTo(Document::class);
    }
      public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}

