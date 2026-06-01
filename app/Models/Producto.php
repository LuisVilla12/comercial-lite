<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use  App\Models\Clasificacion;
use  App\Models\ExistenciaProducto;

class Producto extends Model implements Auditable
{
    //
    use AuditableTrait;
    protected $fillable = [
        'codigo_producto',
        'clave_producto',
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
        'marca',
        'volumen',
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
