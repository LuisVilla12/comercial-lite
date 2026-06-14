<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SesionesController extends Controller
{
    //
    public function index(){

$sesiones = DB::table('sessions')
    ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
    ->select(
        'sessions.id',
        'users.name',
        'users.email',
        'sessions.ip_address',
        'sessions.user_agent',
        'sessions.last_activity'
    )
    ->orderByDesc('sessions.last_activity')
    ->paginate(10);
    // dd($sesiones);
        return view('sesions.index',['sesiones'=>$sesiones]);
    }

    public function destroy($sessionId)
{
    DB::table('sessions')
        ->where('id', $sessionId)
        ->delete();

    return back()->with('success', 'La sesión fue cerrada correctamente.');
}
//SACAR A TODOS LOS USUARIOS
public function destroyAll()
{
    DB::table('sessions')->delete();

    return back()->with('success', 'La sesión fue cerrada correctamente.');
}
    }
