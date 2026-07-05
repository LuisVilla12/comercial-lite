<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;


class FacturacionController extends Controller
{
    //
    public function create(Request $request)
    {
        // CONECTAR A LA BASE DE DATOS DEL TENANT
        $empresa = Empresa::findOrFail(1);
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
        ]);


        DB::purge('tenant');
        DB::reconnect('tenant');

        $documento = Documento::where('serie', $request->input('serie'))
            ->where('folio', $request->input('folio'))
            ->where('estatus', 1)->first();

        return view('facturas.online', compact('documento'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'razon_social' => 'required|string|max:255',
            'rfc' => 'required|string|max:13',
            'regimen_fiscal' => 'required|string|max:255',
            'usos_cfdi' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cp' => 'required|string|max:6',
            'documento_id' => 'required',
        ]);
        //BUSCAR EL DOCUMENTO EN LA BASE DE DATOS DEL TENANT
        $documento = Documento::findOrFail($request->documento_id);
        
        }
}
