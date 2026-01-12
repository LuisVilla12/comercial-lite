<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //
    protected $fillable = [
        'codigo_producto',
        'nombre_producto',
        'tipo_producto',
        'peso_producto',
        'estatus_producto',
        'unidad_medida',
        'impuesto1',
        'retencion1',
        'valor_clasificacion1',
        'valor_clasificacion2',
        'importe_extra',
        'precio1',
        'precio2',
        'precio3',
        'precio4',
        'precio5',
        'precio_calculado',
        'exento_impuesto',
        'codigo_alterno',
        'clave_sat'
    ];

    public function clasificacion1()
{
    return $this->belongsTo(Clasificacion::class, 'valor_clasificacion1');
}
public function existencias()
    {
        return $this->hasMany(ExistenciaProducto::class);
    }
}
