@section('title', content: 'Ajuste de almacen')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Listado de @if ($tipo == 1)
                Entradas
            @else
                Salidas
            @endif de almacén
        </h2>
    </x-slot>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">

        <form method="GET" action="{{ route('ajustes-almacen.index',$tipo) }}"
            class="w-full flex flex-col md:flex-row md:items-center gap-3">

            {{-- Buscador --}}
            <div class="relative w-full md:w-1/2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar ajuste de inventario..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
                @if (request('search'))
                    <a href="{{ route('ajustes-almacen.index',$tipo) }}"
                        class="inline-block mt-1 text-sm text-gray-500 hover:text-indigo-600">
                        Limpiar búsqueda
                    </a>
                @endif
            </div>

            {{-- Filtro por fecha --}}
            <select name="fecha" onchange="this.form.submit()"
                class="p-2 w-full md:w-1/4 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">Todas</option>
                <option value="hoy" {{ request('fecha') === 'hoy' ? 'selected' : '' }}>
                    Hoy
                </option>
            </select>


        </form>
        <div x-data @keydown.window.prevent.f9="$refs.btnEntrada.click()">
            {{-- Botón --}}
            <a href="{{ route('ajustes-almacen.create', $tipo) }}" x-ref="btnEntrada"
                class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-md font-medium shadow transition whitespace-nowrap">
                Registrar
                @if ($tipo == 1)
                    Entrada
                @else
                    Salida
                @endif
                [F9]
            </a>
        </div>

    </div>
    @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">{{ session('success') }}
        </p>
    @endif


    <div class="shadow-md overflow-x-auto rounded-lg">
        @if ($ajustes->count() > 0)
            <div class="hidden md:block">
                <table class="w-full border bg-white shadow rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Fecha</th>
                            <th class="p-2">Folio</th>
                            <th class="p-2">Almacen</th>
                            <th class="p-2">Estado</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ajustes as $ajuste)
                            <tr class="border-t">
                                <td class="p-2 text-center">
                                    {{ $ajuste->fecha }}
                                </td>
                                <td class="p-2">
                                    {{ $ajuste->id }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $ajuste->almacen->nombre }}
                                </td>
                                <td class="p-2 text-center">
                                    @if ($ajuste->estatus == 1)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                            Activo
                                        </span>
                                    @elseif ($ajuste->estatus == 4)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                            Surtida
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex justify-center items-center gap-4">
                                        {{-- Ver --}}
                                        <a href="{{ route('ajustes-almacen.show', $ajuste) }}"
                                            class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                            <x-heroicon-o-eye class="w-4 h-4" />
                                            <span class="hidden sm:inline">Ver</span>
                                        </a>
                                        @if ($ajuste->estatus == 1)
                                            <span class="hidden sm:inline text-gray-300">•</span>
                                            {{-- Editar --}}
                                            <a href=""
                                                class="inline-flex items-center gap-1 text-gray-600 hover:text-indigo-600 transition">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                <span class="hidden sm:inline">Editar</span>
                                            </a>
                                            <span class="hidden sm:inline text-gray-300">•</span>

                                            {{-- Eliminar --}}
                                            <form action="{{ route('ajustes-almacen.destroy', $ajuste )}}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 text-gray-500 hover:text-red-600 transition"
                                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                    <span class="hidden sm:inline">Eliminar</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- CARDS: visible en tablet y móvil -->
            <div class="md:hidden space-y-4">
                @foreach ($ajustes as $ajuste)
                    <div class="border rounded-lg shadow bg-white p-4">
                        <div class="flex justify-between mt-2">
                            <div class=" text-sm text-gray-500">
                                <span>Fecha</span>
                                <span class="font-medium text-gray-800">
                                    {{ $ajuste->fecha }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm">Folio:
                                    <span class="font-semibold">
                                        {{ $ajuste->id }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 mb-3 text-sm">
                            <div>
                                <p class="text-gray-500">Almacen
                                    <span class="font-semibold  text-gray-800">
                                        {{ $ajuste->almacen->nombre }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 mb-3 text-sm">
                        </div>

                        <div class="flex flex-wrap items-center justify-end mt-4 gap-4">
                            {{-- Ver --}}
                            <a href="{{ route('ajustes-almacen.show', $ajuste) }}"
                                class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                <x-heroicon-o-eye class="w-4 h-4" />
                                <span class="hidden sm:inline">Ver</span>
                            </a>
                            {{-- @if ($entrada->estatus == 1)
                                <span class="hidden sm:inline text-gray-300">•</span>
                                <a href="{{ route('entradas-almacen.edit', ['entrada' => $entrada]) }}"
                                    class="inline-flex items-center gap-1 text-gray-600 hover:text-indigo-600 transition">
                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    <span class="hidden sm:inline">Editar</span>
                                </a>
                                <span class="hidden sm:inline text-gray-300">•</span>

                                <form
                                    action="{{ route('entradas-almacen.destroy', ['entrada' => $entrada]) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="inline-flex items-center gap-1 text-gray-500 hover:text-red-600 transition"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                        <span class="hidden sm:inline">Eliminar</span>
                                    </button>
                                </form>
                            @endif --}}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white py-4 mt-3">
                <p class="text-sm text-gray-600 ml-6 text-center"> No hay ajustes de inventario</p>
            </div>
        @endif
        @if ($ajustes->count() > 0)
            <div class="bg-white py-4 my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <p class="text-sm text-gray-600 ml-6">
                    Mostrando
                    <span class="font-medium">{{ $ajustes->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $ajustes->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $ajustes->total() }}</span>
                    registros
                </p>

                {{ $ajustes->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
