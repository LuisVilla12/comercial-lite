<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $empresas=Empresa::all();
        return view('auth.register',['empresas'=>$empresas]);
    }
    // OBTIENE LAS SUCURSALES
    public function sucursales(Empresa $empresa){

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

    return Sucursal::select('id', 'nombre')->get();
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'tipo' => ['required', 'integer', 'in:1,2,3,4,5'],
            'empresa_id' => ['required', 'integer'],
            'sucursal_id' => ['required', 'integer'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'tipo' => $request->tipo,
            'empresa_id' => $request->empresa_id,
            'sucursal_id' => $request->sucursal_id,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('usuarios.index')->with('success', 'Registro exitoso');
    }
}
