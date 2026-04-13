<?php

namespace App\Http\Controllers;
use App\Http\Middleware\AdminMiddleware;

use App\Models\Sucursal;
use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function index(Request $request)
    {
    $search = $request->get('search');

    $usuarios = User::where('estatus', 1)
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString(); // ← mantiene el search en la paginación

    return view('users.index', compact('usuarios', 'search'));
    }
    public function show(User $usuario){
        $usuario->load([
            'sucursal',
        ]);

        return view('users.show',['usuario'=> $usuario]);
    }

     public function edit(User $usuario)
    {
        // dd($usuario);
        $sucursales=Sucursal::all();
        return view('users.edit', ['usuario'=>$usuario,'sucursales'=>$sucursales]);
    }

    public function destroy(User $usuario)
    {
    $usuario->delete();

    return redirect()
        ->route('usuarios.index')
        ->with(
            'success', 'El usuario se ha eliminado correctamente.'
        );
    }
    public function update(Request $request, User $usuario){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'integer', 'in:1,2,3,4,5'],
            'sucursal_id' => ['required', 'integer'],
        ]);

        $usuario->update($request->all());

 return redirect()
        ->route('usuarios.index')
        ->with(
            'success', 'El usuario se ha actualizado correctamente.'
        );
    }
}
