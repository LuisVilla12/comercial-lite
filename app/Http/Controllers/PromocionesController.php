<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promocion;

class PromocionesController extends Controller
{
    //
    public function index(){
        $promociones = Promocion::query();
        $promociones = $promociones->paginate(10);
        return view("promocion.index",["promociones"=> $promociones]);
    }
    public function create(){
        return view("promocion.create");
    }
    public function store(Request $request){
        $request->validate([
            'name'=> 'required|string',
            'codigo'=> 'required|string',
            'tipo'=> 'required|string',
            'valor'=> 'required',
            'fecha_inicio'=> 'required|date',
            'fecha_fin'=> 'required|date',
        ]);

        $promocion=Promocion::create([
            'nombre'=> $request->name,
            'codigo'=> $request->codigo,
            'tipo'=> $request->tipo,
            'valor'=> $request->valor,
            'fecha_inicio'=> $request->fecha_inicio,
            'fecha_fin'=> $request->fecha_fin,
        ]);

        return redirect()->route('promociones.index')->with('success','Promoción registrada correctamente');
    }
    public function edit($promocion){
        $promocion = Promocion::find($promocion);
        return view('promocion.edit',['promocion'=> $promocion]);
    }

    public function update(Request $request, $promocion){
        $request->validate([
            'name'=> 'required|string',
            'codigo'=> 'required|string',
            'tipo'=> 'required|string',
            'valor'=> 'required',
            'fecha_inicio'=> 'required|date',
            'fecha_fin'=> 'required|date',
        ]);

        $promocion = Promocion::find($promocion);
        $promocion->update([
        'nombre'=> $request->name,
            'codigo'=> $request->codigo,
            'tipo'=> $request->tipo,
            'valor'=> $request->valor,
            'fecha_inicio'=> $request->fecha_inicio,
            'fecha_fin'=> $request->fecha_fin,
        ]);

        return redirect()->route('promociones.show',$promocion)->with('success','Actualizado correctamente');
    }
    public function show($promocion){
    $promocion = Promocion::find($promocion);
    return view('promocion.show',['promocion'=> $promocion]);

    }

    public function destroy($promocion){
        // BUSCAR PROMOCION
        $promocion=Promocion::find($promocion);
        //ELIMINAR
        $promocion->delete();
        return redirect()->back()->with('success','Promocion eliminada correctamente');
    }

    public function select(){
        return view('promocion.definir');
    }

    public function definir(Request $request){
        dd($request->all());
        $request->validate([]);
    }

}

