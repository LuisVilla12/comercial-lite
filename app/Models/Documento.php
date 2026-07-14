<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use  App\Models\DocumentosDetalle;
use  App\Models\Cliente;
use  App\Models\User;
use  App\Models\Domicilio;
use  App\Models\Pago;


class Documento extends TenantModel implements Auditable{
    use AuditableTrait;
    protected $fillable = [
        'id',
        'documento_modelo_id',
        'serie',
        'folio',
        'fecha',
        'cliente_id',
        'almacen_id',
        'sucursal_id',
        'user_id',
        'subtotal',
        'impuestos',
        'descuentos',
        'total',
        'saldo_pendiente',
        'metodo_pago',
        'forma_pago',
        'uso_cfdi',
        'observaciones',
        'estatus',
        'vigencia',
        'agente_id',
        'caja_id',
        //FACTURAR EN LINEA
        'codigo_unico',
        'timbrado_online',
        'codigo_utilizado',
        //DATOS DE FACTURAMA
        'facturama_id',
        'uuid',
        'cadena_original',
        //DATPS DE CANCELACION
        'motivo_cancelacion',
        'fecha_cancelacion',
        'uuid_cancelado',
        'id_cancelado',
        'cancellation_status',
    ];

    public function detalles() {
        return $this->hasMany(DocumentosDetalle::class);
    }


    public function cliente() {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function usuario() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function domicilios()
{
    return $this->morphMany(Domicilio::class, 'domiciliable');
}

public function pagos()
{
    return $this->hasMany(Pago::class);
}
    }


