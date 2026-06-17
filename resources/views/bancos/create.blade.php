
@section('title', content: 'Registar datos bancarios')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar datos bancarios
        </h2>
    </x-slot>


    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <form id="" method="POST" action="{{ route('bancos.store') }}" class="">
        @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4">
 <div class="md:col-span-2 lg:col-span-1 mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Nombre del banco: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre_banco"
                   placeholder="Nombre del banco"
                   value="{{ old('nombre_banco') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('nombre_banco')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

        </div>
        {{-- Cuenta bancaria --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Cuenta bancaria: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="cuenta_bancaria"
                   placeholder="Cuenta bancaria"
                   value="{{ old('cuenta_bancaria') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('cuenta_bancaria')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        {{-- {{ -- CLABE --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                CLABE: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="clabe"
                   placeholder="CLABE"
                   value="{{ old('clabe') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('clabe')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        {{-- Datos de contacto --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                WhatsApp: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="whatsapp"
                   placeholder="Número de WhatsApp"
                   value="{{ old('whatsapp') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('whatsapp')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Correo electrónico: <span class="text-red-500">*</span>
            </label>
            <input type="email" name="correo_electronico"
                   placeholder="Correo electrónico"
                   value="{{ old('correo_electronico') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('correo_electronico')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
        {{-- Botones --}}
        <div class="flex justify-between items-center gap-4 mt-3">
<a href="{{ route('bancos.index') }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
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
