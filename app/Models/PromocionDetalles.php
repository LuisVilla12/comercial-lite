<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromocionDetalles extends TenantModel
{
    //
     protected $fillable = [
        'promocion_id',
        'producto_id',
    ];

    public function producto() {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
