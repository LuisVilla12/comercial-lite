@section('title', content: 'Remisiones')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Remisiones {{ $sucursal->nombre }}
        </h2>
    </x-slot>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">

        <form method="GET" action="{{ route('remisiones.index', $sucursal) }}"
            class="w-full flex flex-col md:flex-row md:items-center gap-3">

            {{-- Buscador --}}
            <div class="relative w-full md:w-1/2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar remisión..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </div>

            {{-- Filtro por fecha --}}
            <select name="fecha" onchange="this.form.submit()"
                class="p-2 w-full md:w-1/4 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">Todas</option>
                <option value="hoy" {{ request('fecha') === 'hoy' ? 'selected' : '' }}>
                    Hoy
                </option>
            </select>

            {{-- Filtro por estatus --}}
            <select name="estatus" onchange="this.form.submit()"
                class="p-2 w-full md:w-1/4 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">Todas</option>
                <option value="1" {{ request('estatus') === '1' ? 'selected' : '' }}>
                    Activa
                </option>
                <option value="2" {{ request('estatus') === '2' ? 'selected' : '' }}>
                    Transformada
                </option>
                <option value="3" {{ request('estatus') === '3' ? 'selected' : '' }}>
                    Cancelada
                </option>
                <option value="4" {{ request('estatus') === '4' ? 'selected' : '' }}>
                    Surtida
                </option>
            </select>

            {{-- Filtro por cantidad --}}
            <select name="cantidad" onchange="this.form.submit()"
                class="p-2 w-full md:w-1/4 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">Default</option>
                <option value="10" {{ request('cantidad') === '10' ? 'selected' : '' }}>
                    10
                </option>
                <option value="15" {{ request('cantidad') === '15' ? 'selected' : '' }}>
                    15
                </option>
                <option value="20" {{ request('cantidad') === '20' ? 'selected' : '' }}>
                    20
                </option>
            </select>
        </form>
        <div x-data @keydown.window.prevent.f9="$refs.btnRemision.click()">
            {{-- Botón --}}
            <a href="{{ route('documentos.create', [
                'sucursal' => $sucursal,
                'tipo' => 3,
            ]) }}"
                x-ref="btnRemision"
                class="inline-flex items-center w-full justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-md font-medium shadow transition whitespace-nowrap">
                Registrar [F9]
            </a>

        </div>

    </div>
    @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">{{ session('success') }}
        </p>
    @endif


    <div class="shadow-md overflow-x-auto rounded-lg">
        @if ($documentos->count() > 0)
            <div class="hidden md:block">
                <table class="w-full border bg-white shadow rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Fecha</th>
                            <th class="p-2">Serie</th>
                            <th class="p-2">Folio</th>
                            <th class="p-2">Razón social</th>
                            <th class="p-2">Total</th>
                            <th class="p-2">Estado</th>
                            <th class="p-2">Timbrada</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documentos as $documento)
                            <tr class="border-t">
                                <td class="p-2 text-center">
                                    {{ $documento->fecha }}
                                </td>
                                <td class="p-2">
                                    {{ $documento->serie }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $documento->folio }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $documento->cliente->nombre }}
                                </td>
                                <td class="p-2 text-right">
                                    {{ number_format($documento->total, 2) }}
                                </td>
                                <td class="p-2 text-center">
                                    @if ($documento->estatus == 1)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                            Activo
                                        </span>
                                    @elseif ($documento->estatus == 4)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                            Surtida
                                        </span>
                                    @elseif($documento->estatus == 2)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                            Convertida
                                        </span>
                                    @elseif($documento->estatus == 5)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                            Devolución
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                            Cancelada
                                        </span>
                                    @endif
                                </td>
                                <td class="p-2 text-right">
                                    @if ($documento->codigo_utilizado == 1)
                                        <span
                                            class="px-2 py-1 text-xs ml-2 font-semibold text-blue-800 bg-blue-100 rounded-full">
                                            Si
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs ml-2 font-semibold text-red-800 bg-red-100 rounded-full">
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex justify-center items-center gap-4">
                                        {{-- Ver --}}
                                        <a href="{{ route('documentos.show', ['sucursal' => $sucursal, 'documento' => $documento]) }}"
                                            class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                            <x-heroicon-o-eye class="w-4 h-4" />
                                            <span class="hidden sm:inline">Ver</span>
                                        </a>
                                        @if ($documento->estatus == 1)
                                            <span class="hidden sm:inline text-gray-300">•</span>
                                            {{-- Editar --}}
                                            <a href="{{ route('documentos.edit', ['sucursal' => $sucursal, 'documento' => $documento]) }}"
                                                class="inline-flex items-center gap-1 text-gray-600 hover:text-indigo-600 transition">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                <span class="hidden sm:inline">Editar</span>
                                            </a>
                                            <span class="hidden sm:inline text-gray-300">•</span>

                                            {{-- Eliminar --}}
                                            <form
                                                action="{{ route('documentos.destroy', ['sucursal' => $sucursal, 'documento' => $documento]) }}"
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
                @foreach ($documentos as $documento)
                    <div class="border rounded-lg shadow bg-white p-4">
                        <div class=" flex justify-end text-sm text-gray-500">
                            @if ($documento->estatus == 1)
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                    Activo
                                </span>
                            @elseif ($documento->estatus == 4)
                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                    Surtida
                                </span>
                            @elseif($documento->estatus == 2)
                                <span
                                    class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                    Convertida
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                    Cancelada
                                </span>
                            @endif
                        </div>
                        <div class="flex justify-between mt-2">
                            <div class=" text-sm text-gray-500">
                                <span>Fecha</span>
                                <span class="font-medium text-gray-800">
                                    {{ $documento->fecha }}
                                </span>
                            </div>
                            <div class="">
                                <p class="text-sm">Serie:
                                    <span class="font-semibold">
                                        {{ $documento->serie }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm">Folio:
                                    <span class="font-semibold">
                                        {{ $documento->folio }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 mb-3 text-sm">
                            <div>
                                <p class="text-gray-500">Cliente
                                    <span class="font-semibold  text-gray-800">
                                        {{ $documento->cliente->nombre }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-3 border-t pt-2 flex justify-between">
                            <span class="text-gray-500 text-sm">Importe</span>
                            <span class="font-semibold text-lg">
                                ${{ number_format($documento->total, 2) }}
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center justify-end mt-4 gap-4">
                            {{-- Ver --}}
                            <a href="{{ route('documentos.show', ['sucursal' => $sucursal, 'documento' => $documento]) }}"
                                class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                <x-heroicon-o-eye class="w-4 h-4" />
                                <span class="hidden sm:inline">Ver</span>
                            </a>
                            @if ($documento->estatus == 1)
                                <span class="hidden sm:inline text-gray-300">•</span>
                                {{-- Editar --}}
                                <a href="{{ route('documentos.edit', ['sucursal' => $sucursal, 'documento' => $documento]) }}"
                                    class="inline-flex items-center gap-1 text-gray-600 hover:text-indigo-600 transition">
                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    <span class="hidden sm:inline">Editar</span>
                                </a>
                                <span class="hidden sm:inline text-gray-300">•</span>

                                {{-- Eliminar --}}
                                <form
                                    action="{{ route('documentos.destroy', ['sucursal' => $sucursal, 'documento' => $documento]) }}"
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
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white py-4 mt-3">
                <p class="text-sm text-gray-600 ml-6 text-center"> No hay remisiones</p>
            </div>
        @endif

        @if ($documentos->count() > 0)
            <div class="bg-white py-4 my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <p class="text-sm text-gray-600 ml-6">
                    Mostrando
                    <span class="font-medium">{{ $documentos->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $documentos->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $documentos->total() }}</span>
                    registros
                </p>
                {{ $documentos->links() }}
            </div>
        @endif


    </div>

</x-app-layout>
