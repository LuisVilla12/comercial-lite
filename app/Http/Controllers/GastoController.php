<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Caja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class GastoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Gasto::query();
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        //FILTRO POR SUCURSAL
         if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        // 📅 Filtro por fechas (correcto)
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereBetween('created_at', [
            Carbon::parse($request->fecha_inicio)->startOfDay(),
            Carbon::parse($request->fecha_fin)->endOfDay()
        ]);
    }

        $gastos = $query->paginate(10)->withQueryString();
        $users = User::all();
        return view("gastos.index", ['gastos' => $gastos, 'users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $cajas = Caja::all();

        return view("gastos.create", ["cajas" => $cajas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "descripcion" => "required|string|max:250",
            "total" => "required|integer",
            "tipo" => "required",
            "caja_id" => "required",
            "user_id" => "required",
        ]);

        DB::transaction(function () use ($request) {
            $folio = (Gasto::lockForUpdate()->max('folio') ?? 0) + 1;

            Gasto::create([
                'fecha'       => now(),
                'folio'       => $folio,
                'tipo'       => $request->tipo,
                'descripcion' => $request->descripcion,
                'total'       => $request->total,
                'caja_id'     => $request->caja_id,
                'user_id'     => $request->user_id,
            ]);
        });

        return redirect()->route("gastos.index")->with("success", "Se registro el gasto correctamente");
    }

    /**
     * Display the specified resource.
     */
    public function show($gasto)
    {
        $gasto = Gasto::findOrFail($gasto);
        return view("gastos.show", ["gasto" => $gasto]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gasto $gasto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gasto $gasto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gasto $gasto)
    {
        //
    }
}
