@section('title', content: 'Auditoria')
<x-app-layout>
     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Auditoria
        </h2>
    </x-slot>
    <div class="container">

       <div class="bg-white p-4 rounded-lg shadow my-4 mt-5">

    <form method="GET" action="{{ route('auditoria.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            {{-- 🔍 Buscador --}}
            <div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar evento, usuario o tipo..."
                    class="w-full p-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- 👤 Usuario --}}
            <div>
                <select name="user_id"
                    class="w-full p-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">Todos los usuarios</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ⚡ Evento --}}
            <div>
                <select name="event"
                    class="w-full p-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">Todos los eventos</option>
                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Creado</option>
                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Actualizado</option>
                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Eliminado</option>
                </select>
            </div>

            {{-- 📅 Fecha inicio --}}
            <div>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                    class="w-full p-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- 📅 Fecha fin --}}
            <div>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}"
                    class="w-full p-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-2 mt-4">

            <a href="{{ route('auditoria.index') }}"
                class="px-4 py-2 bg-gray-200 rounded-lg text-sm hover:bg-gray-300">
                Limpiar
            </a>

            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                Filtrar
            </button>

        </div>
    </form>

</div>

        <div class="shadow-md overflow-x-auto rounded-lg ">
            @if ($audits->count() > 0)
                <div class="hidden md:block">
                    <table class="w-full border bg-white shadow rounded">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2">Codigo</th>
                                <th class="p-2">Usuario</th>
                                <th class="p-2">Evento</th>
                                <th class="p-2">Modulo</th>
                                <th class="p-2">Fecha</th>
                                <th class="p-2">Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audits as $audit)
                                <tr class="border-t">
                                    <td class="p-2 text-center">
                                        {{ $audit->id }}
                                    </td>
                                    <td class="p-2">
                                        {{ $audit->user->name ?? 'Sistema' }}
                                    </td>
                                    <td class="p-2 text-center">
                                        @if ($audit->event == 'created')
                                            <span class="badge bg-success">Creado</span>
                                        @elseif($audit->event == 'updated')
                                            <span class="badge bg-warning text-dark">Actualizado</span>
                                        @elseif($audit->event == 'deleted')
                                            <span class="badge bg-danger">Eliminado</span>
                                        @endif
                                    </td>

                                    <td class="p-2">
                                        {{ class_basename($audit->auditable_type) }}
                                    </td>
                                    <td class="p-2">
                                {{ $audit->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="flex justify-center items-center gap-4">
                                            {{-- Ver --}}
                                            <a href="{{ route('auditoria.show', $audit->id) }}"
                                                class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                                <x-heroicon-o-eye class="w-4 h-4" />
                                                <span class="hidden sm:inline">Ver</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- CARDS: visible en tablet y móvil -->
                <div class="md:hidden space-y-4">
                    @foreach ($audits as $audit)
                        <div class="border rounded-lg shadow bg-white p-4">
                            <div class="mt-2">
                                <div class="mb-2 text-sm text-gray-500">
                                    <span>Codigo:</span>
                                    <span class="font-medium text-gray-800">
                                        {{ $audit->id }}
                                    </span>
                                </div>
                                <div class="">
                                    <p class="mb-2 text-sm">Usuario:
                                        <span class="font-semibold">
                                            {{ $audit->user->name ?? 'Sistema' }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="mb-2 text-sm">Evento:
                                        <span class="font-semibold">
                                            @if ($audit->event == 'created')
                                                <span class="badge bg-success">Creado</span>
                                            @elseif($audit->event == 'updated')
                                                <span class="badge bg-warning text-dark">Actualizado</span>
                                            @elseif($audit->event == 'deleted')
                                                <span class="badge bg-danger">Eliminado</span>
                                            @endif

                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center justify-end mt-4 gap-4">
                                {{-- Ver --}}
                                <a href="{{ route('auditoria.show', $audit->id) }}"
                                    class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                    <span class="hidden sm:inline">Ver</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white py-4 mt-3">
                    <p class="text-sm text-gray-600 ml-6 text-center"> No hay almacenes registrados</p>
                </div>
            @endif
            @if ($audits->count() > 0)
                <div class="bg-white py-4 my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <p class="text-sm text-gray-600 ml-6">
                        Mostrando
                        <span class="font-medium">{{ $audits->firstItem() }}</span>
                        a
                        <span class="font-medium">{{ $audits->lastItem() }}</span>
                        de
                        <span class="font-medium">{{ $audits->total() }}</span>
                        registros
                    </p>

                    {{ $audits->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
