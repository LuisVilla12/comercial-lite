<?php

namespace App\Services;

use App\Models\ExistenciaProducto;
use Exception;

class InventarioService
{
    public static function sumar(int $productoId, int $almacenId, float $cantidad): void
    {
        $existencia = ExistenciaProducto::where('producto_id', $productoId)
            ->where('almacen_id', $almacenId)
            ->lockForUpdate()
            ->first();

        if ($existencia) {
            $existencia->increment('cantidad', $cantidad);
        } else {
            ExistenciaProducto::create([
                'producto_id' => $productoId,
                'almacen_id'  => $almacenId,
                'cantidad'    => $cantidad,
            ]);
        }
    }

    public static function restar(int $productoId, int $almacenId, float $cantidad): void
    {
        $existencia = ExistenciaProducto::where('producto_id', $productoId)
            ->where('almacen_id', $almacenId)
            ->lockForUpdate()
            ->first();

        if (!$existencia) {
            throw new Exception('No existe stock del producto');
        }


        $existencia->decrement('cantidad', $cantidad);
    }
}
