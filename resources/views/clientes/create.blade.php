@section('title', content: 'Registrar ' . ($tipo == 1 ? 'cliente' : 'proveedor'))

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar {{ $tipo == 1 ? 'Cliente' : 'Proveedor' }}
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <form method="POST" action="{{ route('clientes.store') }}" class="grid grid-cols-1 md:grid-cols-2 md:gap-4">
        @csrf
        <input type="hidden" name="tipo" value="{{ $tipo }}">

        <div class="md:col-span-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Codigo <span class="text-red-500">*</span>
            </label>
            <input type="text" name="codigo"
                   placeholder="Codigo"
                   value="{{ old('codigo') }}"
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
                   placeholder="Nombre o razón social"
                   value="{{ old('nombre') }}"
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
                   placeholder="RFC"
                   value="{{ old(key: 'rfc') }}"
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
            {{-- <input type="text" name="regimen_fiscal"
                   placeholder="Ej. 601, 603, 612"
                   value="{{ old(key: 'regimen_fiscal') }}"
                   class="p-2 w-full  rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"> --}}
            <select name="tipo" id="tipo"
                    class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="" disabled selected>Seleccione una opcion</option>
                <option value="1" disabled @selected($almacen->tipo == 1)>MATRIZ</option>
                <option value="2" disabled @selected($almacen->tipo == 2)>SUCURSAL</option>
                <option value="0" disabled @selected($almacen->tipo == 0)>No asignado</option>
            </select>
             @error('regimen_fiscal')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- CURP --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                CURP
            </label>
            <input type="text" name="curp" max="18"
                value="{{ old(key: 'curp') }}"
                   placeholder="CURP"
                   class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Email 1 --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Correo electrónico principal  <span class="text-red-500">*</span>
            </label>
            <input type="email" name="email1"
                   placeholder="correo@ejemplo.com"
                     value="{{ old(key: 'email1') }}"
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
                   placeholder="correo2@ejemplo.com"
                    value="{{ old(key: 'email2') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Teléfono --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Teléfono
            </label>
            <input type="number" name="telefono"
                    value="{{ old(key: 'telefono') }}"
                   placeholder="Teléfono"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- WhatsApp --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                WhatsApp
            </label>
            <input type="number" name="whatsapp"
                value="{{ old(key: 'whatsapp') }}"
                   placeholder="WhatsApp"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Botones --}}
        <div class="md:col-span-2 flex justify-end gap-3 mt-4">
            <a href="{{ route($tipo == 1 ? 'clientes.index' : 'proveedores.index') }}"
               class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                Cancelar
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                Guardar {{ $tipo == 1 ? 'Cliente' : 'Proveedor' }}
            </button>
        </div>


    </form>
    </div>
</x-app-layout>
