<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Editar Empresa
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <form method="POST" action="{{ route('empresas.edit', $empresa) }}" class="grid grid-cols-1 md:grid-cols-3 md:gap-4">
            @method('PUT')
            @csrf
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Codigo: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="codigo" placeholder="Codigo" value="{{ $empresa->codigo }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('codigo')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- Nombre --}}
            <div class="col-span-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Nombre de la empresa: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre" placeholder="Nombre de la empresa" value="{{ $empresa->nombre }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('nombre')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- RFC --}}
            <div class="my-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    RFC <span class="text-red-500">*</span>
                </label>
                <input type="text" name="rfc" placeholder="RFC" value="{{ $empresa->rfc }}"
                    class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('rfc')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Régimen Fiscal --}}
            <div class="my-2 col-span-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Régimen Fiscal <span class="text-red-500">*</span>
                </label>
                <select name="regimen_fiscal" id="regimen_fiscal"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="" disabled selected>Seleccione una opcion</option>
                    @foreach ($regimenes as $regimen)
                        <option value="{{ $regimen->codigo }}" @selected($empresa->regimen_fiscal == $regimen->codigo)>{{ $regimen->codigo . ' ' . $regimen->nombre }}</option>
                    @endforeach
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
                <input type="text" name="curp" max="18" value="{{ $empresa->curp }}" placeholder="CURP"
                    class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- Email 1 --}}
            <div class="my-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Correo electrónico principal <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" placeholder="correo@ejemplo.com" value="{{ $empresa->email }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- WhatsApp --}}
            <div class="my-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    WhatsApp
                </label>
                <input type="number" name="telefono" value="{{ $empresa->telefono }}" placeholder="telefono"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            {{-- Botones --}}
            <div class="md:col-span-full flex justify-end gap-3 mt-4">
                <a href="{{ route('empresas.index') }}"
                    class="px-4 py-2 rounded-md border-red-100 font-medium  text-white bg-red-600 hover:bg-red-600">
                    Cancelar
                </a>
                 @php
                    $domicilio = $empresa->domicilios()->first();
                @endphp
                @if ($domicilio)
                    <a class="bg-blue-600 text-white px-6 py-2 rounded"
                        href="{{ route('domicilios.edit', [
                            'modeloTipo' => 'empresa',
                            'domicilio' => $domicilio->id,
                        ]) }}">
                        Editar domicilio
                    </a>
                @endif
                <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Actualizar empresa
                </button>

            </div>
        </form>
    </div>


</x-app-layout>
