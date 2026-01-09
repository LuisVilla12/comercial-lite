<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
           Registro de producto
        </h2>
    </x-slot>
    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <form method="POST" action="{{ route('productos.store') }}">
        @csrf
            <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">
        Datos generales
    </h3>
    <div class="md:col-span-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Codigo --}}
            <div class="">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Codigo: <span class="text-red-500">*</span>
            </label>
            <input name="codigo_producto"
                   placeholder="Codigo"
                   value="{{ old('codigo_producto') }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('codigo_producto')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

        </div>
        {{-- Nombre --}}
        <div class="md:col-span-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Nombre: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre_producto"
                   placeholder="Nombre del producto"
                   value="{{ old('nombre_producto') }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('nombre_producto')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Codigo alterno --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Codigo alterno:
            </label>
            <input type="text" name="codigo_alterno"
                   placeholder="Codigo alterno"
                   value="{{ old(key: 'codigo_alterno') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('codigo_alterno')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Clave sat --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Clave sat: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="clave_sat"
                   placeholder="Ej. 601, 603, 612"
                   value="{{ old(key: 'clave_sat') }}"
                   class="p-2 w-full  rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('clave_sat')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Peso producto --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Peso producto:
            </label>
            <input type="text" name="peso_producto"
                   value="{{ old(key: 'peso_producto') }}"
                   placeholder="Peso del producto"
                   step="0.01"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Unidad--}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Unidad de medida:<span class="text-red-500">*</span>
            </label>
            <select name="unidad_medida" id="unidad_medida"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="" disabled selected>Seleccione unidad</option>
                <option value="1" @selected(old('unidad_medida') == '1')>PIEZA (PZ)</option>
                <option value="2" @selected(old('unidad_medida') == '2')>METRO (MT)</option>
                <option value="4" @selected(old('unidad_medida') == '4')>KILO  (KG)</option>
            </select>
            @error('unidad_medida')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        {{-- Clasificacion--}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Clasificación:<span class="text-red-500">*</span>
            </label>
<select name="valor_clasificacion1" id="valor_clasificacion1"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="" disabled selected>Seleccione una opcion</option>
                @foreach ($clasificaciones as $clasificacion)
                    <option value="{{ $clasificacion->id }}"
                        @selected(old('valor_clasificacion1') == $clasificacion->id)>
                        {{ $clasificacion->nombre }}
                    </option>
                @endforeach
            </select>
            @error('valor_clasificacion1')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3 border-b pb-2">
        Precios e impuestos
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Precio1 --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Precio 1: <span class="text-red-500">*</span>
            </label>
            <input type="number" name="precio1"
                   placeholder="Precio1"
                          step="0.01"
                    value="{{ old(key: 'precio1') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Precio 2 --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Precio 2:
            </label>
            <input type="number" name="precio2"
                    value="{{ old(key: 'precio2') }}"
                   placeholder="Precio 2"
                   step="0.01"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Precio 3 --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Precio 3:
            </label>
            <input type="number" name="precio3"
                   value="{{ old(key: 'precio3') }}"
                   placeholder="Precio 3"
                   step="0.01"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
        {{-- Precio 4 --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Precio 4:
            </label>
            <input type="number" name="precio4"
                   value="{{ old(key: 'precio4') }}"
                   placeholder="Precio 4"
                   step="0.01"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
         {{-- Precio 5 --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Precio 5:
            </label>
            <input type="number" name="precio5"
                   value="{{ old(key: 'precio5') }}"
                   step="0.01"
                   placeholder="Precio 5"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
        {{-- Precio Calculado --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Precio Calculado:
            </label>
            <input type="number" name="precio_calculado"
                   value="{{ old(key: 'precio_calculado') }}"
                   placeholder="Precio Calculado"
                   step="0.01"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
        {{-- Impuesto --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Impuesto:
            </label>
            <input type="number" name="impuesto1"
                   value="{{ old(key: 'impuesto1') }}"
                   placeholder="Impuesto"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
        {{-- Retencion --}}
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Retención:
            </label>
            <input type="number" name="retencion1"
                   value="{{ old(key: 'retencion1') }}"
                   placeholder="Retención"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
         <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Importe extra:
            </label>
            <input type="number" name="importe_extra"
                   value="{{ old(key: 'importe_extra') }}"
                   placeholder="Importe extra"
                   step="0.01"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div class="my-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Exento de impuesto:
            </label>
            <input type="number" name="exento_impuesto"
                   value="{{ old(key: 'exento_impuesto') }}"
                   placeholder="Exento de impuesto"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
    </div>
            {{-- Botones --}}
        <div class="md:col-span-2 flex justify-end gap-3 mt-4">
            <a href="{{ route('productos.index') }}"
               class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                Cancelar
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                Guardar producto
            </button>
        </div>
    </div>

    </form>
</div>
</x-app-layout>
