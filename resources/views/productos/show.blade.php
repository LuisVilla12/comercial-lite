@section('title', content: 'Datos generales del producto')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Detalles de producto
        </h2>
    </x-slot>
    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">{{ session('success') }}
        </p>
    @endif

         <div x-data="{ tab: 'detalle' }">
                <div class="flex gap-4 border-b mt-4">
                    <button type="button" @click="tab='detalle'"
                        :class="tab === 'detalle' ? 'border-b-2 border-blue-500' : ''"
                        class="block text-lg font-medium mb-2">
                        [1] Datos generales
                    </button>

                    <button type="button" @click="tab='info'"
                        :class="tab === 'info' ? 'border-b-2 border-blue-500' : ''"
                        class="block text-lg font-medium mb-2 ">
                        [2] Datos puntuales
                    </button>
                </div>
        <div  x-show="tab === 'detalle'">
            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3 border-b pb-2">
            Datos generales del producto
        </h3>
<div  class="grid grid-cols-1 md:grid-cols-3  mt-4 lg:grid-cols-4 gap-4">
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Clave: <span class="text-red-500">*</span>
                </label>
                <input name="clave_producto" placeholder="Clave" disabled value="{{ $producto->clave_producto }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('clave_producto')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Codigo: <span class="text-red-500">*</span>
                </label>
                <input name="codigo_producto" placeholder="Codigo" disabled value="{{ $producto->codigo_producto }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('codigo_producto')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- Nombre --}}
            <div class="md:col-span-2">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Nombre: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre_producto" placeholder="Nombre del producto" disabled
                    value="{{ $producto->nombre_producto }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- Codigo alterno --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Codigo alterno:
                </label>
                <input type="text" name="codigo_alterno" disabled
                    value="{{ $producto->codigo_alterno == '' ? 'N/A' : $producto->codigo_alterno }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- Clave sat --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Clave sat: <span class="text-red-500">*</span>
                </label>
                <input type="text" name="clave_sat" placeholder="Ej. 601, 603, 612" disabled
                    value="{{ $producto->clave_sat }}"
                    class="p-2 w-full  rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- Peso producto --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Peso producto (KG):
                </label>
                <input type="text" name="peso_producto"
                    value="{{ $producto->peso_producto == '' ? 'N/A' : $producto->peso_producto }}" disabled
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- Unidad --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Unidad de medida:<span class="text-red-500">*</span>
                </label>
                <select name="unidad_medida" id="unidad_medida"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="" disabled selected>Seleccione unidad</option>
                    <option value="1" disabled @selected($producto->unidad_medida == '1')>PIEZA (PZ)</option>
                    <option value="2" disabled @selected($producto->unidad_medida == '2')>METRO (MT)</option>
                    <option value="4" disabled @selected($producto->unidad_medida == '4')>KILO (KG)</option>
                </select>
                @error('unidad_medida')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- Clasificacion --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Clasificación:<span class="text-red-500">*</span>
                </label>
                <input type="text" name="valor_clasificacion1" placeholder="Clasificación" disabled
                    value="{{ $producto->clasificacion1->nombre ?? 'N/A' }}"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
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
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Precio 1: <span class="text-red-500">*</span>
                </label>
                <input type="number" name="precio1" placeholder="Precio1" value="{{ $producto->precio1 }}" disabled
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- Precio 2 --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Precio 2:
                </label>
                <input type="number" name="precio2" value="{{ $producto->precio2 == '' ? '0' : $producto->precio2 }}"
                    disabled placeholder="Precio 2"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- Precio 3 --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Precio 3:
                </label>
                <input type="number" name="precio3" value="{{ $producto->precio3 == '' ? '0' : $producto->precio3 }}"
                    disabled placeholder="Precio 3"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            {{-- Precio 4 --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Precio 4:
                </label>
                <input type="number" name="precio4" value="{{ $producto->precio4 == '' ? '0' : $producto->precio4 }}"
                    disabled placeholder="Precio 4"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            {{-- Precio 5 --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Precio 5:
                </label>
                <input type="number" name="precio5"
                    value="{{ $producto->precio5 == '' ? '0' : $producto->precio5 }}" disabled placeholder="Precio 5"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            {{-- Precio Calculado --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Precio Calculado:
                </label>
                <input type="number" name="precio_calculado"
                    value="{{ $producto->precio_calculado == '' ? '0' : $producto->precio_calculado }}" disabled
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            {{-- Impuesto --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Impuesto:
                </label>
                <input type="number" name="impuesto1"
                    value="{{ $producto->impuesto1 == '' ? '0' : $producto->impuesto1 }}" disabled
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            {{-- Retencion --}}
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Retención:
                </label>
                <input type="number" name="retencion1"
                    value="{{ $producto->retencion1 == '' ? '0' : $producto->retencion1 }}" disabled
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="mb-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Importe extra:
                </label>
                <input type="number" name="importe_extra"
                    value="{{ $producto->importe_extra == '' ? '0' : $producto->importe_extra }}" disabled
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="mb-1 col-span-1 md:col-span-2 lg:col-span-1">
                <label class="block text-md font-medium text-gray-700 mb-1">
                    Exento de impuesto:
                </label>
                <select name="exento_impuesto" id="exento_impuesto"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="" disabled selected>Seleccione</option>
                    <option value="1" @selected($producto->exento_impuesto == '1')>Si</option>
                    <option value="0" @selected($producto->exento_impuesto == '0')>No</option>
                </select>
            </div>
        </div>
        </div>
        <div  x-show="tab === 'info'">
        <div  class="flex justify-between items-center  text-gray-800 mt-6 mb-3 border-b pb-2">
            <h3 class="text-lg font-semibold">
                Minimos y Maximos
            </h3>
            <a href="{{ route('maxmin.create', $producto) }}"
            class="px-4 py-2 rounded-md border-red-100 font-medium  text-white bg-blue-600 hover:bg-blue-600">
            Definir
        </a>
        </div>
        @if($producto->maximominimo->isNotEmpty())
        <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">
                                Almacén
                            </th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">
                                Mínimo
                            </th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">
                                Máximo
                            </th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producto->maximominimo as $registro)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    {{ $registro->almacen->nombre }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $registro->minimo }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $registro->maximo }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{-- Eliminar --}}
                            <form action="{{ route('maxmin.destroy', ['producto'=>$producto->id,'minimomaximo' => $registro->id]) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="inline-flex items-center gap-1 text-gray-500 hover:text-red-600 transition"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                    <span class="hidden sm:inline">Eliminar</span>
                                </button>
                            </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No hay registrado ningun minimo y maximo</p>
        @endif

        <div  class="flex justify-between items-center  text-gray-800 mt-6 mb-3 border-b pb-2">
            <h3 class="text-lg font-semibold">
                Ubicaciones
            </h3>
            <a href="{{ route('productoubicacion.create', $producto) }}"
            class="px-4 py-2 rounded-md border-red-100 font-medium  text-white bg-blue-600 hover:bg-blue-600">
            Definir
        </a>
        </div>
        @if($producto->productoUbicacion->isNotEmpty())
        <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">
                                Almacén
                            </th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">
                                Zona
                            </th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">
                                Pasillo
                            </th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">
                                Anaquel
                            </th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">
                                Repisa
                            </th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producto->productoUbicacion as $registro)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    {{ $registro->almacen->nombre }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $registro->zona }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $registro->pasillo }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $registro->anaquel }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $registro->repisa }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                     {{-- Editar --}}
                                        <a href="{{ route('productoubicacion.edit', ['producto' => $producto,'productoUbicacion' => $registro->id]) }}"
                                            class="inline-flex items-center gap-1 text-gray-600 hover:text-indigo-600 transition">
                                            <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            <span class="hidden sm:inline">Editar</span>
                                        </a>
                                        <span class="hidden sm:inline text-gray-300">•</span>
                                    {{-- Eliminar --}}
                            <form action="{{ route('productoubicacion.destroy', ['producto'=>$producto->id,'productoUbicacion' => $registro->id]) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="inline-flex items-center gap-1 text-gray-500 hover:text-red-600 transition"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                    <span class="hidden sm:inline">Eliminar</span>
                                </button>
                            </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No hay registrado ninguna ubicacion</p>
        @endif
        </div>




        {{-- Botones --}}
        <div class="md:col-span-2 flex  gap-3 mt-4">
            <a href="{{route('productos.index') }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
            </a>
        </div>
    </div>
    </div>
    </div>
</x-app-layout>
