<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraspasoDetalle extends Model
    {
    protected $table = 'traspasos_detalles';
    protected $fillable = [
        'traspaso_id',
        'producto_id',
        'cantidad'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
