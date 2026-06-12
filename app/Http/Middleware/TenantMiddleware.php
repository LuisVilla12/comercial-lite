<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;


class TenantMiddleware
{
    public function handle($request, Closure $next)
    {
        if (session()->has('empresa_id')) {
            $empresa = Empresa::find(session('empresa_id'));
            View::share('empresaActual', $empresa);

            if ($empresa) {

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
        return $next($request);
    }
}
