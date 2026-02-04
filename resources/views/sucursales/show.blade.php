<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Sucursal {{ $sucursal->nombre }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <div class="grid grid-cols-1 md:grid-cols-4 md:gap-4">
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Codigo: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="codigo" placeholder="Codigo" value="{{ $sucursal->codigo }}" readonly
                    class="py-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
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
                    readonly class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
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
                <input type="text" name="serie_cotizacion" placeholder="Serie de cotización" readonly
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
                <input type="text" name="serie_remision" placeholder="Serie de cotización" readonly
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
                <input type="text" name="serie_facturacion" placeholder="Serie de cotización" readonly
                    value="{{ $sucursal->serie_factura }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_facturacion')
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
                <input type="text" name="folio_cotizacion" placeholder="Folio de cotización" readonly
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
                <input type="text" name="folio_cotizacion" placeholder="Folio de cotización" readonly
                    value="{{ $sucursal->folio_cotizacion }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_cotizacion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Folio facturación: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_facturacion" placeholder="Folio de cotización" readonly
                    value="{{ $sucursal->folio_factura }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_facturacion')
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

        </div>

        @if ($sucursal->domicilios->count())
            @foreach ($sucursal->domicilios as $dom)
                <h3 class="mt-6 text-lg font-semibold mb-4">Domicilio</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Calle: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" placeholder="Nombre de la sucursal"
                            value="{{ $dom->calle }}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Numero exterior: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" placeholder="Nombre de la sucursal"
                            value="{{ $dom->numero_exterior}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Numero interior: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" placeholder="Nombre de la sucursal"
                            value="{{ $dom->numero_interior ?? 'N/A'}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Colonia: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" placeholder="Nombre de la sucursal"
                            value="{{ $dom->colonia}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Ciudad: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" placeholder="Nombre de la sucursal"
                            value="{{ $dom->ciudad}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Municipio: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" placeholder="Nombre de la sucursal"
                            value="{{ $dom->municipio}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Estado: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" placeholder="Nombre de la sucursal"
                            value="{{$dom->estado}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Codigo Postal: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" placeholder="Nombre de la sucursal"
                            value="{{$dom->cp}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                     <div class="">
                        <label class="block text-md font-medium text-gray-700 mb-1">
                            Pais: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" placeholder="Pais de la sucursal"
                            value="{{$dom->pais}}" readonly
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-gray-500 text-sm mt-4 mb-6 md:mb-0">Sin domicilio registrado</p>
        @endif
        {{-- Botones --}}
        <div class="md:col-span-full flex justify-end gap-3 mt-4">
            <a href="{{ route('sucursales.index') }}"
                class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                Regresar
            </a>
            @if ($sucursal->domicilios->count() == 0)
                <a href="{{ route('domicilios.create', parameters: ['modeloTipo' => 'sucursales', 'id' => $sucursal->id]) }}"
                    class="block bg-blue-600 text-white px-3 py-2 rounded text-center">Agregar domicilio
                </a>
            @endif
        </div>
    </div>


</x-app-layout>
