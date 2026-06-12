<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use App\Models\Regimen;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

use function Ramsey\Uuid\v1;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Empresa::all();
        // dd(vars: $empresas);
        return view('empresas.index', ['empresas' => $empresas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $regimenes = Regimen::all();
        return view('empresas.create', ['regimenes' => $regimenes]);
    }

    public function listado($user)
    {
        // Busca el usuario
    $user = User::findOrFail($user);
    //Busca las empresas del usuario
    $empresas = Empresa::when(
        $user->empresa_id,
        fn ($query) => $query->where('id', $user->empresa_id)
    )->get();

    // $empresas = Empresa::all();
        return view('empresas.select', ['empresas' => $empresas]);
    }
    public function set(Request $request)
    {
        $empresa = Empresa::findOrFail($request->empresa_id);
        session([
            'empresa_id' => $empresa->id,
            'nombreEmpresa' => $empresa->nombre,
        ]);
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => $empresa->db_host,
            'port' => $empresa->db_port,
            'database' => $empresa->db_database,
            'username' => $empresa->db_username,
            'password' => $empresa->db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        $sucursales = Sucursal::all();
        return redirect()->route('dashboard', ['sucursales' => $sucursales]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            //DATOS GENERALES
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:250',
            'rfc' => 'required|string|max:13',
            'regimen_fiscal' => 'required|string|max:250',
            'email' => 'required|email',
            // DATOS DE DOMILIO
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'ciudad' => 'required|string|max:100',
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'nullable|string|max:50',
            'cp' => 'required|string|max:6',
        ]);
        // Nombre único para la BD
        $databaseName = $request->nombre . '_empresa_' . Str::slug($request->codigo, '_');

        // Crear la base de datos
        DB::statement("CREATE DATABASE `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Guardar empresa
        $empresa = Empresa::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'rfc' => $request->rfc,
            'regimen_fiscal' => $request->regimen_fiscal,
            'curp' => $request->curp,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'activo' => 1,

            'pais' => 'MÉXICO',
            'estado' => $request->estado,
            'municipio' => $request->municipio,
            'ciudad' => $request->ciudad ?? '',
            'colonia' => $request->colonia,
            'calle' => $request->calle,
            'numero_exterior' => $request->numero_exterior,
            'numero_interior' => $request->numero_interior ?? '',
            'cp' => $request->cp,

            'db_host' => env('DB_HOST'),
            'db_port' => env('DB_PORT'),
            'db_database' => $databaseName,
            'db_username' => env('DB_USERNAME'),
            'db_password' => env('DB_PASSWORD'),
        ]);
        // Configurar conexión temporal
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => $databaseName,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ]);

        DB::purge('tenant');
        //EJECUTA LAS MIGRACIONES
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);
        // Ejecutar seeders
        // Artisan::call('db:seed', [
        //     '--database' => 'tenant',
        //     '--class' => 'Database\\Seeders\\TenantDatabaseSeeder',
        //     '--force' => true,
        // ]);

        $empresas = Empresa::all();
        return view('empresas.select', ['empresas' => $empresas])->with('success',   'La empresa ha sido registrada.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        $regimenes = Regimen::all();
        return view('empresas.show', ['empresa' => $empresa, 'regimenes' => $regimenes]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        $regimenes = Regimen::all();
        return view('empresas.edit', ['empresa' => $empresa, 'regimenes' => $regimenes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:250',
            'rfc' => 'required|string|max:13',
            'regimen_fiscal' => 'required|string|max:250',
            'email' => 'required|email',
            // DATOS DE DOMILIO
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'ciudad' => 'required|string|max:100',
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'nullable|string|max:50',
            'cp' => 'required|string|max:6',
        ]);

        $empresa->update($request->all());
        return redirect()->route('empresas.show', $empresa)
            ->with('success', 'Empresa ha sido actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        //
    }
}
