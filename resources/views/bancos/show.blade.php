
@section('title', content: 'Registar datos bancarios')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Ver datos bancarios
        </h2>
    </x-slot>


    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4">
 <div class="md:col-span-2 lg:col-span-1 mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Nombre del banco: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre_banco"
                   placeholder="Nombre del banco"
                   value="{{$banco->nombre_banco ?? old('nombre_banco') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
        {{-- Cuenta bancaria --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Cuenta bancaria: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="cuenta_bancaria"
                   placeholder="Cuenta bancaria"
                   value="{{ $banco->cuenta_bancaria ?? old('cuenta_bancaria') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
        {{-- {{ -- CLABE --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                CLABE: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="clabe"
                   placeholder="CLABE"
                   value="{{ $banco->clabe ?? old('clabe') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">

        </div>
        {{-- Datos de contacto --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                WhatsApp: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="whatsapp"
                   placeholder="Número de WhatsApp"
                   value="{{ $banco->whatsapp ?? old('whatsapp') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Correo electrónico: <span class="text-red-500">*</span>
            </label>
            <input type="email" name="correo_electronico"
                   placeholder="Correo electrónico"
                   value="{{ $banco->correo_electronico ?? old('correo_electronico') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
    </div>
        {{-- Botones --}}
        <div class="flex justify-between items-center gap-4 mt-3">
            <a href="{{ route('bancos.index') }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium  text-white bg-red-600 hover:bg-red-600">
                Regresar
            </a>
        </div>
    </div>


</x-app-layout>
