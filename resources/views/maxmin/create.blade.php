
@section('title', content: 'Registar minimo y maximo de un producto')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar Maximo y Minimos
        </h2>
    </x-slot>


    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <form id="" method="POST" action="{{ route('maxmin.store',$producto) }}" class="">
        @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4">
 <div class="md:col-span-2 lg:col-span-1 mb-2">
             <label class="block mb-2 text-md font-medium text-gray-700  ">
                    Seleccionar almacen: <span class="text-red-500">*</span>
                </label>
                <select name="almacen_id" id="almacen_id"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="" disabled selected>Seleccione una opcion</option>
                    @foreach ($almacenes as $almacen)
                        <option  value="{{ $almacen->id }}">
                            {{ $almacen->nombre}}</option>
                    @endforeach
                </select>
                @error('almacen_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
        </div>
        {{-- Minima --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Define la cantidad minima: <span class="text-red-500">*</span>
            </label>
            <input type="number" name="minimo"
                   placeholder=""
                   value="{{ old('minimo') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('minimo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        {{-- maxima --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Define la cantidad maximo: <span class="text-red-500">*</span>
            </label>
            <input type="number" name="maximo"
                   placeholder=""
                   value="{{ old('maximo') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('maximo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
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
                    Guardar [F10]
                </button>
            </div>
        </div>
    </form>
    </div>


</x-app-layout>
