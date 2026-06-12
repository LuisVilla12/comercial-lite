<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionEmpresa;
use Illuminate\Http\Request;
use App\Models\Sucursal;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $sucursales=Sucursal::all();
        $empresa=ConfiguracionEmpresa::first();
    // // TODO::VERIFICAR COMO USUARIOS PUEDEN ACCEDER A LA SUCURSAL
    //     // $sucursales = $user->isAdmin()
    //     //     ? Sucursal::all()
    //     //     : Sucursal::where('id', $user->sucursal_id)->get();

        return view('dashboard',['sucursales'=>$sucursales,'empresa'=>$empresa]);
    }
}
