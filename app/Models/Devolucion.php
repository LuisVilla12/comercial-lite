<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{protected $fillable = [
    'documento_id',
    'serie',
    'folio',
    'fecha',
    'total',
    'observaciones'
    ];
}
