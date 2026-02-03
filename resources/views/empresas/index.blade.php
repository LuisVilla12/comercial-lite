@section('title', content: 'Empresas')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Catálogo de empresas
        </h2>
    </x-slot>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4  ">
        {{-- Botón --}}
        <a href="{{ route('empresas.create') }}"
            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-md font-medium shadow transition whitespace-nowrap">
            Registrar Empresa
        </a>

    </div>
    @if (session('success'))
    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
        class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">{{ session('success') }}
    </p>
    @endif

    <div class="bg-white shadow-md overflow-x-auto rounded-lg border border-gray-200  w-4/5 mx-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Codigo
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>


            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($empresas as $empresa)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-md text-gray-700">
                        {{ $empresa->codigo }}
                    </td>
                    <td class="px-6 py-4 text-md font-medium text-gray-900">
                        {{ $empresa->nombre }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        <div class="flex flex-wrap items-center gap-4">
                            {{-- Ver --}}
                            <a href="{{ route('empresas.show',$empresa) }}"
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

                            {{-- <span class="hidden sm:inline text-gray-300">•</span> --}}
                            {{-- Eliminar --}}
                            {{-- <form action="{{ route('almacenes.destroy', $almacen) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="inline-flex items-center gap-1 text-gray-500 hover:text-red-600 transition"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                    <span class="hidden sm:inline">Eliminar</span>
                                </button>
                            </form> --}}

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-6 text-center text-md text-gray-500">
                        No hay empresas registradas
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
        {{-- @if($empresas->count() > 0)
        <div class="my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <p class="text-sm text-gray-600 ml-6">
                Mostrando
                <span class="font-medium">{{ $empresas->firstItem() }}</span>
                a
                <span class="font-medium">{{ $empresas->lastItem() }}</span>
                de
                <span class="font-medium">{{ $empresas->total() }}</span>
                registros
            </p>

            {{ $empresas->links() }}
        </div> --}}
        {{-- @endif --}}

    </div>

</x-app-layout>
