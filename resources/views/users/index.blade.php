@section('title', content: 'Usuarios' )

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Catalogo de Usuarios
        </h2>
    </x-slot>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">
    {{-- Buscador --}}
    <form method="GET" action="{{ route('usuarios.index') }}" class="w-full md:w-1/3">
        <div class="relative">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Buscar usuario..."
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
    <a href="{{ route('register') }}"
       class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-md font-medium shadow transition whitespace-nowrap">
        Registrar usuario
    </a>

</div>
  @if (session('success'))
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md"
                >{{ session('success') }}</p>
    @endif

<div class="bg-white shadow-md overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Nombre de usuario
                </th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Codigo
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
            @forelse ($usuarios as $usuario)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-md text-gray-700">
                        {{ $usuario->name }}
                    </td>
                    <td class="px-6 py-4 text-md font-medium text-gray-900">
                        {{ $usuario->email }}
                    </td>
                    <td class="px-6 py-4 text-md font-medium text-gray-900">
                        @if($usuario->tipo == 1)
                            Administrador
                        @elseif($usuario->tipo==2)
                            Operador
                        @else
                            No asignado
                        @endif
                    </td>
                    <td class="px-6 py-4 text-md text-gray-700">
                        <div class="flex items-center gap-4 text-sm font-medium">
        {{-- Ver --}}
        <a href=""
           class="text-gray-600 hover:text-blue-600 transition">
            Ver
        </a>
  {{-- Separador --}}
        <span class="text-gray-300">|</span>
        {{-- Editar --}}
        <a href=""
           class="text-gray-600 hover:text-indigo-600 transition">
            Editar
        </a>

        {{-- Separador --}}
        <span class="text-gray-300">|</span>
    </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-6 text-center text-md text-gray-500">
                        No hay usuarios registrados
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
<div class="my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

    <p class="text-sm text-gray-600 ml-6">
        Mostrando
        <span class="font-medium">{{ $usuarios->firstItem() }}</span>
        a
        <span class="font-medium">{{ $usuarios->lastItem() }}</span>
        de
        <span class="font-medium">{{ $usuarios->total() }}</span>
        registros
    </p>

    {{ $usuarios->links() }}
</div>

</div>

</x-app-layout>
