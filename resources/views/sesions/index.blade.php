@section('title', content: 'Sesiones Activas')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Sesiones activas
        </h2>
    </x-slot>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">

        {{-- Buscador --}}
        <form method="GET" action="{{ route('sesiones.index') }}" class="w-full md:w-1/3">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar sesiones..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </div>

            @if (request('search'))
                <a href="{{ route('sesiones.index') }}"
                    class="inline-block mt-1 text-sm text-gray-500 hover:text-indigo-600">
                    Limpiar búsqueda
                </a>
            @endif
        </form>
        <div>
            <form
                                                action="{{ route('sesiones.destroyAll') }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="inline-flex py-2 px-4 items-center gap-1 text-white  bg-red-500 transition"
                                                    onclick="return confirm('¿Estás seguro de que deseas expulsar a todos?')">
                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                    <span class="hidden sm:inline">Expulsar a todos</span>
        </button>
                                            </form>
        </div>

    </div>
    @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">{{ session('success') }}
        </p>
    @endif


    <div class="shadow-md overflow-x-auto rounded-lg ">
        @if ($sesiones->count() > 0)
            <div class="hidden md:block">
                <table class="w-full border bg-white shadow rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">N°</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">Ultima actividad</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sesiones as $sesion)
                            <tr class="border-t">
                                <td class="p-2 text-center">
                                    {{ 1 }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $sesion->name??'-Por confirmar-' }}
                                </td>
                                <td class="p-2">
                                    {{ $sesion->email??'-Por confirmar-' }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ \Carbon\Carbon::createFromTimestamp($sesion->last_activity)->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex justify-center items-center gap-4">
                                        {{-- Eliminar --}}
                                       <form
                                                action="{{ route('sesiones.destroy',$sesion->id ) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 text-gray-500 hover:text-red-600 transition"
                                                    onclick="return confirm('¿Estás seguro de que cerrar la sesion de este usuario?')">
                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                    <span class="hidden sm:inline">Cerrar sesiòn</span>
                                                </button>
                                            </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- CARDS: visible en tablet y móvil -->
            <div class="md:hidden space-y-4">
                @foreach ($sesiones as $sesion)
                    <div class="border rounded-lg shadow bg-white p-4">
                        <div class="mt-2">
                            <div class="mb-2 text-sm text-gray-500">
                                <span>Nombre del usuario:</span>
                                <span class="font-medium text-gray-800">
                                    {{ $sesion->name }}
                                </span>
                            </div>
                            <div class="">
                                <p class="mb-2 text-sm">Email:
                                    <span class="font-semibold">
                                        {{ $sesion->email }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="mb-2 text-sm">Tiempo conectado:
                                    {{ \Carbon\Carbon::createFromTimestamp($sesion->last_activity)->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center justify-end mt-4 gap-4">

                            {{-- Eliminar --}}

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white py-4 mt-3">
                <p class="text-sm text-gray-600 ml-6 text-center"> No hay sesiones activas</p>
            </div>
        @endif
        @if ($sesiones->count() > 0)
            <div class="bg-white py-4 my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <p class="text-sm text-gray-600 ml-6">
                    Mostrando
                    <span class="font-medium">{{ $sesiones->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $sesiones->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $sesiones->total() }}</span>
                    registros
                </p>

                {{ $sesiones->links() }}
            </div>
        @endif

    </div>
    <div class="mt-6  gap-4">
        <div class="flex  items-center gap-3 mt-4">
            <a  href="{{ route('empresas.list', ['user' => auth()->user()]) }}" class="px-4 py-2 bg-gray-500 text-white rounded">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" /> Regresar
            </a>
        </div>
    </div>

</x-app-layout>
