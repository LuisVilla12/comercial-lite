<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Traspaso extends Model implements Auditable
{
    use AuditableTrait;
      protected $fillable = [
        'serie',
        'folio',
        'fecha',
        'almacen_origen_id',
        'almacen_destino_id',
        'estatus',
        'user_id'
    ];

    public function detalles()
    {
        return $this->hasMany(TraspasoDetalle::class);
    }

    public function almacenOrigen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_origen_id');
    }

    public function almacenDestino()
    {
        return $this->belongsTo(Almacen::class, 'almacen_destino_id');
    }
}
