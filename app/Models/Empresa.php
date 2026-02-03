<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'rfc',
        'regimen_fiscal',
        'curp',
        'email',
        'telefono',
        'activo',
    ];

    public function domicilios()
    {
        return $this->hasMany(Domicilio::class, 'cliente_id');
}
}

