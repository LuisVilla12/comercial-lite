@section('title', content: 'Traspasos')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Catálogo de Traspaso
        </h2>
    </x-slot>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">

        {{-- Buscador --}}
        <form method="GET" action="{{ route('traspasos.index') }}" class="w-full md:w-1/3">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar Traspasos..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </div>

            @if (request('search'))
                <a href="{{ route('traspasos.index') }}"
                    class="inline-block mt-1 text-sm text-gray-500 hover:text-indigo-600">
                    Limpiar búsqueda
                </a>
            @endif
        </form>

        {{-- Botón --}}
        <a href="{{ route('traspasos.create') }}"
            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-md font-medium shadow transition whitespace-nowrap">
            Registrar Traspaso
        </a>

    </div>
    @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">{{ session('success') }}
        </p>
    @endif
    @if (session('error'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4 mt-4">{{ session('error') }}
        </p>
    @endif

    <div class=" shadow-md overflow-x-auto rounded-lg">
        @if ($traspasos->count() > 0)
            <div class="hidden md:block">
                <table class="w-full border bg-white shadow rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Fecha</th>
                            <th class="p-2">Serie</th>
                            <th class="p-2">Folio</th>
                            <th class="p-2">Almacen salida</th>
                            <th class="p-2">Almacen entrada</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($traspasos as $traspaso)
                            <tr class="border-t">
                                <td class="p-2 text-center">
                                    {{ $traspaso->fecha }}
                                </td>
                                <td class="p-2">
                                    {{ $traspaso->serie }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $traspaso->folio }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $traspaso->almacenOrigen->nombre }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $traspaso->almacenDestino->nombre }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex justify-center items-center gap-4">
                                        {{-- Ver --}}
                                        <a href="{{ route('traspasos.show', ['traspaso' => $traspaso]) }}"
                                            class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                            <x-heroicon-o-eye class="w-4 h-4" />
                                            <span class="hidden sm:inline">Ver</span>
                                        </a>
                                        @if ($traspaso->estatus == 1)
                                            <span class="hidden sm:inline text-gray-300">•</span>
                                            {{-- Editar --}}
                                            <a href="{{ route('traspasos.edit', ['traspaso' => $traspaso]) }}"
                                                class="inline-flex items-center gap-1 text-gray-600 hover:text-indigo-600 transition">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                <span class="hidden sm:inline">Editar</span>
                                            </a>
                                            <span class="hidden sm:inline text-gray-300">•</span>

                                            {{-- Eliminar --}}
                                            <form
                                                action="{{ route('traspasos.destroy', ['traspaso' => $traspaso]) }}"
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
                @foreach ($traspasos as $trapaso)
                    <div class="border rounded-lg shadow bg-white p-4">
                        <div class="flex justify-between mt-2">
                            <div class=" text-sm text-gray-500">
                                <span>Fecha</span>
                                <span class="font-medium text-gray-800">
                                    {{ $trapaso->fecha }}
                                </span>
                            </div>
                            <div class="">
                                <p class="text-sm">Serie:
                                    <span class="font-semibold">
                                        {{ $trapaso->serie }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm">Folio:
                                    <span class="font-semibold">
                                        {{ $trapaso->folio }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 mb-3 text-sm">
                            <div>
                                <p class="text-gray-500">Almacen salida
                                    <span class="font-semibold  text-gray-800">
                                        {{ $traspaso->almacenOrigen->nombre }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 mb-3 text-sm">
                            <div>
                                <p class="text-gray-500">Almacen salida
                                    <span class="font-semibold  text-gray-800">
                                        {{ $traspaso->almacenDestino->nombre }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center justify-end mt-4 gap-4">
                            {{-- Ver --}}
                            <a href="{{ route('traspasos.show', ['traspaso' => $traspaso]) }}"
                                class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                <x-heroicon-o-eye class="w-4 h-4" />
                                <span class="hidden sm:inline">Ver</span>
                            </a>
                            @if ($traspaso->estatus == 1)
                                <span class="hidden sm:inline text-gray-300">•</span>
                                {{-- Editar --}}
                                <a href="{{ route('traspasos.edit', ['traspaso' => $traspaso]) }}"
                                    class="inline-flex items-center gap-1 text-gray-600 hover:text-indigo-600 transition">
                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    <span class="hidden sm:inline">Editar</span>
                                </a>
                                <span class="hidden sm:inline text-gray-300">•</span>

                                {{-- Eliminar --}}
                                <form
                                    action="{{ route('traspasos.destroy', ['traspaso' => $traspaso]) }}"
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
                <p class="text-sm text-gray-600 ml-6 text-center"> No hay trapasos</p>
            </div>
        @endif
        @if ($traspasos->count() > 0)
            <div class="bg-white py-4 my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <p class="text-sm text-gray-600 ml-6">
                    Mostrando
                    <span class="font-medium">{{ $traspasos->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $traspasos->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $traspasos->total() }}</span>
                    registros
                </p>

                {{ $traspasos->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
