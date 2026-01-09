<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoModelo extends Model
{
      protected $fillable = [
        'id',
        'nombre',
        'afecta_existencia',
    ];
}
