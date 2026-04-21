<?php

namespace App\Exports;

use App\Models\DocumentosDetalle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class DocumentosDetallesExport implements FromCollection,    WithHeadings,
    WithEvents,
    WithColumnWidths
{
    public function __construct(
        protected string $fechaInicio,
        protected string $fechaFin,
        ) {}

    public function collection()
    {
        return DocumentosDetalle::join('productos', 'documentos_detalles.producto_id', '=', 'productos.id')
        ->join('documentos', 'documentos_detalles.documento_id', '=', 'documentos.id')
        ->where('documentos.estatus', 4)
        ->whereBetween('documentos.fecha', [
                Carbon::parse($this->fechaInicio)->startOfDay(), Carbon::parse($this->fechaFin)->endOfDay()]
            )
        ->select(
            'productos.nombre_producto as nombre',
            'productos.codigo_producto as codigo',
            DB::raw('SUM(documentos_detalles.cantidad) as cantidad'),
            DB::raw('SUM(documentos_detalles.costo_unitario) as costo'),
            DB::raw('SUM(documentos_detalles.importe) as total')
        )
        ->groupBy('documentos_detalles.producto_id', 'productos.nombre_producto', 'productos.codigo_producto')
        ->get()
    ->map(function ($item) {
        return [
            'codigo'   => $item->codigo ?? '',
            'nombre'   => $item->nombre ?? '',
            'costo'    => $item->costo,
            'cantidad' => $item->cantidad,
            'total'    => $item->total,
        ];
    });
    }
    public function headings(): array
    {
        return [
            'Codigo producto',
            'Nombre producto',
            'Costo unitario',
            'Cantidad',
            'Total',
        ];
    }

    /**
     * ================= ANCHO DE COLUMNAS =================
     */
    public function columnWidths(): array
    {
        return [
            'A' =>20,
            'B' => 20,
            'C' => 15,
            'D' => 10,
            'E' => 15,
        ];
    }

    /**
     * ================= EVENTOS / ESTILOS =================
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                /* ===== TÍTULO ===== */
                $event->sheet->insertNewRowBefore(1, 2);

                $titulo = "Reporte de artículos vendidos ". "del {$this->fechaInicio} al {$this->fechaFin}";

                $event->sheet->setCellValue('A1', $titulo);
                $event->sheet->mergeCells('A1:E1');

                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                /* ===== ENCABEZADOS ===== */
                $headerRange = 'A3:E3';

                $event->sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '4F46E5'],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                /* ===== BORDES DEL CONTENIDO ===== */
                $lastDataRow = $event->sheet->getHighestRow();

                $event->sheet->getStyle("A3:E{$lastDataRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);


                },
        ];
    }



}
