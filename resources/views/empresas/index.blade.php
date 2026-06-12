@section('title', content: 'Empresas')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Catálogo de empresas
        </h2>
    </x-slot>
    <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-4 my-4  ">
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
    @if ($empresas->count() === 0)
        <p class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md mb-4">
            No se han encontrado empresas. Por favor, registre una nueva empresa para comenzar a gestionar sus
            documentos.
        </p>
    @else
        <div class="bg-white shadow-md overflow-x-auto rounded-lg border border-gray-200  w-4/5 mx-auto">
            <div class="hidden md:block">
                <table class="w-full border bg-white shadow rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Codigo</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empresas as $empresa)
                            <tr class="border-t">
                                <td class="p-2 text-center">
                                    {{ $empresa->codigo }}
                                </td>
                                <td class="p-2">
                                    {{ $empresa->nombre }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex justify-center items-center gap-4">
                                        {{-- Ver --}}
                                        <a href="{{ route('empresas.show', ['empresa' => $empresa]) }}"
                                            class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                            <x-heroicon-o-eye class="w-4 h-4" />
                                            <span class="hidden sm:inline">Ver</span>
                                        </a>
                                        <span class="hidden sm:inline text-gray-300">•</span>
                                        {{-- Editar --}}
                                        <a href="{{ route('empresas.edit', ['empresa' => $empresa]) }}"
                                            class="inline-flex items-center gap-1 text-gray-600 hover:text-indigo-600 transition">
                                            <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            <span class="hidden sm:inline">Editar</span>
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
                @foreach ($empresas as $empresa)
                    <div class="border rounded-lg shadow bg-white p-4">
                        <div class="flex justify-between smt-2">

                            <div class="">
                                <p class="mb-2 text-sm">Nombre:
                                    <span class="font-semibold text-gray-800">
                                        {{ $empresa->nombre }}
                                    </span>
                                </p>
                            </div>
                            <div class="mb-2 text-sm text-gray-500">
                                <span>Codigo:</span>
                                <span class="font-medium text-gray-800">
                                    {{ $empresa->codigo }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center justify-end mt-4 gap-4">
                            {{-- Ver --}}
                            <a href="{{ route('empresas.show', ['empresa' => $empresa]) }}"
                                class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                <x-heroicon-o-eye class="w-4 h-4" />
                                <span class="hidden sm:inline">Ver</span>
                            </a>
                            <span class="hidden sm:inline text-gray-300">•</span>
                            {{-- Editar --}}
                            <a href="{{ route('empresas.edit', ['empresa' => $empresa]) }}"
                                class="inline-flex items-center gap-1 text-gray-600 hover:text-indigo-600 transition">
                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                <span class="hidden sm:inline">Editar</span>
                            </a>
                            <span class="hidden sm:inline text-gray-300">•</span>


                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    <div class="mt-6  gap-4">
        <div class="flex  items-center gap-3 mt-4">
            <a href="{{ route('empresas.select') }}" class="px-4 py-2 bg-gray-500 text-white rounded">
                Volver
            </a>
        </div>
    </div>
</x-app-layout>
