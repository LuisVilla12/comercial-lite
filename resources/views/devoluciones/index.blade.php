@section('title', content: 'Devoluciones')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Catálogo de devoluciones {{ $sucursal->nombre }}
        </h2>
    </x-slot>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">

        {{-- Buscador --}}
        <form method="GET" action="{{ route('devoluciones.index',$sucursal) }}" class="w-full md:w-1/3">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar devolucion..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </div>

            @if (request('search'))
                <a href="{{ route('compras.index') }}"
                    class="inline-block mt-1 text-sm text-gray-500 hover:text-indigo-600">
                    Limpiar búsqueda
                </a>
            @endif
        </form>

        {{-- Botón --}}
        {{-- <a href="{{ route('compras.create') }}"
            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-md font-medium shadow transition whitespace-nowrap">
            Registrar Devolucion
        </a> --}}

    </div>
    @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">{{ session('success') }}
        </p>
    @endif


    <div class="shadow-md overflow-x-auto rounded-lg">
        @if ($devoluciones->count() > 0)
            <div class="hidden md:block">
                <table class="w-full border bg-white shadow rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Fecha</th>
                            <th class="p-2">Serie</th>
                            <th class="p-2">Folio</th>
                            <th class="p-2">Razón social</th>
                            <th class="p-2">Total</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($devoluciones as $documento)
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
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex flex-wrap items-center gap-4">
                                        {{-- Ver --}}
                                        <a href="{{ route('devoluciones.show', ['sucursal' => $sucursal, 'documento' => $documento]) }}"
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
                @foreach ($devoluciones as $documento)
                    <div class="border rounded-lg shadow bg-white p-4">
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
                            <a href="{{ route('devoluciones.show', ['sucursal' => $sucursal, 'documento' => $documento]) }}"
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
                <p class="text-sm text-gray-600 ml-6 text-center"> No hay remisiones</p>
            </div>
        @endif
        @if ($devoluciones->count() > 0)
            <div class="bg-white py-4 my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <p class="text-sm text-gray-600 ml-6">
                    Mostrando
                    <span class="font-medium">{{ $devoluciones->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $devoluciones->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $devoluciones->total() }}</span>
                    registros
                </p>

                {{ $devoluciones->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
