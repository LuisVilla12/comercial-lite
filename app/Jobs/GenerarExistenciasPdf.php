<?php

namespace App\Jobs;

use App\Models\ExistenciaProducto;
use App\Models\Empresa;
use App\Models\Reporte;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerarExistenciasPdf implements ShouldQueue
{
    use Queueable;

    public $timeout = 1800; // 30 minutos

    protected $search;
    protected $almacen_id;
    protected $userId;
    protected $empresaId;
    protected $reporteId;

    public function __construct($search, $almacen_id, $userId,$empresaId,$reporteId)
    {
        $this->search = $search;
        $this->almacen_id = $almacen_id;
        $this->userId = $userId;
        $this->empresaId = $empresaId;
        $this->reporteId = $reporteId;
    }

    public function handle(): void
    {
        ini_set('memory_limit', '20480M');
        set_time_limit(0);

$empresa = Empresa::findOrFail($this->empresaId);

    Config::set('database.connections.tenant', [
        'driver' => 'mysql',
        'host' => $empresa->db_host,
        'port' => $empresa->db_port,
        'database' => $empresa->db_database,
        'username' => $empresa->db_username,
        'password' => $empresa->db_password,
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
    ]);

    DB::purge('tenant');
    DB::reconnect('tenant');


    logger()->info('Base de datos: ' . DB::connection('tenant')->getDatabaseName());
    logger()->info('Empresa: ' . $this->empresaId);
    logger()->info('Empresa: ' . $this->empresaId);
        $existencias = ExistenciaProducto::with([
            'producto',
            'almacen'
        ])
             ->when($this->search, function ($q) {
                 $search = $this->search;
                 $q->whereHas('producto', function ($p) use ($search) {
                     $p->where('nombre_producto', 'like', "%{$search}%")
                         ->orWhere('codigo_producto', 'like', "%{$search}%");
                 });
             })
            ->when($this->almacen_id, function ($q) {
                 $q->where('almacen_id', $this->almacen_id);
             })
             ->get();
            
            logger()->info('Existencias encontradas: '.$existencias->count());

            $pdf = Pdf::loadView(
            'existencias.pdf',
            compact('existencias')
        )->setPaper('letter');

        $nombreArchivo = 'existencias_' .
            $this->userId .
            '_' .
            now()->format('Ymd_His') .
            '.pdf';
        // BUSCA REPORTE
        $reporte = Reporte::findOrFail($this->reporteId);
        
        Storage::disk('public')->put(
            'reportes/' . $nombreArchivo,
            $pdf->output()
        );
        //ACTUALIZA
        $reporte->update([
    'archivo' => $nombreArchivo,
    'estado' => 'Finalizado',
]);        
    }
}