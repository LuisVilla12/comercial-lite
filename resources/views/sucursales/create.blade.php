<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar Sucursal
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <form method="POST" action="{{ route('sucursales.store') }}" class="grid grid-cols-1 md:grid-cols-4 md:gap-4">
            @csrf
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Codigo: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="codigo" placeholder="Codigo" value="{{ old('codigo') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('codigo')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror

            </div>
            {{-- Nombre --}}
            <div class="col-span-3">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Nombre de la sucursal: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre" placeholder="Nombre de la sucursal" value="{{ old('nombre') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('nombre')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Serie cotización: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_cotizacion" placeholder="Serie de cotización"
                    value="{{ old('serie_cotizacion') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_cotizacion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Serie remisión: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_remision" placeholder="Serie de cotización"
                    value="{{ old('serie_remision') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_remision')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Serie facturación: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_facturacion" placeholder="Serie de cotización"
                    value="{{ old('serie_facturacion') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('serie_facturacion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Serie devolución: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="serie_devolucion" placeholder="Serie de cotización"
                    value="{{ old('serie_devolucion') }}"
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
                    value="{{ old('folio_cotizacion') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_cotizacion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Folio remisión: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_remision" placeholder="Folio de cotización"
                    value="{{ old('folio_remision') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_remision')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Folio facturación: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_facturacion" placeholder="Folio de cotización"
                    value="{{ old('folio_facturacion') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_facturacion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Folio devolución: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="folio_devolucion" placeholder="Folio de cotización"
                    value="{{ old('folio_devolucion') }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('folio_devolucion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- Botones --}}
            <div class="md:col-span-full flex justify-end gap-3 mt-4">
                <a href="{{ route('sucursales.index') }}"
                    class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                    Cancelar
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Guardar Sucursal
                </button>

            </div>
        </form>
    </div>


</x-app-layout>
