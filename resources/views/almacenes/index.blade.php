@section('title', content: 'Almacenes' )

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Catálogo de Almacenes
        </h2>
    </x-slot>
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">

    {{-- Buscador --}}
    <form method="GET" action="{{ route('almacenes.index') }}" class="w-full md:w-1/3">
        <div class="relative">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Buscar almacen..."
                class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
            >

            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"
                 fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
        </div>

        @if(request('search'))
            <a href="{{ route('almacenes.index') }}"
               class="inline-block mt-1 text-sm text-gray-500 hover:text-indigo-600">
                Limpiar búsqueda
            </a>
        @endif
    </form>

    {{-- Botón --}}
    <a href="{{ route('almacenes.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-md font-medium shadow transition whitespace-nowrap">
        Registrar Almacen
    </a>

</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
@if (session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4">
        <p class="font-semibold">Éxito</p>
        <p>{{ session('success') }}</p>
    </div>
@endif

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Codigo
                </th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Nombre
                </th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Tipo
                </th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Acciones
                </th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-100">
            @forelse ($almacenes as $almacen)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-md text-gray-700">
                        {{ $almacen->codigo }}
                    </td>
                    <td class="px-6 py-4 text-md font-medium text-gray-900">
                        {{ $almacen->nombre }}
                    </td>
                    <td class="px-6 py-4 text-md font-medium text-gray-900">
                        @if($almacen->tipo == 1)
                            MATRIZ
                        @elseif($almacen->tipo==2)
                            SUCURSAL
                        @else
                            No asignado
                        @endif
                    </td>
                    <td class="px-6 py-4 text-md text-gray-700">
                        <div class="flex items-center gap-4 text-sm font-medium">
        {{-- Ver --}}
        <a href="{{ route('almacenes.show', $almacen) }}"
           class="text-gray-600 hover:text-blue-600 transition">
            Ver
        </a>
  {{-- Separador --}}
        <span class="text-gray-300">|</span>
        {{-- Editar --}}
        <a href="{{ route('almacenes.edit', $almacen) }}"
           class="text-gray-600 hover:text-indigo-600 transition">
            Editar
        </a>

        {{-- Separador --}}
        <span class="text-gray-300">|</span>

        {{-- Eliminar --}}
        <form action="{{ route('almacenes.destroy', $almacen) }}"
              method="POST"
              class="inline">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="text-gray-500 hover:text-red-600 transition"
                    onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                Eliminar
            </button>
        </form>
    </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-6 text-center text-md text-gray-500">
                        No hay almacenes registrados
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
<div class="my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

    <p class="text-sm text-gray-600 ml-6">
        Mostrando
        <span class="font-medium">{{ $almacenes->firstItem() }}</span>
        a
        <span class="font-medium">{{ $almacenes->lastItem() }}</span>
        de
        <span class="font-medium">{{ $almacenes->total() }}</span>
        registros
    </p>

    {{ $almacenes->links() }}
</div>

</div>

</x-app-layout>
