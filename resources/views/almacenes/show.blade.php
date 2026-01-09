
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Ver almacen
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
<div class="md:col-span-2">
            <label class="block text-md font-medium text-gray-700 mb-2">
                Codigo: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="codigo"
                   placeholder="Codigo"
                   disabled
                   value="{{ $almacen->codigo?? old('codigo') }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('codigo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

        </div>
        {{-- Nombre --}}
        <div class="my-4">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Nombre del almacen: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre"
                   placeholder="Nombre del almacen"
                   disabled
                   value="{{ $almacen->nombre?? old('nombre') }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('nombre')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="my-4">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Tipo: <span class="text-red-500">*</span>
            </label>
            <select name="tipo" id="tipo"
                    class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
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
        <div class="md:col-span-2 flex justify-end gap-3 mt-4">
            <a href="{{ route('almacenes.index') }}"
               class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                Regresar
            </a>
        </div>
    </div>
</x-app-layout>
