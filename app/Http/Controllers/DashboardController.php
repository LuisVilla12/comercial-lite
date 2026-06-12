<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sucursal;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $sucursales=Sucursal::all();
    // // TODO::VERIFICAR COMO USUARIOS PUEDEN ACCEDER A LA SUCURSAL
    //     // $sucursales = $user->isAdmin()
    //     //     ? Sucursal::all()
    //     //     : Sucursal::where('id', $user->sucursal_id)->get();

        return view('dashboard',['sucursales'=>$sucursales]);
    }
}
