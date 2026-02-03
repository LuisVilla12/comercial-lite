<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Ver Empresa
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4">
            @csrf
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Codigo: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="codigo" placeholder="Codigo" value="{{ $empresa->codigo}}" readonly
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">

            </div>
            {{-- Nombre --}}
            <div class="col-span-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Nombre de la empresa: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre" placeholder="Nombre de la empresa" value="{{ $empresa->nombre}}" readonly
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                </div>
            {{-- RFC --}}
            <div class="my-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    RFC <span class="text-red-500">*</span>
                </label>
                <input type="text" name="rfc" placeholder="RFC" value="{{ $empresa->rfc }}" readonly
                    class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
            </div>

            {{-- Régimen Fiscal --}}
            <div class="my-2 col-span-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Régimen Fiscal <span class="text-red-500">*</span>
                </label>
                <select name="regimen_fiscal" id="regimen_fiscal"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                    <option value="" disabled selected>Seleccione una opcion</option>
                    @foreach ($regimenes as $regimen)
                        <option value="{{ $regimen->codigo }}">{{ $regimen->codigo . ' ' . $regimen->nombre }}</option>
                    @endforeach
                </select>

            </div>


            {{-- CURP --}}
            <div class="my-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    CURP
                </label>
                <input type="text" name="curp" max="18" value="{{$empresa->curp }}" placeholder="CURP" readonly
                    class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
            </div>

            {{-- Email 1 --}}
            <div class="my-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Correo electrónico principal <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" placeholder="correo@ejemplo.com" value="{{ $empresa->email }}" readonly
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">

            </div>
            {{-- WhatsApp --}}
            <div class="my-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    WhatsApp
                </label>
                <input type="number" name="telefono" value="{{ $empresa->telefono }}" placeholder="telefono" readonly
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
            </div>
            {{-- Botones --}}
            <div class="md:col-span-full flex justify-end gap-3 mt-4">
                <a href="{{ route('domicilios.create', $empresa->id) }}"
                            class="block bg-blue-600 text-white px-3 py-2 rounded text-center">Agregar domicilio
                        </a>
                <a href="{{ route('empresas.index') }}"
                    class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                    Cancelar
                </a>
            </div>
        </div>
    </div>


</x-app-layout>
