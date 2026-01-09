<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
           Editar domicilio
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
<form method="POST" action="{{ route('domicilios.update', [$cliente->id, $domicilio->id]) }}"  class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @method('PUT')
    @csrf
        <div class="md:col-span-2">
            <label for="cp" class="block text-md font-medium text-gray-700 mb-1">
                Codigo Postal<span class="text-red-500">*</span>
            </label>
            <input type="number" id="cp" name="cp"
                   placeholder="Codigo"
                    value="{{ $domicilio->cp }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">

            @error('cp')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

        </div>
        {{-- Estado --}}
        <div class="my-2">
            <label for="estado" class="block text-md font-medium text-gray-700 mb-1"> Estado <span class="text-red-500">*</span>
            </label>
            <input type="text" name="estado" id="estado"
                   placeholder="Estado"
                    value="{{ $domicilio->estado }}"
                   class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('estado')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Municipio --}}
        <div class="my-2">
            <label for="municipio" class="block text-md font-medium text-gray-700 mb-1">
                Municipio <span class="text-red-500">*</span>
            </label>
            <input type="text" name="municipio" id="municipio"
                   placeholder="Municipio"
                   value="{{ $domicilio->ciudad }}"
                   class="p-2 w-full uppercase   rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('municipio')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        {{-- Colonia --}}
        <div class="my-2">
            <label for="colonia" class="block text-md font-medium text-gray-700 mb-1">
                Colonia<span class="text-red-500">*</span>
            </label>
            <select name="colonia" id="colonia"
                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            <option value="">Seleccione colonia</option>
                @if (old('colonia'))
                    <option value="{{ old('colonia') }}" selected>{{ old('colonia') }}</option>
                @endif

            </select>
            @error('colonia')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="">
            <label for="calle" class="block text-md font-medium text-gray-700 mb-1">
                Calle <span class="text-red-500">*</span>
            </label>
            <input type="text" id="calle" name="calle"
                   placeholder="Calle"
                    value="{{ $domicilio->calle }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('calle')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="">
            <label for="numero_interior" class="block text-md font-medium text-gray-700 mb-1">
                Numero interior <span class="text-red-500">*</span>
            </label>
            <input type="number" id="numero_interior" name="numero_interior"
                   placeholder="Numero interior"
                   value="{{ $domicilio->numero_interior }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('numero_interior')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

         <div class="">
            <label for="numero_exterior" class="block text-md font-medium text-gray-700 mb-1">
                Numero exterior
            </label>
            <input type="number" id="numero_exterior" name="numero_exterior"
                   placeholder="Numero exterior "
                   value="{{ $domicilio->numero_exterior }}"
                   class="p-4 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Botones --}}
        <div class="md:col-span-2 flex justify-end gap-3 mt-4">
            <a href="{{ route('clientes.edit', [$cliente, $cliente->tipo]) }}"
               class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                Cancelar
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                Actualizar domicilio
            </button>
        </div>
    </form>
</div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const cpInput = document.getElementById('cp');

    if (!cpInput) return;

    cpInput.addEventListener('blur', function () {
        const cp = this.value.trim();

        if (cp.length !== 5) return;

        fetch(`/api/codigos-postales/${cp}`)
            .then(res => res.json())
            .then(data => {

                if (!data.length) {
                    alert('Código postal no encontrado');
                    return;
                }

                document.getElementById('estado').value = data[0].d_estado;
                document.getElementById('municipio').value = data[0].d_mnpio;

                const coloniaSelect = document.getElementById('colonia');
                coloniaSelect.innerHTML = '<option value="">Seleccione colonia</option>';

                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.d_asenta;
                    option.textContent = item.d_asenta;
                    coloniaSelect.appendChild(option);
                });
            })
            .catch(() => alert('Error al consultar código postal'));
    });

});
</script>
