<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use  App\Models\Cliente;
use  App\Models\Almacen;
use  App\Models\Compras_detalle;

class Compra extends Model implements Auditable
{
    use AuditableTrait;
    protected $fillable = [
        'folio',
        'serie',
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
        return $this->hasMany(Compras_detalle::class);
    }

    public function proveedor() {
        return $this->belongsTo(Cliente::class, 'proveedor_id');
    }
    public function almacen() {
        return $this->belongsTo(Almacen::class, 'almacen_id');
    }
}
