<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Punto;

class PuntosController extends Controller
{
    //
    public function index(Request $request)
    {
        $puntos = Punto::when($request->search, function ($q, $search) {
            $q->where(function ($query) use ($search) {

                $query->WhereHas('cliente', function ($c) use ($search) {
                    $c->where('nombre', 'like', "%{$search}%");
                });
            });
        })
            ->orderBy('total_puntos', 'desc')
            ->paginate(10)
            ->withQueryString();
        $puntos->load([
            'cliente',
            'movimientos'
        ]);
        return view("puntos.index", ['puntos' => $puntos]);
    }
}
