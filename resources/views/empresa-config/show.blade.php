@section('title', 'Detalles de la empresa')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Detalles de la Empresa
        </h2>
    </x-slot>
        @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mt-4 rounded-md mb-4">{{ session('success') }}
        </p>
    @endif
    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-4">

        <div class="flex flex-col md:flex-row md:justify-between">
            <label class="block text-md font-bold text-center md:text-left text-gray-700 mb-2">Timbres utilizados: {{ $timbre->utilizados }} </label>
            @if($certificados==null)
            <a href="{{ route('certificados-empresa.create') }}" class="px-6 py-2 bg-blue-500 hover:bg-blue-500 text-white rounded-md font-medium">
                Configurar certificados
            </a>
            @else
            <a href="{{ route('certificados-empresa.show') }}" class="px-6 text-center py-2 bg-blue-500 hover:bg-blue-500 text-white rounded-md font-medium">
                Mostrar certificados
            </a>
            @endif
       </div>

        <div class="mt-4">
            <div class="md:grid  md:grid-cols-3 md:gap-4">
                @csrf
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Codigo: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="codigo" placeholder="Codigo" value="{{ $empresa->codigo }}" readonly
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">

                </div>
                {{-- Nombre --}}
                <div class="col-span-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Nombre de la empresa: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" placeholder="Nombre de la empresa"
                        value="{{ $empresa->nombre }}" readonly
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
                        <option value=""  selected>Seleccione una opcion</option>
                        @foreach ($regimenes as $regimen)
                            <option  @selected ($empresa->regimen_fiscal == $regimen->codigo) value="{{ $regimen->codigo }}">{{ $regimen->codigo . ' ' . $regimen->nombre }}
                            </option>
                        @endforeach
                    </select>

                </div>


                {{-- CURP --}}
                <div class="my-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        CURP
                    </label>
                    <input type="text" name="curp" max="18" value="{{ $empresa->curp }}" placeholder="CURP"
                        readonly
                        class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                </div>

                {{-- Email 1 --}}
                <div class="my-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Correo electrónico  <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" placeholder="correo@ejemplo.com" value="{{ $empresa->email }}"
                        readonly
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">

                </div>
                {{-- WhatsApp --}}
                <div class="my-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        WhatsApp
                    </label>
                    <input type="number" name="telefono" value="{{ $empresa->telefono }}" placeholder="telefono"
                        readonly
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                </div>


            </div>
        </div>

        <h3 class="mt-6 text-lg font-semibold mb-4">Domicilio</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Calle: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="calle" placeholder="Calle de la sucursal"
                            value="{{ $empresa->calle }}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Número exterior: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="numero_exterior" placeholder="Numero exterior de la sucursal"
                            value="{{ $empresa->numero_exterior}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Número interior: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="numero_interior" placeholder=""
                            value="{{ $empresa->numero_interior}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Colonia: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="colonia" placeholder="Colonia de la sucursal"
                            value="{{ $empresa->colonia}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Ciudad: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="ciudad" placeholder="Ciudad de la sucursal"
                            value="{{ $empresa->ciudad}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Municipio: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="municipio" placeholder="Municipio de la sucursal"
                            value="{{ $empresa->municipio}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Estado: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="estado" placeholder="Estado de la sucursal"
                            value="{{ $empresa->estado}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Codigo Postal: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="cp" placeholder="Codigo Postal de la sucursal"
                            value="{{ $empresa->cp}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
        {{-- Botones --}}
        <div class="md:col-span-full flex justify-between gap-3 mt-4">
            <a href="{{route('dashboard')  }}" class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
            </a>
            <a href="{{ route('configuracion-empresa.edit') }}" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                Actualizar empresa
            </a>

        </div>
    </div>


</x-app-layout>
