<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = [
        'id',
        'serie',
        'folio',
        'fecha',
        'cliente_id',
        'nombre_cliente',
        'rfc_cliente',
        'observaciones',
        'afectado',
        'neto',
        'impuestos',
        'retenciones',
        'total',
        'total_unidades',
        'metodo_pago',
        'usuario'
    ];
}

