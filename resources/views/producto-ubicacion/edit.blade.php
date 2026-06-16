
@section('title', content: 'Registar ubicacion de un producto')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Editar ubicación de un producto
        </h2>
    </x-slot>


    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <form id="" method="POST" action="{{ route('productoubicacion.update', $productoUbicacion) }}" class="">
         @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4">
 <div class="md:col-span-2 lg:col-span-1 mb-2">
             <label class="block mb-2 text-md font-medium text-gray-700  ">
                    Seleccionar almacen: <span class="text-red-500">*</span>
                </label>
                <select name="almacen_id" id="almacen_id"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @foreach ($almacenes as $almacen)
                        <option  @selected($almacen->id == $productoUbicacion->almacen_id) value="{{ $almacen->id }}">
                            {{ $almacen->nombre}}</option>
                    @endforeach
                </select>
                @error('almacen_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
        </div>
        {{-- Zona --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Zona: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="zona"
                   placeholder=""
                   value="{{ $productoUbicacion->zona }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('zona')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        {{-- Pasillo --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Pasillo: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="pasillo"
                   placeholder=""
                   value="{{ $productoUbicacion->pasillo }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('pasillo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        {{-- anaquel --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Anaquel: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="anaquel"
                   placeholder=""
                   value="{{ $productoUbicacion->anaquel }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('anaquel')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        {{-- Repisa --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Repisa: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="repisa"
                   placeholder=""
                   value="{{ $productoUbicacion->repisa }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('repisa')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <input type="hidden" name="producto_id" value="{{ $productoUbicacion->producto_id }}">
        @error('producto_id')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
         </div>
        {{-- Botones --}}
        <div class="flex justify-between items-center gap-4 mt-3">
            <a href="{{ route('productos.show', $producto) }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium  text-white bg-red-600 hover:bg-red-600">
                Regresar
            </a>
            <div x-data @keydown.window.prevent.f10="$refs.btnRegistrar.click()">
                <button
                    x-ref="btnRegistrar"
                    type="submit"
                    id="btnGuardar"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Actualizar [F10]
                </button>
            </div>
        </div>
    </form>
    </div>


</x-app-layout>
