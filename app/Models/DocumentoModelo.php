<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoModelo extends TenantModel
{
      protected $fillable = [
        'id',
        'nombre',
        'afecta_existencia',
    ];
}
