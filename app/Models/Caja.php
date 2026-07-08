<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\Model;

class Caja extends TenantModel implements Auditable
{
        use AuditableTrait;
    protected $fillable = [
        'sucursal_id',
        'user_id',
        'fecha_apertura',
        'monto_inicial',
        'fecha_cierre',
        'total_ventas',
        'total_gastos',
        'total_documentos',
        // 'diferencia',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
