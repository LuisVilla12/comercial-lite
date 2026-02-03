<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Editar Sucursal
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <form method="POST" action="{{ route('sucursales.update', parameters: $sucursal) }}"
            class="grid md:grid-cols-4 md:gap-4">
            @csrf
            @method('PUT')
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Codigo: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="codigo" placeholder="Codigo" value="{{ $sucursal->codigo }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('codigo')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror

            </div>
            {{-- Nombre --}}
            <div class="md:col-span-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Nombre de la sucursal: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre" placeholder="Nombre de la sucursal" value="{{ $sucursal->nombre }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('nombre')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1 ">
                    Seleccionar almacen: <span class="text-red-500">*</span>
                </label>
                <select name="almacen_id" id="almacen_id"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="" disabled>Seleccione una opcion</option>
                    @foreach ($almacenes as $almacen)
                        <option {{ old('almacen_id', $sucursal->almacen_id) == $almacen->id ? 'selected' : '' }}
                            value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                    @endforeach
                </select>
                @error('almacen_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Serie cotización: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_cotizacion" placeholder="Serie de cotización"
                    value="{{ $sucursal->serie_cotizacion }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_cotizacion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Serie remisión: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_remision" placeholder="Serie de remisión"
                    value="{{ $sucursal->serie_remision }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_remision')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Serie facturación: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_factura" placeholder="Serie de facturación"
                    value="{{ $sucursal->serie_factura }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_factura')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Serie devolución: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_devolucion" placeholder="Serie de devolución"
                    value="{{ $sucursal->serie_devolucion }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_devolucion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Folio cotización: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_cotizacion" placeholder="Folio de cotización"
                    value="{{ $sucursal->folio_cotizacion }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_cotizacion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Folio remisión: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_remision" placeholder="Folio de remisión"
                    value="{{ $sucursal->folio_remision }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_remision')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Folio facturación: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_factura" placeholder="Folio de facturación"
                    value="{{ $sucursal->folio_factura }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_factura')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Folio devolución: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_devolucion" placeholder="Folio de devolución"
                    value="{{ $sucursal->folio_devolucion }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_devolucion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- Botones --}}
            <div class="col-span-full flex justify-end gap-3 mt-4">
                <a href="{{ route('sucursales.index') }}"
                    class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                    Cancelar
                </a>
                @php
                    $domicilio = $sucursal->domicilios()->first();
                @endphp
                @if ($domicilio)
                    <a class="bg-blue-600 text-white px-6 py-2 rounded"
                        href="{{ route('domicilios.edit', [
                            'modeloTipo' => 'sucursal',
                            'domicilio' => $domicilio->id,
                        ]) }}">
                        Editar domicilio
                    </a>
                @endif
                <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Actualizar Sucursal
                </button>

            </div>
        </form>
    </div>


</x-app-layout>
