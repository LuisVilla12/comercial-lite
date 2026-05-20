<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PuntosMovimiento;
use  App\Models\Cliente;


class Punto extends Model
{
    //
    protected $fillable = [
        'cliente_id',
        'total_puntos',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function movimientos()
    {
        return $this->hasMany(PuntosMovimiento::class);
    }
}
