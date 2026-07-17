<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Models\ConfiguracionEmpresa;
use App\Models\Sucursal;
use App\Models\Caja;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // VALIDAR
        $user = auth()->user();
        $empresa=ConfiguracionEmpresa::first();
        // // TODO::VERIFICAR COMO USUARIOS PUEDEN ACCEDER A LA SUCURSAL
        if($user->hasRole("Administrador")){
            $sucursales = Sucursal::all();
        }else{
         $sucursales= Sucursal::where('id', $user->sucursal_id)->get();
        }
        $cajaAbierta = Caja::where('user_id', auth()->id())->where('estado', 'abierta')->first();


        return view('layouts.app',['sucursales'=>$sucursales,'empresa'=>$empresa,'cajaAbierta'=>$cajaAbierta]);
    }
}
