<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Carbon\Carbon;
use App\Models\User;

class AuditoriaController extends Controller
{
    public function index(Request $request)
{
    $query = Audit::with('user')->latest();

    // 🔍 Buscador general
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('event', 'like', "%{$search}%")
              ->orWhere('auditable_type', 'like', "%{$search}%")
              ->orWhereHas('user', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }

    // 👤 Filtro por usuario
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    // ⚡ Filtro por evento
    if ($request->filled('event')) {
        $query->where('event', $request->event);
    }

    // 📦 Filtro por tipo/modelo
    if ($request->filled('type')) {
        $query->where('auditable_type', $request->type);
    }

    // 📅 Filtro por fechas (correcto)
    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereBetween('created_at', [
            Carbon::parse($request->fecha_inicio)->startOfDay(),
            Carbon::parse($request->fecha_fin)->endOfDay()
        ]);
    }

    $audits = $query->paginate(20)->withQueryString();

    // Para el select de usuarios
    $users = User::orderBy('name')->get();

    return view('auditoria.index', compact('audits', 'users'));
}
    // 📄 Ver detalle de una auditoría
    public function show($id)
    {
        $audit = Audit::with('user')->findOrFail($id);
        // dd($audit);
        return view('auditoria.show', compact('audit'));
    }
}
