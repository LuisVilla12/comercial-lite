<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    //
    protected $fillable = [
        'nombre',
        'tipo',
        'valor',
        'fecha_inicio',
        'fecha_fin',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

     public function productos()
    {
        return $this->belongsToMany(Producto::class, 'promocion_productos');
    }

    public function estaActiva(): bool
    {
        return $this->activo &&
            now()->between($this->fecha_inicio, $this->fecha_fin);
    }
}
