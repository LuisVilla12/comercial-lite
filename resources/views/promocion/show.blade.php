
@section('title', content: 'Detalle de la promoción')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Detalle de la promoción
        </h2>
    </x-slot>


    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <div id="formAlmacen" class="">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4">
                 <div class="md:col-span-2 lg:col-span-1 mb-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Código de la promoción: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="codigo" placeholder="Codigo" value="{{ $promocion->codigo }}" readonly
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2 lg:col-span-1 mb-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Nombre de la promoción: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" placeholder="Nombre" value="{{ $promocion->nombre }}" readonly
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                {{-- TIPO DE PROMOCION --}}
                <div class="mb-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Tipo:<span class="text-red-500">*</span>
                    </label>
                    <select name="tipo" id="tipo"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="" disabled selected>Seleccione una opcion</option>
                        <option value="PORCENTAJE" @selected(old('tipo', $promocion->tipo) === 'PORCENTAJE')>PORCENTAJE</option>
                        <option value="PRECIO" @selected(old('tipo', $promocion->tipo) === 'PRECIO')>PRECIO</option>
                    </select>
                </div>
                  {{-- Valor de la promocion --}}
                <div class="mb-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Valor de la promoción: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="valor" placeholder="Valor de la promocion:" value="{{ $promocion->valor }}" readonly
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- FECHA DE INICIO --}}
                <div class="mb-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Fecha de inicio de la promoción: <span class="text-red-500">*</span>
                    </label>
                    <input type="date"  name="fecha_inicio" value="{{ $promocion->fecha_inicio }}" readonly
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                {{-- FECHA DE FIN --}}
                <div class="mb-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Fecha de inicio de la promoción: <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="fecha_fin" value="{{ $promocion->fecha_fin }}" readonly
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            <div>
                
            </div>

            {{-- Botones --}}
            <div class="flex justify-between items-center gap-4 mt-3">
                <a href="{{ route('promociones.index') }}"
                    class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                    <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" /> Regresar
                </a>
                <div x-data @keydown.window.prevent.f9="$refs.btnRegistrar.click()">
                    <a x-ref="btnRegistrar" href="{{ route('promociones.edit',$promocion) }}" id="btnGuardar"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                        Actualizar [F9]
                    </a>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formAlmacen');
        const btn = document.getElementById('btnGuardar');
        if (!form || !btn) return;
        form.addEventListener('submit', function() {
            btn.disabled = true;
            btn.innerText = 'Guardando...';
        });
    });
</script>
