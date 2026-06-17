
@section('title', content: 'Registar almacen')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar Almacen
        </h2>
    </x-slot>


    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <form id="formAlmacen" method="POST" action="{{ route('almacenes.store') }}" class="">
        @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4">
 <div class="md:col-span-2 lg:col-span-1 mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Codigo: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="codigo"
                   placeholder="Codigo"
                   value="{{ old('codigo') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('codigo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

        </div>
        {{-- Nombre --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Nombre del almacen: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre"
                   placeholder="Nombre del almacen"
                   value="{{ old('nombre') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('nombre')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Tipo:<span class="text-red-500">*</span>
            </label>
            <select name="tipo" id="tipo"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="" disabled selected>Seleccione una opcion</option>
                <option value="1">MATRIZ</option>
                <option value="2">SUCURSAL</option>
            </select>
            @error('tipo')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>


        {{-- Botones --}}
        <div class="flex justify-between items-center gap-4 mt-3">
            <a href="{{ route('almacenes.index') }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
            </a>
            <div x-data @keydown.window.prevent.f9="$refs.btnRegistrar.click()">
                <button
                    x-ref="btnRegistrar"
                    type="submit"
                    id="btnGuardar"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Guardar [F9]
                </button>
            </div>
        </div>
    </form>
    </div>


</x-app-layout>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formAlmacen');
    const btn  = document.getElementById('btnGuardar');
    if (!form || !btn) return;
    form.addEventListener('submit', function () {
        btn.disabled = true;
        btn.innerText = 'Guardando...';
    });
});
</script>
