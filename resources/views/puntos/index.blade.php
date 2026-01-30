@section('title', content: 'Puntos')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Catálogo de Puntos
        </h2>
    </x-slot>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">

        {{-- Buscador --}}
        <form method="GET" action="{{ route('puntos.index') }}" class="w-full md:w-1/3">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar cliente..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </div>

            @if (request('search'))
                <a href="{{ route('puntos.index') }}"
                    class="inline-block mt-1 text-sm text-gray-500 hover:text-indigo-600">
                    Limpiar búsqueda
                </a>
            @endif
        </form>

    </div>
    @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">{{ session('success') }}
        </p>
    @endif

    <div class="shadow-md overflow-x-auto rounded-lg ">
        @if ($puntos->count() > 0)
            <div class="hidden md:block">
                <table class="w-full border bg-white shadow rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Codigo</th>
                            <th class="p-2">Cliente</th>
                            <th class="p-2">Total de puntos</th>
                            <th class="p-2">Ultima compra</th>
                            {{-- <th class="p-2">Ver compras</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($puntos as $punto)
                            <tr class="border-t">
                                 <td class="p-2 text-center">
                                    {{ $punto->cliente->codigo }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $punto->cliente->nombre }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $punto->total_puntos }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $punto->updated_at }}
                                </td>
                                {{-- <td class="p-2 text-center">
                                    {{ $punto->movimientos }}
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- CARDS: visible en tablet y móvil -->
            <div class="md:hidden space-y-4">
                @foreach ($puntos as $punto)
                    <div class="border rounded-lg shadow bg-white p-4">
                        <div class="mt-2">
                            <div class="mb-2 text-sm text-gray-500">
                                <span>Cliente:</span>
                                <span class="font-medium text-gray-800">
                                    {{ $punto->cliente->codigo }}
                                </span>
                            </div>
                            <div class="mb-2 text-sm text-gray-500">
                                <span>Cliente:</span>
                                <span class="font-medium text-gray-800">
                                    {{ $punto->cliente->nombre }}
                                </span>
                            </div>
                            <div class="">
                                <p class="mb-2 text-sm">Total de puntos:
                                    <span class="font-semibold text-gray-800">
                                        {{ $punto->total_puntos }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="mb-2 text-sm">Ultima compra:
                                    <span class="font-semibold text-gray-800">{{ $punto->updated_at }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white py-4 mt-3">
                <p class="text-sm text-gray-600 ml-6 text-center"> No hay puntos</p>
            </div>
        @endif
        {{-- @if ($puntos->count() > 0)
            <div class="bg-white py-4 my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <p class="text-sm text-gray-600 ml-6">
                    Mostrando
                    <span class="font-medium">{{ $puntos->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $puntos->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $puntos->total() }}</span>
                    registros
                </p>

                {{ $puntos->links() }}
            </div>
        @endif --}}

    </div>

</x-app-layout>
