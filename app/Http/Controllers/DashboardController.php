<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sucursal;

class DashboardController extends Controller
{
     public function index()
    {
        return view('dashboard', [
            'sucursales' => Sucursal::all()
        ]);
    }
}
