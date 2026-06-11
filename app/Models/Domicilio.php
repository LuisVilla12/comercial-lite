<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domicilio extends TenantModel
{
    //
        protected $fillable = [
            'pais',
            'estado',
            'municipio',
            'ciudad',
            'colonia',
            'calle',
            'numero_interior',
            'numero_exterior',
            'cp',
            'tipo',
            'cliente_id'
        ];

    // public function cliente()
    // {
    //     return $this->belongsTo(Cliente::class, 'cliente_id');
    // }
    public function domiciliable()
{
    return $this->morphTo();
}
}
