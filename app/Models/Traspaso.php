<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traspaso extends Model
{
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
