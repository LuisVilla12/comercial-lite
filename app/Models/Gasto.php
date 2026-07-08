<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends TenantModel
{
    protected $fillable = [
        'fecha',
        'folio',
        'total',
        'descripcion',
        'tipo',
        'caja_id',
        'user_id',
    ];
    protected $casts = [
    'fecha' => 'datetime'
    ];


    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

}
