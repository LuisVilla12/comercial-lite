@section('title', content: 'Ver Almacen')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Detalles Almacen
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6 grid md:grid-cols-2 lg:grid-cols-3 gap-2">
<div class="md:col-span-2 lg:col-span-1 mb-2">
            <label class="block text-md font-medium text-gray-700 mb-2">
                Codigo: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="codigo"
                   placeholder="Codigo"
                   disabled
                   value="{{ $almacen->codigo?? old('codigo') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('codigo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

        </div>
        {{-- Nombre --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Nombre del almacen: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre"
                   placeholder="Nombre del almacen"
                   disabled
                   value="{{ $almacen->nombre?? old('nombre') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('nombre')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Tipo: <span class="text-red-500">*</span>
            </label>
            <select name="tipo" id="tipo"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="" disabled selected>Seleccione una opcion</option>
                <option value="1" disabled @selected($almacen->tipo == 1)>MATRIZ</option>
                <option value="2" disabled @selected($almacen->tipo == 2)>SUCURSAL</option>
                <option value="0" disabled @selected($almacen->tipo == 0)>No asignado</option>
            </select>
            @error('tipo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="md:col-span-2 flex gap-3 mt-4">
            <a href="{{ route('almacenes.index') }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
            </a>
        </div>
    </div>
</x-app-layout>
