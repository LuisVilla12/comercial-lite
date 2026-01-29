@section('title', content: 'Existencias')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Inventario
        </h2>
    </x-slot>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">

        <form method="GET" action="{{ route('existencias.index') }}" class="flex flex-col md:flex-row gap-4 w-full">

            {{-- Buscador --}}
            <div class="relative w-full md:w-1/3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar producto..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </div>

            {{-- Filtro por almacén --}}
            <div class="w-full md:w-1/4">
                <select name="almacen_id" onchange="this.form.submit()"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                    <option value="">Todos los almacenes</option>

                    @foreach ($almacenes as $almacen)
                        <option value="{{ $almacen->id }}"
                            {{ request('almacen_id') == $almacen->id ? 'selected' : '' }}>
                            {{ $almacen->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Botón opcional --}}
            {{-- <button type="submit"
            class="hidden md:inline-flex px-4 py-2 bg-indigo-600 text-white rounded-md">
            Buscar
        </button> --}}
        </form>

    </div>

    @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">{{ session('success') }}
        </p>
    @endif

    <div class="shadow-md overflow-x-auto rounded-lg">
        @if ($existencias->count() > 0)
            <div class="hidden md:block">
                <table class="w-full border bg-white shadow rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Codigo</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Almacen</th>
                            <th class="p-2">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($existencias as $existencia)
                            <tr class="border-t">
                                <td class="p-2 text-center">
                                    {{ $existencia->producto->codigo_producto }}
                                </td>
                                <td class="p-2">
                                    {{ $existencia->producto->nombre_producto }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $existencia->almacen->nombre }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $existencia->cantidad }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- CARDS: visible en tablet y móvil -->
            <div class="md:hidden space-y-4">
                @foreach ($existencias as $existencia)
                    <div class="border rounded-lg shadow bg-white p-4">
                        <div class="mt-2">
                            <div class="mb-2 text-sm text-gray-500">
                                <span>Codigo:</span>
                                <span class="font-medium text-gray-800">
                                    {{ $existencia->producto->codigo_producto }}
                                </span>
                            </div>
                            <div class="">
                                <p class="mb-2 text-sm">Nombre:
                                    <span class="font-semibold">
                                        {{ $existencia->producto->nombre_producto }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="mb-2 text-sm">Almacen:
                                    <span class="font-semibold">
                                        {{ $existencia->almacen->nombre }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="mb-2 text-sm">Almacen:
                                    <span class="font-semibold">
                                       {{ $existencia->cantidad }}
                                    </span>
                                </p>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white py-4 mt-3">
                <p class="text-sm text-gray-600 ml-6 text-center"> No hay existencias</p>
            </div>
        @endif

        <div class="bg-white py-4 my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <p class="text-sm text-gray-600 ml-6">
                Mostrando
                <span class="font-medium">{{ $existencias->firstItem() }}</span>
                a
                <span class="font-medium">{{ $existencias->lastItem() }}</span>
                de
                <span class="font-medium">{{ $existencias->total() }}</span>
                registros
            </p>

            {{ $existencias->links() }}
        </div>

    </div>

</x-app-layout>
