<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = [
        'id',
        'documento_modelo_id',
        'serie',
        'folio',
        'fecha',
        'cliente_id',
        'almacen_id',
        'user_id',
        'subtotal',
        'impuestos',
        'total',
        'metodo_pago',
        'forma_pago',
        'uso_cfdi',
        'observaciones',
        'estatus',
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

