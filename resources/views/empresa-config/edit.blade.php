<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Editar Empresa
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <form method="POST" action="{{ route('configuracion-empresa.update', $empresa) }}" class="">
            @method('PUT')
            @csrf
            <h3 class="mt-4 text-lg font-semibold mb-4">Datos generales de la empresa</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4">
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
                    <input type="text" name="nombre" placeholder="Nombre de la empresa"
                        value="{{ $empresa->nombre }}"
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
                            <option value="{{ $regimen->codigo }}" @selected($empresa->regimen_fiscal == $regimen->codigo)>
                                {{ $regimen->codigo . ' ' . $regimen->nombre }}</option>
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
            </div>
            <h3 class="mt-6 text-lg font-semibold mb-4">Domicilio</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Calle: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="calle" placeholder="Calle de la sucursal"
                        value="{{ $empresa->calle }}"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('calle')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Número exterior: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="numero_exterior" placeholder="Número exterior de la sucursal"
                        value="{{ $empresa->numero_exterior }}"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('numero_exterior')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Número interior:
                    </label>
                    <input type="text" name="numero_interior" placeholder="Número exterior de la sucursal" value="{{ $empresa->numero_interior }}"

                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Colonia: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="colonia" placeholder="Colonia de la sucursal"
                        value="{{ $empresa->colonia }}"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('colonia')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Ciudad: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ciudad" placeholder="Ciudad de la sucursal"
                        value="{{ $empresa->ciudad }}"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('ciudad')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Municipio: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="municipio" placeholder="Municipio de la sucursal"
                        value="{{ $empresa->municipio }}"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('municipio')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Estado: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="estado" placeholder="Estado de la sucursal"
                        value="{{ $empresa->estado }}"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('estado')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Codigo Postal: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="cp" placeholder="Codigo Postal de la sucursal"
                        value="{{ $empresa->cp }}"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('cp')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- Botones --}}
            <div class="md:col-span-full flex justify-between gap-3 mt-4">
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                                    <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Actualizar empresa
                </button>

            </div>
        </form>
    </div>


</x-app-layout>
