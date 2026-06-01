<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatosBancario extends Model
{
    //
     protected $fillable = [
        'nombre_banco',
        'cuenta_bancaria',
        'clabe',
        'correo_electronico',
        'whatsapp',
        'predeterminado',
    ];
}
