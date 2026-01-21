@section('title', content: 'Devoluciones')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Catálogo de devoluciones
        </h2>
    </x-slot>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">

        {{-- Buscador --}}
        <form method="GET" action="{{ route('compras.index') }}" class="w-full md:w-1/3">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar compra..."
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
        <a href="{{ route('compras.create') }}"
            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-md font-medium shadow transition whitespace-nowrap">
            Registrar Devolucion
        </a>

    </div>
    @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">{{ session('success') }}
        </p>
    @endif


    <div class="bg-white shadow-md overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Serie
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Folio
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Razon social
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Total
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($documentos as $documento)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-md text-gray-700">
                            {{ $documento->fecha }}
                        </td>
                        <td class="px-6 py-4 text-md font-medium text-gray-900">
                            {{ $documento->serie }}
                        </td>
                        <td class="px-6 py-4 text-md font-medium text-gray-900">
                            {{ $documento->folio }}
                        </td>
                        <td class="px-6 py-4 text-md font-medium text-gray-900">
                            {{ $documento->cliente->nombre }}
                        </td>
                        <td class="px-6 py-4 text-md font-medium text-gray-900">
                            {{ number_format($documento->total,2) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="flex flex-wrap items-center gap-4">
                                {{-- Ver --}}
                                <a href="{{ route('documentos.show', $documento) }}"
                                    class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                    <span class="hidden sm:inline">Ver</span>
                                </a>
                                    <span class="hidden sm:inline text-gray-300">•</span>
                                {{-- Editar --}}
                                <a href=""
                                    class="inline-flex items-center gap-1 text-gray-600 hover:text-indigo-600 transition">
                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    <span class="hidden sm:inline">Editar</span>
                                </a>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-md text-gray-500">
                            No hay compras registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
        @if($documentos->count() > 0)
        <div class="my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

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
