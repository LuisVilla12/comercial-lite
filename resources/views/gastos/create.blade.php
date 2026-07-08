@section('title', content: 'Registar Movimiento de Caja')

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Registrar un Movimiento de caja
            </h2>
                        <p class="block text-md font-medium text-gray-800 dark:text-gray-200 mb- ">
                            {{ now()->format('d/m/Y H:i') }}
                        </p>

        </div>
    </x-slot>


    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <form id="formAlmacen" method="POST" action="{{ route('gastos.store') }}" class="">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 md:gap-4">
                {{-- Descripcion --}}
                <div class="mb-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Descripción: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="descripcion" placeholder="Descripcion del gasto"
                        value="{{ old('descripcion') }}"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('descripcion')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Monto: <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="total" placeholder="Total" value="{{ old('total') }}"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('total')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Caja de:<span class="text-red-500">*</span>
                    </label>
                    <select name="caja_id" id="caja_id"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="{{ $caja->id }}" selected>{{ $user->name }}</option>
                    </select>
                    @error('caja_id')
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
                        <option value="1">Gastos</option>
                        <option value="2">Retiros</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- OCULTO --}}
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            {{-- Botones --}}
            <div class="flex justify-between items-center gap-4 mt-3">
                <a href="{{ route('gastos.index') }}"
                    class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                    <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" /> Regresar
                </a>
                <div x-data @keydown.window.prevent.f9="$refs.btnRegistrar.click()">
                    <button x-ref="btnRegistrar" type="submit" id="btnGuardar"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                        Guardar [F9]
                    </button>
                </div>
            </div>
        </form>
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
