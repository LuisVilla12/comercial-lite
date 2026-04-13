<?php

namespace App\Exports;

use App\Models\DocumentosDetalle;
use Maatwebsite\Excel\Concerns\FromCollection;

class DocumentosDetallesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DocumentosDetalle::with('producto')
    ->whereHas('documento', function ($q) {
        $q->where('estatus', 4)
          ->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin]);
    })
    ->select(
        'producto_id',
        DB::raw('SUM(cantidad) as cantidad'),
        DB::raw('SUM(total) as total')
    )
    ->groupBy('producto_id')
    ->get()
    ->map(function ($item) {
        return [
            'nombre'   => $item->producto->nombre ?? '',
            'cantidad' => $item->cantidad,
            'total'    => $item->total,
        ];
    });
    }
}
