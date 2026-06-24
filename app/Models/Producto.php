<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use  App\Models\Clasificacion;
use  App\Models\ExistenciaProducto;
use  App\Models\Compra;
use  App\Models\Compras_detalle;
use  App\Models\TraspasoDetalle;
use  App\Models\DocumentosDetalle;
use  App\Models\MaximoMinimo;


class Producto extends TenantModel implements Auditable
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

public function unidad()
{
    return $this->belongsTo(ProductoClave::class, 'unidad_medida');
}

    public function maximominimo()
{
    return $this->hasMany(MaximoMinimo::class);
}
public function productoUbicacion()
{
    return $this->hasMany(ProductoUbicacion::class);
}

    public function existencias()
    {
        return $this->hasMany(ExistenciaProducto::class);
    }

    public function compras(){
        return $this->hasMany(Compra::class);
    }

    public function comprasDetalles()
{
    return $this->hasMany(Compras_detalle::class, 'producto_id');
}


public function traspasosDetalles()
{
    return $this->hasMany(TraspasoDetalle::class, 'producto_id');
}

public function documentosDetalles()
{
    return $this->hasMany(DocumentosDetalle::class, 'producto_id');
}
public function AjustesDetalles()
{
    return $this->hasMany(AjustesAlmacenDetalles::class, 'producto_id');
}

}
