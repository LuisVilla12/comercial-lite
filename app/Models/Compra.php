<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = [
        'folio',
        'proveedor_id',
        'almacen_id',
        'user_id',
        'fecha',
        'subtotal',
        'impuestos',
        'total',
        'estatus',
        'observaciones'
    ];
    public function detalles() {
        return $this->hasMany(Compras_Detalle::class);
    }

    public function proveedor() {
        return $this->belongsTo(Cliente::class, 'proveedor_id');
    }
}
