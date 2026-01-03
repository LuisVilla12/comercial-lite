@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="">
     <h1 class="text-2xl font-semibold text-gray-800">
        Clientes
    </h1>



</div>
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">

    {{-- Buscador --}}
    <form method="GET" action="{{ route('clientes.index') }}" class="w-full md:w-1/3">
        <div class="relative">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Buscar cliente..."
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
            <a href="{{ route('clientes.index') }}"
               class="inline-block mt-1 text-sm text-gray-500 hover:text-indigo-600">
                Limpiar búsqueda
            </a>
        @endif
    </form>

    {{-- Botón --}}
    <a href="{{ route('clientes.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-md font-medium shadow transition whitespace-nowrap">
        Registrar cliente
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
                    RFC
                </th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Acciones
                </th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-100">
            @forelse ($clientes as $cliente)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-md text-gray-700">
                        {{ $cliente->codigo }}
                    </td>
                    <td class="px-6 py-4 text-md font-medium text-gray-900">
                        {{ $cliente->nombre }}
                    </td>
                    <td class="px-6 py-4 text-md text-gray-700">
                        {{ $cliente->rfc }}
                    </td>
                    <td class="px-6 py-4 text-md text-gray-700">
                        <a href="{{ route('clientes.edit', $cliente) }}"
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Editar
                        </a>
                        <a href="{{ route('clientes.show',$cliente) }}"
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Ver
                        </a>
                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:text-red-800 font-medium"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-6 text-center text-md text-gray-500">
                        No hay clientes registrados
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
<div class="mt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

    <p class="text-sm text-gray-600">
        Mostrando
        <span class="font-medium">{{ $clientes->firstItem() }}</span>
        a
        <span class="font-medium">{{ $clientes->lastItem() }}</span>
        de
        <span class="font-medium">{{ $clientes->total() }}</span>
        registros
    </p>

    {{ $clientes->links() }}

</div>


</div>

@endsection
