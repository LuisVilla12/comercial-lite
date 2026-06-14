<?php

namespace App\Models;

use Dom\Document;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Producto;
use  App\Models\Documento;


class DocumentosDetalle extends TenantModel
{
    protected $fillable = [
        'documento_id',
        'producto_id',
        'cantidad',
        'costo_unitario',
        'importe'
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }
      public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
