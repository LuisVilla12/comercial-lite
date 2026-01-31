<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sucursal;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $sucursales = $user->isAdmin()
            ? Sucursal::all()
            : Sucursal::where('id', $user->sucursal_id)->get();

        return view('dashboard', compact('sucursales'));
    }
}
