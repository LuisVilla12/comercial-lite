<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Punto;

class PuntosMovimiento extends Model
{
        protected $table = 'puntos_movimientos';

    protected $fillable = [
        'punto_id',
        'documento_id',
        'tipo',
        'concepto',
        'puntos',
        'referencia',
    ];
 public function puntos()
    {
        return $this->belongsTo(Punto::class);
    }

}
