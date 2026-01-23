<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{protected $fillable = [
    'documento_id',
    'cliente_id',
    'user_id',
    'serie',
    'folio',
    'fecha',
    'total',
    'observaciones'
    ];
public function detalles() {
        return $this->hasMany(DevolucionesDetalles::class);
    }

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function usuario() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
