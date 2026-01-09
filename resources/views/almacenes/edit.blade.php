
@extends('layouts.app')

@section('title', 'Editar - Almacen')

@section('content')
    <div class="p-4">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">

    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        Editar almacen
    </h2>

    <form method="POST" action="{{ route('almacenes.update', $almacen->id) }}" class="grid grid-cols-1 md:grid-cols-2 md:gap-4">
        @csrf
        @method('PUT')
    <div class="md:col-span-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Codigo <span class="text-red-500">*</span>
            </label>
            <input type="string" name="codigo"
                   placeholder="Codigo"
                   value="{{ $almacen->codigo?? old('codigo') }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('codigo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

        </div>
        {{-- Nombre --}}
        <div class="">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Nombre del almacen<span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre"
                   placeholder="Nombre del almacen"
                   value="{{ $almacen->nombre?? old('nombre') }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('nombre')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Tipo:<span class="text-red-500">*</span>
            </label>
            <select name="tipo" id="tipo"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="" disabled selected>Seleccione una opcion</option>
                <option value="1"  @selected($almacen->tipo == 1)>MATRIZ</option>
                <option value="2" @selected($almacen->tipo == 2)>SUCURSAL</option>
                <option value="0" @selected($almacen->tipo == 0)>No asignado</option>
            </select>
            @error('tipo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="md:col-span-2 flex justify-end gap-3 mt-4">
            <a href="{{ route('almacenes.index') }}"
               class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                Cancelar
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                Guardar almacen
            </button>
        </div>


    </form>
</div>

    </div>
@endsection
