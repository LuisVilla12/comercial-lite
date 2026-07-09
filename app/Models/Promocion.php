<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use  App\Models\Producto;

class Promocion extends TenantModel
{
    //
    protected $table = "promociones";
    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'valor',
        'fecha_inicio',
        'fecha_fin',
        'activo'
    ];

    protected $casts = [
        'estatus' => 'boolean',
        // 'fecha_inicio' => 'date',
        // 'fecha_fin' => 'date',
    ];

    public function detalles() {
        return $this->hasMany(PromocionDetalles::class);
    }
    public function estaActiva(): bool
    {
        return $this->activo &&
            now()->between($this->fecha_inicio, $this->fecha_fin);
    }
}
