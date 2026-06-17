@section('title', content: 'Registrar sucursal' )

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar Sucursal
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <form method="POST" action="{{ route('sucursales.store') }}" class=" md:grid md:grid-cols-4 md:gap-4">
            @csrf
            <div class="mb-2 ">
                <label class="block mb-2 text-md font-medium text-gray-700 ">
                    Codigo: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="codigo" placeholder="Codigo" value="{{ old('codigo') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('codigo')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror

            </div>
            {{-- Nombre --}}
            <div class="mb-2 col-span-2">
                <label class="block mb-2 text-md font-medium text-gray-700 ">
                    Nombre de la sucursal: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre" placeholder="Nombre de la sucursal" value="{{ old('nombre') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('nombre')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2 ">
                <label class="block mb-2 text-md font-medium text-gray-700  ">
                    Seleccionar almacen: <span class="text-red-500">*</span>
                </label>
                <select name="almacen_id" id="almacen_id"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="" disabled selected>Seleccione una opcion</option>
                    @foreach ($almacenes as $almacen)
                        <option  value="{{ $almacen->id }}">
                            {{ $almacen->nombre}}</option>
                    @endforeach
                </select>
                @error('almacen_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2 ">
                <label class="block mb-2 text-md font-medium text-gray-700 ">
                    Serie cotización: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_cotizacion" placeholder="Serie de cotización"
                    value="{{ old('serie_cotizacion') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_cotizacion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2 ">
                <label class="block mb-2 text-md font-medium text-gray-700 ">
                    Serie remisión: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_remision" placeholder="Serie de remisión"
                    value="{{ old('serie_remision') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_remision')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2 ">
                <label class="block mb-2 text-md font-medium text-gray-700 ">
                    Serie facturación: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_factura" placeholder="Serie de facturación"
                    value="{{ old('serie_factura') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_factura')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2 ">
                <label class="block mb-2 text-md font-medium text-gray-700 ">
                    Serie devolución: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_devolucion" placeholder="Serie de devolución"
                    value="{{ old('serie_devolucion') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_devolucion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2 ">
                <label class="block mb-2 text-md font-medium text-gray-700 ">
                    Folio cotización: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_cotizacion" placeholder="Folio de cotización"
                    value="{{ old('folio_cotizacion') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_cotizacion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2 ">
                <label class="block mb-2 text-md font-medium text-gray-700 ">
                    Folio remisión: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_remision" placeholder="Folio de remisión"
                    value="{{ old('folio_remision') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_remision')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2 ">
                <label class="block mb-2 text-md font-medium text-gray-700 ">
                    Folio facturación: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_factura" placeholder="Folio de facturación"
                    value="{{ old('folio_factura') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_factura')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2 ">
                <label class="block mb-2 text-md font-medium text-gray-700 ">
                    Folio devolución: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_devolucion" placeholder="Folio de devolución"
                    value="{{ old('folio_devolucion') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_devolucion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- Botones --}}
            <div class="mb-2 md:col-span-full flex justify-between gap-3 mt-4">
                <a href="{{ route('sucursales.index') }}"
                    class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                                    <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar

                </a>
                <div  x-data @keydown.window.prevent.f10="$refs.btnRegistrar.click()">
                    <button type="submit"
                        x-ref="btnRegistrar"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                        Guardar Sucursal [F10]
                    </button>
                </div>
            </div>
        </form>
    </div>


</x-app-layout>
