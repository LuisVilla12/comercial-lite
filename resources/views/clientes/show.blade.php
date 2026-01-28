@section('title',
    match ($tipo) {
    '1' => 'Datos generales del Cliente',
    '3' => 'Datos generales del Proveedor',
    })

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Detalles del {{ $tipo == 1 ? 'Cliente' : 'Proveedor' }}
        </h2>
    </x-slot>
    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        @if (session('success'))
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">
                {{ session('success') }}
            </p>
        @endif
        <h2 class="text-xl font-semibold">{{ $tipo == 1 ? 'Cliente' : 'Proveedor' }} : {{ $cliente->nombre }}</h2>

        <div class="grid md:grid-cols-2 gap-2 md:gap-4 ">

            <div class="mt-2 bg-white shadow rounded p-4">
                <h4 class="block text-lg font-semibold text-gray-700 my-2">Información del
                    {{ $tipo == 1 ? 'cliente' : 'proveedor' }}</h4>
                <div class="md:col-span-2">
                    <label class="block  text-md font-medium text-gray-700 my-2">
                        Codigo <span class="text-red-500">*</span>: <span>{{ $cliente->codigo }}</span>
                    </label>
                </div>
                {{-- Nombre --}}
                <div class="md:col-span-2">
                    <label class="block  text-md font-medium text-gray-700 my-2">
                        Nombre / Razón Social <span class="text-red-500">*</span>: <span>{{ $cliente->nombre }}</span>
                    </label>
                </div>
                {{-- RFC --}}
                <div class="my-2">
                    <label class="block  text-md font-medium text-gray-700 my-2">
                        RFC <span class="text-red-500">*</span>: <span>{{ $cliente->rfc }}</span>
                    </label>
                </div>

                {{-- Régimen Fiscal --}}
                <div class="my-2">
                    <label class="block  text-md font-medium text-gray-700 my-2">
                        Régimen Fiscal <span class="text-red-500">*</span>: <span>{{ $cliente->regimen_fiscal }}</span>
                    </label>
                </div>

                {{-- CURP --}}
                <div class="my-2">
                    <label class="block  text-md font-medium text-gray-700 my-2">
                        CURP : <span>{{ $cliente->curp }}</span>
                    </label>
                </div>

                {{-- Email 1 --}}
                <div class="my-2">
                    <label class="block  text-md font-medium text-gray-700 my-2">
                        Correo electrónico principal: <span>{{ $cliente->email1 }}</span>
                    </label>
                </div>

                {{-- Email 2 --}}
                <div class="my-2">
                    <label class="block  text-md font-medium text-gray-700 my-2">
                        Correo electrónico alterno: <span>{{ $cliente->email2 }}</span>
                    </label>
                </div>

                {{-- Teléfono --}}
                <div class="my-2">
                    <label class="block  text-md font-medium text-gray-700 my-2">
                        Teléfono : <span>{{ $cliente->telefono }}</span>
                    </label>
                </div>

                {{-- WhatsApp --}}
                <div class="my-2">
                    <label class="block  text-md font-medium text-gray-700 my-2">
                        WhatsApp : <span>{{ $cliente->whatsapp }}</span>
                    </label>
                </div>
            </div>

            <div class="mt-2 bg-white shadow rounded px-4">
                <div class="lg:flex lg:justify-between items-center ">
                    <h4 class="block text-lg font-semibold text-gray-700 mt-2 p-4">Domicilio</h4>
                    @if ($cliente->domicilios->count() == 0)
                        <a href="{{ route('domicilios.create', $cliente->id) }}"
                            class="block bg-blue-600 text-white px-3 py-2 rounded text-center">Agregar domicilio
                        </a>
                    @endif
                </div>
                @if ($cliente->domicilios->count())
                    @foreach ($cliente->domicilios as $dom)
                        <label class="block  text-md font-medium text-gray-700 my-2">
                            Pais: <span>{{ $dom->pais }}</span>
                        </label>
                        <label class="block  text-md font-medium text-gray-700 my-2">
                            Estado: <span>{{ $dom->estado }}</span>
                        </label>
                        <label class="block  text-md font-medium text-gray-700 my-2">
                            Municipio: <span>{{ $dom->municipio }}</span></label>
                        <label class="block  text-md font-medium text-gray-700 my-2">
                            Ciudad: <span>{{ $dom->ciudad }}</span></label>
                        <label class="block  text-md font-medium text-gray-700 my-2">
                            Colonia: <span>{{ $dom->colonia }}</span></label>
                        <label class="block  text-md font-medium text-gray-700 my-2">
                            Calle: <span>{{ $dom->calle }}</span></label>
                        <label class="block  text-md font-medium text-gray-700 my-2">
                            Numero exterior: <span>{{ $dom->numero_exterior }}</span></label>
                        <label class="block  text-md font-medium text-gray-700 my-2">
                            Numero interior : <span>{{ $dom->numero_interior }}</span></label>
                    @endforeach
                @else
                    <p class="text-gray-500 text-sm mt-4 mb-6 md:mb-0">Sin domicilio registrado</p>
                @endif
            </div>

            {{-- Botones --}}
            <div class="md:col-span-2 flex justify-end gap-3 mt-4">
                <a href="{{ route($tipo == 1 ? 'clientes.index' : 'proveedores.index') }}"
                    class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                    Regresar a {{ $tipo == 1 ? 'clientes' : 'proveedores' }}
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
