<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    protected $fillable = [
        'codigo',
        'nombre',

        // SERIES
        'serie_cotizacion',
        'serie_remision',
        'serie_factura',
        'serie_devolucion',

        // FOLIOS
        'folio_cotizacion',
        'folio_remision',
        'folio_factura',
        'folio_devolucion',

        // RELACIONES
        'almacen_id',
        'empresa_id',
    ];

    /* =========================
     | RELACIONES
     ========================= */
    public function domicilios()
    {
        return $this->morphMany(Domicilio::class, 'domiciliable');
    }
    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }


    /* =========================
     | FOLIOS (SEGUROS)
     ========================= */

    /**
     * Obtiene el siguiente folio según el tipo de documento
     * cotizacion | remision | factura
     */
    public function nextFolio(string $tipo): int
    {
        if (!in_array($tipo, ['cotizacion', 'remision', 'factura'])) {
            throw new \InvalidArgumentException('Tipo de documento inválido');
        }

        return DB::transaction(function () use ($tipo) {
            $this->lockForUpdate();

            $campo = "folio_{$tipo}";
            $this->$campo++;

            $this->save();

            return $this->$campo;
        });
    }

    /**
     * Obtiene la serie según el tipo de documento
     */
    public function getSerie(string $tipo): string
    {
        $campo = "serie_{$tipo}";

        return $this->$campo;
    }
}
