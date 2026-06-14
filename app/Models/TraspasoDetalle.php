<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use  App\Models\Producto;
use  App\Models\Traspaso;


class TraspasoDetalle extends TenantModel
    {
    protected $table = 'traspasos_detalles';
    protected $fillable = [
        'traspaso_id',
        'producto_id',
        'cantidad'
    ];

     public function traspaso()
    {
        return $this->belongsTo(Traspaso::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
