<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionEmpresa;
use Illuminate\Http\Request;
use App\Models\Sucursal;
use App\Models\Caja;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $empresa=ConfiguracionEmpresa::first();
    // // TODO::VERIFICAR COMO USUARIOS PUEDEN ACCEDER A LA SUCURSAL
        $sucursales = $user->isAdmin()? Sucursal::all() : Sucursal::where('id', $user->sucursal_id)->get();
        // $cajaAbierta = Caja::where('user_id', auth()->id())->where('estado', 'abierta')->first();
        // return view('dashboard',['sucursales'=>$sucursales,'empresa'=>$empresa,'cajaAbierta'=>$cajaAbierta]);
        return view('dashboard',['sucursales'=>$sucursales,'empresa'=>$empresa]);
    }
}
