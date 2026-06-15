@section('title', 'Reportes')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Kardex
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">
        <h2 class="mb-4 font-semibold text-lg text-gray-800 ">
            Kardex global de producto
        </h2>
        <form method="POST" action="{{route('kardex.global')}}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Producto:
                    </label>
                     <select name="producto_id" id="producto_id"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="" selected disabled >Seleccione un tipo</option>
                        @forelse ($productos as $producto)
                            <option value="{{$producto->id}}" >{{$producto->nombre_producto}}</option>
                        @empty

                        @endforelse
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de movimiento
                    </label>
                    <select name="movimiento_id" id="movimiento_id"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="" selected disabled >Seleccione un tipo</option>
                        <option value="1" >Compras</option>
                        <option value="2" >Traslados</option>
                        <option value="3" >Remisiones/Facturas</option>
                        <option value="4" >Ajustes de inventario</option>
                        <option value="5" >Todos los movimientos</option>
                    </select>
                </div>
                {{-- Fecha inicio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Desde
                    </label>
                    <input type="date" name="fecha_inicio"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                {{-- Fecha fin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Hasta
                    </label>
                    <input type="date" name="fecha_fin"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Generar
                </button>
            </div>
        </form>
    </div>


    <div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">
        <h2 class="mb-4 font-semibold text-lg text-gray-800 ">
            Kardex de producto por sucursal
        </h2>
        <form method="POST" action="{{route('kardex.sucursal')}}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Producto:
                    </label>
                     <select name="producto_id" id="producto_id"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="" selected disabled >Seleccione un tipo</option>
                        @forelse ($productos as $producto)
                            <option value="{{$producto->id}}" >{{$producto->nombre_producto}}</option>
                        @empty

                        @endforelse
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de movimiento
                    </label>
                    <select name="movimiento_id" id="movimiento_id"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="" selected disabled >Seleccione un tipo</option>
                        <option value="1" >Compras</option>
                        <option value="2" >Traslados</option>
                        <option value="3" >Remisiones/Facturas</option>
                        <option value="4" >Todos los movimientos</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Almacen:
                    </label>
                    <select name="almacen_id" id="almacen_id"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="" selected disabled >Seleccione un almacen</option>
                        @forelse ($almacenes as $almacen)
                            <option value="{{$almacen->id}}">{{$almacen->nombre}}</option>
                        @empty
                                                    <option value="1" >No hay almacenes registrados</option>
                        @endforelse
                    </select>
                </div>
                {{-- Fecha inicio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Desde
                    </label>
                    <input type="date" name="fecha_inicio"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                {{-- Fecha fin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Hasta
                    </label>
                    <input type="date" name="fecha_fin"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Generar
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
