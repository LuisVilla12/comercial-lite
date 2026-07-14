@section('title', content: 'Clientes con saldo pendiente' )

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Catalogo de Clientes con Saldo Pendiente
        </h2>
    </x-slot>
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
</div>
@if (session('success'))
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4"
                >{{ session('success') }}</p>
    @endif

<div class="shadow-md overflow-x-auto rounded-lg">
@if ($clientes->count() > 0)
            <div class="hidden md:block">
                <table class="w-full border bg-white shadow rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Codigo</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">RFC</th>
                            <th class="p-2">Credito</th>
                            <th class="p-2">Pendiente</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr class="border-t">
                                <td class="p-2 text-center">
                                    {{ $cliente->codigo }}
                                </td>
                                <td class="p-2">
                                    {{ $cliente->nombre }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $cliente->rfc }}
                                </td>
                                <td class="p-2 text-center">
                                    ${{ number_format($cliente->credito, 2) }}
                                </td>
                                <td class="p-2 text-center">
                                    ${{ number_format($cliente->saldo, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex justify-center items-center gap-4">
                                        {{-- Ver --}}
                                        <a href="{{ route('clientes.show', [$cliente, $cliente->tipo]) }}"
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
                @foreach ($clientes as $cliente)
                    <div class="border rounded-lg shadow bg-white p-4">
                        <div class="mt-2">
                            <div class="mb-2 text-sm text-gray-500">
                                <span>Codigo:</span>
                                <span class="font-medium text-gray-800">
                                    {{ $cliente->codigo }}
                                </span>
                            </div>
                            <div class="">
                                <p class="mb-2 text-sm">Nombre:
                                    <span class="font-semibold">
                                        {{ $cliente->nombre }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="mb-2 text-sm">RFC:
                                    <span class="font-semibold">
                                        {{ $cliente->rfc }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4 gap-4">
                            {{-- Ver --}}
                            <a href="{{ route('clientes.show', [$cliente, $cliente->tipo]) }}"
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
                <p class="text-sm text-gray-600 ml-6 text-center"> No hay clientes con saldo pendiente</p>
            </div>
        @endif
@if($clientes->count() > 0)

<div class="bg-white py-4 my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

    <p class="text-sm text-gray-600 ml-6">
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
@endif
</div>
</x-app-layout>
