<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use App\Models\Sucursal;
use App\Models\Documento;
use App\Models\ConfiguracionEmpresa;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentoMail;

class EnviarDocumentoMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $empresaId,
        public int $sucursalId,
        public int $documentoId,
        public string $email
    ) {}

    public function handle()
    {
        $this->conectarTenant();

        $sucursal = Sucursal::findOrFail($this->sucursalId);

        $documento = Documento::with([
            'cliente',
            'detalles.producto'
        ])->findOrFail($this->documentoId);

        $empresa = ConfiguracionEmpresa::first();

        Mail::to($this->email)
            ->send(new DocumentoMail(
                $sucursal,
                $documento,
                $empresa
            ));

    }
    private function conectarTenant()
    {
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
    }
}
