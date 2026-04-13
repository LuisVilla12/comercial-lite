<?php

namespace App\Exports;

use App\Models\Documento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DocumentsProductsExport implements
    FromCollection,
    WithHeadings,
    WithEvents,
    WithColumnWidths
{
    public function __construct(
        protected array $series,
        protected array $documentoModeloIds,
        protected string $fechaInicio,
        protected string $fechaFin,
        protected string $user_id,
    ) {}

    /**
     * ================= DATOS DEL EXCEL =================
     */
    public function collection()
    {
        return Documento::with(['cliente', 'usuario'])
            ->whereIn('documento_modelo_id', $this->documentoModeloIds)
            ->whereIn('serie', $this->series)
            ->where('estatus', 4)
            ->where('user_id', $this->user_id)
            ->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin])
            ->orderBy('fecha')
            ->get()
            ->map(function ($doc) {
                return [
                    'serie'      => $doc->serie,
                    'folio'      => $doc->folio,
                    'fecha'      => $doc->fecha,
                    'cliente'    => $doc->cliente->nombre ?? '',
                    'usuario'    => $doc->usuario->name ?? '',
                    'forma_pago' => $this->getFormaPagoTexto($doc->forma_pago),
                    'total'      => $doc->total,
                ];
            });
    }

    /**
     * ================= ENCABEZADOS =================
     */
    public function headings(): array
    {
        return [
            'Serie',
            'Folio',
            'Fecha',
            'Cliente',
            'Usuario',
            'Forma de pago',
            'Total',
        ];
    }

    /**
     * ================= ANCHO DE COLUMNAS =================
     */
    public function columnWidths(): array
    {
        return [
            'A' =>7,
            'B' => 7,
            'C' => 12,
            'D' => 20,
            'E' => 12,
            'F' => 12,
            'G' => 12,
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

                $titulo = "Reporte de ventas ({$this->getTiposTexto()}) "
                    . "del {$this->fechaInicio} al {$this->fechaFin}";

                $event->sheet->setCellValue('A1', $titulo);
                $event->sheet->mergeCells('A1:G1');

                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                /* ===== ENCABEZADOS ===== */
                $headerRange = 'A3:G3';

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

                $event->sheet->getStyle("A3:G{$lastDataRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                /* ================= RESUMEN POR FORMA DE PAGO ================= */
                $inicioDatos = 4; // después del título y encabezados
                $totales = [];

                for ($row = $inicioDatos; $row <= $lastDataRow; $row++) {

                    $formaPagoTexto = $event->sheet->getCell("F{$row}")->getValue();
                    $total = (float) $event->sheet->getCell("G{$row}")->getValue();

                    if (!$formaPagoTexto) {
                        continue;
                    }

                    $codigo = substr($formaPagoTexto, 0, 2);
                    $grupo = $this->getGrupoFormaPago($codigo);

                    $totales[$grupo] = ($totales[$grupo] ?? 0) + $total;
                }

                // Insertar resumen
                $resumenInicio = $lastDataRow + 2;

                $event->sheet->setCellValue("F{$resumenInicio}", 'RESUMEN');
                $event->sheet->getStyle("F{$resumenInicio}")->getFont()->setBold(true);

                $fila = $resumenInicio + 1;
                $sumaGeneral = 0;

                foreach ($totales as $grupo => $monto) {
                    $event->sheet->setCellValue("F{$fila}", $grupo);
                    $event->sheet->setCellValue("G{$fila}", $monto);
                    $sumaGeneral += $monto;
                    $fila++;
                }

                // TOTAL GENERAL
                $event->sheet->setCellValue("F{$fila}", 'TOTAL GENERAL');
                $event->sheet->setCellValue("G{$fila}", $sumaGeneral);
                $event->sheet->getStyle("F{$fila}:G{$fila}")->getFont()->setBold(true);
            },
        ];
    }

    /**
     * ================= HELPERS =================
     */

    private function getTiposTexto(): string
    {
        $mapa = [
            2 => 'Facturas',
            3 => 'Remisiones',
        ];

        return collect($this->documentoModeloIds)
            ->map(fn ($id) => $mapa[$id] ?? 'Desconocido')
            ->implode(' y ');
    }

    private function getFormaPagoTexto(?string $codigo): string
    {
        $formas = [
            '01' => '01 Efectivo',
            '02' => '02 Cheque nominativo',
            '03' => '03 Transferencia electrónica',
            '04' => '04 Tarjeta de crédito',
            '05' => '05 Monedero electrónico',
            '28' => '28 Tarjeta de débito',
        ];

        return $formas[$codigo] ?? $codigo ?? '';
    }

    private function getGrupoFormaPago(string $codigo): string
    {
        return match ($codigo) {
            '01' => 'Efectivo',
            '03' => 'Transferencia',
            '02' => 'Cheque',
            '04', '28' => 'Tarjeta',
            '05' => 'Monedero electrónico',
            default => 'Otros',
        };
    }
}
