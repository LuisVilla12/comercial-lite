<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
      protected $fillable = [
        'tipo',
        'codigo',
        'nombre',
        'rfc',
        'curp',
        'email1',
        'email2',
        'whatsapp',
        'telefono',
        'regimen_fiscal',
        'activo',
        'saldo'
    ];

    public function domicilios()
    {
        return $this->hasMany(Domicilio::class, 'cliente_id');
    }
}
