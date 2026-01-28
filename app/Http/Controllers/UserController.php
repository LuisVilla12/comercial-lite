<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    //
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
        return view('users.show',['usuario'=> $usuario]);
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
}
