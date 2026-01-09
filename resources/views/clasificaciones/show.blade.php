
@extends('layouts.app')

@section('title', 'Detalles de la clasificación')

@section('content')
    <div class="p-4">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">

    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        Detalles de la clasificacion
    </h2>

    <form method="POST" action="{{ route('clasificaciones.store') }}" class="grid grid-cols-1 md:grid-cols-2 md:gap-4">
        @csrf
    <div class="md:col-span-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Codigo <span class="text-red-500">*</span>
            </label>
            <input type="string" name="codigo"
                   placeholder="Codigo"
                   disabled
                   value="{{ $clasificacion->codigo ?? 'N/A'}}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('codigo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

        </div>
        {{-- Nombre --}}
        <div class="md:col-span-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Nombre de la clasificación<span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre"
                   placeholder="Nombre de la clasificacion"
                   disabled
                   value="{{$clasificacion->nombre ?? 'N/A'}}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('nombre')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>


        {{-- Botones --}}
        <div class="md:col-span-2 flex justify-end gap-3 mt-4">
            <a href="{{ route('clasificaciones.index') }}"
               class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                Regresar
            </a>

        </div>


    </form>
</div>

    </div>
@endsection
