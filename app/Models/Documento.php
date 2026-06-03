<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use  App\Models\DocumentosDetalle;
use  App\Models\Cliente;
use  App\Models\User;
use  App\Models\Domicilio;

class Documento extends Model implements Auditable{

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
        'total',
        'metodo_pago',
        'forma_pago',
        'uso_cfdi',
        'observaciones',
        'estatus',
        'vigencia',
        'agente_id',
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
    }

