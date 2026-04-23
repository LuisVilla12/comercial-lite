@section('title', content: 'Clientes' )

<x-app-layout>
<div class="container">
    <h2 class="mb-4">📊 Auditoría del sistema</h2>
    <form method="GET" action="{{ route('auditoria.index') }}" class="row mb-4">

        <div class="col-md-2">
            <input type="text" name="user_id" class="form-control" placeholder="Usuario ID"
                   value="{{ request('user_id') }}">
        </div>

        <div class="col-md-2">
            <select name="event" class="form-control">
                <option value="">Evento</option>
                <option value="created" {{ request('event')=='created' ? 'selected' : '' }}>Creado</option>
                <option value="updated" {{ request('event')=='updated' ? 'selected' : '' }}>Actualizado</option>
                <option value="deleted" {{ request('event')=='deleted' ? 'selected' : '' }}>Eliminado</option>
            </select>
        </div>

        <div class="col-md-3">
            <input type="text" name="type" class="form-control" placeholder="Modelo (App\Models\Producto)"
                   value="{{ request('type') }}">
        </div>

        <div class="col-md-2">
            <input type="date" name="fecha_inicio" class="form-control"
                   value="{{ request('fecha_inicio') }}">
        </div>

        <div class="col-md-2">
            <input type="date" name="fecha_fin" class="form-control"
                   value="{{ request('fecha_fin') }}">
        </div>

        <div class="col-md-1">
            <button class="btn btn-primary w-100">🔍</button>
        </div>

    </form>

    {{-- 📋 TABLA --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Evento</th>
                        <th>Módulo</th>
                        <th>Registro</th>
                        <th>Fecha</th>
                        <th>Detalle</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($audits as $audit)
                        <tr>
                            <td>{{ $audit->id }}</td>

                            <td>
                                {{ $audit->user->name ?? 'Sistema' }}
                            </td>

                            <td>
                                @if($audit->event == 'created')
                                    <span class="badge bg-success">Creado</span>
                                @elseif($audit->event == 'updated')
                                    <span class="badge bg-warning text-dark">Actualizado</span>
                                @elseif($audit->event == 'deleted')
                                    <span class="badge bg-danger">Eliminado</span>
                                @endif
                            </td>

                            <td>
                                {{ class_basename($audit->auditable_type) }}
                            </td>

                            <td>
                                {{ $audit->auditable_id }}
                            </td>

                            <td>
                                {{ $audit->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <a href="{{ route('auditoria.show', $audit->id) }}" 
                                   class="btn btn-sm btn-info">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Sin registros</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 📄 PAGINACIÓN --}}
    <div class="mt-3">
        {{ $audits->links() }}
    </div>
</div>
</x-app-layout>