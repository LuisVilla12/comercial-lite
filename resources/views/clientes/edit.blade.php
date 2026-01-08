
@extends('layouts.app')

@section('title', 'Editar - Clientes')

@section('content')
    <div class="p-4">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">

    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        Editar {{ $tipo == 1 ? 'Cliente' : 'Proveedor' }}
    </h2>
@if (session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4">
        <p class="font-semibold">Éxito</p>
        <p>{{ session('success') }}</p>
    </div>
@endif
    <form method="POST" action="{{ route('clientes.update', $cliente->id) }}" class="grid grid-cols-1 md:grid-cols-2 gap-1">
        @csrf
        @method('PUT')
        <div class="md:col-span-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Codigo <span class="text-red-500">*</span>
            </label>
            <input type="text" name="codigo"
                   value="{{ $cliente->codigo }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('codigo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nombre --}}
        <div class="md:col-span-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Nombre / Razón Social <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre"
                   value="{{ $cliente->nombre }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('nombre')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>


        {{-- RFC --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                RFC <span class="text-red-500">*</span>
            </label>
            <input type="text" name="rfc"
                   value="{{ $cliente->rfc }}"
                   class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('rfc')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Régimen Fiscal --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Régimen Fiscal <span class="text-red-500">*</span>
            </label>
            <input type="text" name="regimen_fiscal"
                   value="{{ $cliente->regimen_fiscal }}"
                   class="p-2 w-full  rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('regimen_fiscal')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- CURP --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                CURP
            </label>
            <input type="text" name="curp"
                   value="{{ $cliente->curp }}"
                   class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Email 1 --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Correo electrónico principal
            </label>
            <input type="email" name="email1"
                   value="{{ $cliente->email1 }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('email1')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email 2 --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Correo electrónico alterno
            </label>
            <input type="email" name="email2"
                   value="{{ $cliente->email2 }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Teléfono --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Teléfono
            </label>
            <input type="text" name="telefono"
                   value="{{ $cliente->telefono }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- WhatsApp --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                WhatsApp
            </label>
            <input type="text" name="whatsapp"
                   value="{{ $cliente->whatsapp }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Botones --}}
        <div class="md:col-span-2 flex justify-end gap-3 mt-4">
            <a href="{{ route($tipo == 1 ? 'clientes.index' : 'proveedores.index') }}"
               class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                Cancelar
            </a>


            @php
            $domicilio = $cliente->domicilios()->first();
            @endphp
            @if($domicilio)

                <a class="bg-blue-600 text-white px-6 py-2 rounded" href="{{ route('domicilios.edit', [
                    'cliente' => $cliente->id,
                    'domicilio' => $domicilio->id
                ]) }}">
                    Editar domicilio
                </a>
            @endif

            <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                Actualizar {{ $tipo == 1 ? 'Cliente' : 'Proveedor' }}
            </button>
        </div>

    </form>
</div>

    </div>
@endsection
