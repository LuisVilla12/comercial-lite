@section('title', 'Listado de gastos')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Listado de gastos
        </h2>
    </x-slot>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">

        <form method="GET" action="{{ route('gastos.index') }}" class="flex flex-col md:flex-row gap-4 w-full">
            {{-- Filtro por sucursal --}}
            {{-- <div class="w-full md:w-1/4">
                <select name="sucursal_id" onchange="this.form.submit()"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos las sucursales</option>
                    @foreach ($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}"
                            {{ request('sucursal_id') == $sucursal->id ? 'selected' : '' }}>
                            {{ $sucursal->nombre }}
                        </option>
                    @endforeach
                </select>
            </div> --}}
            {{-- Filtro por usuario --}}
            <div class="w-full md:w-1/4">
                <select name="user_id" onchange="this.form.submit()"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos los usuarios</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- 📅 Fecha inicio --}}
            <div>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                    onchange="this.form.submit()"
                    class="w-full p-3 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- 📅 Fecha fin --}}
            <div>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" onchange="this.form.submit()"
                    class="w-full p-3 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
            </div>
            {{-- Filtro por estatus --}}
            <select name="estatus" onchange="this.form.submit()"
                class="p-2 w-full md:w-1/4 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">Todas</option>
                <option value="1" {{ request('estatus') === '1' ? 'selected' : '' }}>
                    Activa
                </option>
                <option value="2" {{ request('estatus') === '2' ? 'selected' : '' }}>
                    Transformada
                </option>
                <option value="3" {{ request('estatus') === '3' ? 'selected' : '' }}>
                    Cancelada
                </option>
                <option value="4" {{ request('estatus') === '4' ? 'selected' : '' }}>
                    Surtida
                </option>
            </select>



        </form>
        <div x-data @keydown.window.prevent.f9="$refs.btnRegistrar.click()">
            <a x-ref="btnRegistrar" href="{{ route('gastos.create') }}"
                class="inline-flex items-center justify-center w-full gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-md font-medium shadow transition whitespace-nowrap">
                Registrar Movimiento [F9]
            </a>
        </div>
    </div>

    <div class="shadow-md overflow-x-auto rounded-lg mt-6">
        @if ($gastos->count() > 0)
            <div class="hidden md:block">
                <table class="w-full border bg-white shadow rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Folio</th>
                            <th class="p-2">Fecha</th>
                            <th class="p-2">Descripcion</th>
                            <th class="p-2">Caja</th>
                            <th class="p-2">Usuario</th>
                            <th class="p-2">Tipo</th>
                            <th class="p-2">Total</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gastos as $gasto)
                            <tr class="border-t">
                                <td class="p-2 text-center">
                                    {{ $gasto->folio }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $gasto->fecha }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $gasto->descripcion }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $gasto->caja_id }}
                                </td>
                                <td class="p-2 text-center">
                                    @foreach ($users as $user)
                                        @if ($user->id == $gasto->user_id)
                                            {{ $user->name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td class="p-2">
                                    @if ($gasto->estatus == 1)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                            Gasto
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                            Retiro
                                        </span>
                                    @endif
                                </td>

                                <td class="p-2">
                                    {{ $gasto->total }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex justify-center items-center gap-4">
                                        {{-- Ver --}}
                                        <a href="{{ route('gastos.show', $gasto) }}"
                                            class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                            <x-heroicon-o-eye class="w-4 h-4" />
                                            <span class="hidden sm:inline">Ver</span>
                                        </a>

                                        <span class="hidden sm:inline text-gray-300">•</span>
                                        {{-- Eliminar --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- CARDS: visible en tablet y móvil -->
            <div class="md:hidden space-y-4">
                @foreach ($gastos as $gasto)
                    <div class="border rounded-lg shadow bg-white p-4">
                        <div class="mt-2">
                            <div class="mb-2 text-sm text-gray-500">
                                <span>Folio:</span>
                                <span class="font-medium text-gray-800">
                                    {{ $gasto->folio }}
                                </span>
                            </div>
                            <div class="">
                                <p class="mb-2 text-sm">Fecha:
                                    <span class="font-semibold">
                                        {{ $gasto->fecha }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="mb-2 text-sm">Usuario:
                                    <span class="font-semibold">
                                        {{ $gasto->user_id }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center justify-end mt-4 gap-4">
                            {{-- Ver --}}
                            <a href=""
                                class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                <x-heroicon-o-eye class="w-4 h-4" />
                                <span class="hidden sm:inline">Ver</span>
                            </a>
                            <span class="hidden sm:inline text-gray-300">•</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white py-4 mt-3">
                <p class="text-sm text-gray-600 ml-6 text-center"> No hay gastos registradas</p>
            </div>
        @endif
        {{-- @if ($almacenes->count() > 0)
            <div class="bg-white py-4 my-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

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
        @endif --}}

    </div>
</x-app-layout>
