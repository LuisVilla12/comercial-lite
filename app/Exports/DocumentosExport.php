<?php

namespace App\Exports;

use App\Models\Documento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DocumentosExport implements
    FromCollection,
    WithHeadings,
    WithEvents,
    WithColumnWidths
{
    public function __construct(
        protected int $documentoModeloId,
        protected string $fechaInicio,
        protected string $fechaFin
    ) {}

    /**
     * Datos del Excel
     */
    public function collection()
    {
        return Documento::with(['cliente', 'usuario'])
            ->where('documento_modelo_id', $this->documentoModeloId)
            ->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin])
            ->get()
            ->map(function ($doc) {
                return [
                    'folio'        => $doc->folio,
                    'fecha'        => $doc->fecha,
                    'cliente'      => $doc->cliente->nombre ?? '',
                    'usuario'      => $doc->usuario->name ?? '',
                    'metodo_pago'  => $doc->metodo_pago,
                    'total'        => $doc->total,
                ];
            });
    }

    /**
     * Encabezados (van en la fila 3)
     */
    public function headings(): array
    {
        return [
            'Folio',
            'Fecha',
            'Cliente',
            'Usuario',
            'Forma de pago',
            'Total',
        ];
    }

    /**
     * Ancho de columnas
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 15,
            'C' => 25,
            'D' => 10,
            'E' => 10,
            'F' => 15,
        ];
    }

    /**
     * Estilos, título y bordes
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // Insertar filas para el título
                $event->sheet->insertNewRowBefore(1, 2);

                $titulo = "Reporte de ventas {$this->documentoModeloId} "
                        . "del {$this->fechaInicio} al {$this->fechaFin}";

                // Título
                $event->sheet->setCellValue('A1', $titulo);
                $event->sheet->mergeCells('A1:F1');

                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                // Encabezados (fila 3)
                $headerRange = 'A3:F3';

                $event->sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '4F46E5'], // Indigo
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

                // Bordes para todo el contenido
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle("A3:F{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}
