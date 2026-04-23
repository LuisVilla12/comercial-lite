<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with('user')->latest();
        // 🔍 Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        // 🔍 Filtro por evento (created, updated, deleted)
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        // 🔍 Filtro por tabla/modelo
        if ($request->filled('type')) {
            $query->where('auditable_type', $request->type);
        }
        // 🔍 Filtro por fecha
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('created_at', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        }
        $audits = $query->paginate(20)->withQueryString();
        return view('auditoria.index', compact('audits'));
    }

    // 📄 Ver detalle de una auditoría
    public function show($id)
    {
        $audit = Audit::with('user')->findOrFail($id);
        return view('auditoria.show', compact('audit'));
    }
}