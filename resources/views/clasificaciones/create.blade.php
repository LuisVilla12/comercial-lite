@section('title', content: 'Registrar una clasificación')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar clasificacion
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <form method="POST" id="formClasificacion" action="{{ route('clasificaciones.store') }}" class="grid grid-cols-1 md:grid-cols-2 md:gap-4">
        @csrf
    <div class="mb-2 md:mb-0">
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
        <div class="mb-2 md:mb-0">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Nombre de la clasificación: <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre"
                   placeholder="Nombre de la clasificacion"
                   value="{{ old('nombre') }}"
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('nombre')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>


        {{-- Botones --}}
        <div class="md:col-span-2 flex justify-between gap-3 mt-4">
<a href="{{ route('clasificaciones.index')  }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
            </a>
            <div x-data @keydown.window.prevent.f9="$refs.btnRegistrar.click()">
                <button id="btnSave"
                    x-ref="btnRegistrar"
                    type="submit"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Guardar [F9]
                </button>
            </div>
        </div>


    </form>
    </div>
</x-app-layout>
<script>
                                // VALIDAR GUARDAR UNA VEZ
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formClasificacion');
    const btn  = document.getElementById('btnSave');
    if (!form || !btn) return;
    form.addEventListener('submit', function () {
        btn.disabled = true;
        btn.innerText = 'Guardando...';
    });
});
</script>
