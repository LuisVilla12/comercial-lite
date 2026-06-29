@section('title', 'Reportes')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Reportes
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">
        <h2 class="mb-4 font-semibold text-lg text-gray-800 ">
            Reporte de conceptos
        </h2>
        <form method="GET" action="{{ route('reportes.conceptos.export') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Sucursal
                    </label>
                    <select id="sucursal_select" name="sucursal_id"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="" selected disabled>Seleccione una sucursal</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}" data-serie-factura="{{ $sucursal->serie_factura }}"
                                data-serie-remision="{{ $sucursal->serie_remision }}">
                                {{ $sucursal->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de documento
                    </label>
                    <select name="documento_modelo_id" id="tipo_documento_select"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" disabled
                        required>
                        <option value="" selected disabled>Seleccione un tipo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Usuario
                    </label>
                    <select name="user_id" id="user_id"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="" selected disabled>Seleccione un tipo</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{$usuario->name }}</option>
                        @endforeach
                    </select>

                </div>
                {{-- Fecha inicio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Desde
                    </label>
                    <input type="date" name="fecha_inicio"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                {{-- Fecha fin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Hasta
                    </label>
                    <input type="date" name="fecha_fin"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Generar Reporte
                </button>
            </div>
        </form>
    </div>
        <div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">

        <form method="GET" action="{{ route('reportes.productos.export') }}">
            <h2 class="mb-4 font-semibold text-lg text-gray-800 ">
            Reporte de articulos vendidos
        </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Sucursal
                    </label>
                    <select id="sucursal_select" name="sucursal_id"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="" selected disabled>Seleccione una sucursal</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}" data-serie-factura="{{ $sucursal->serie_factura }}"
                                data-serie-remision="{{ $sucursal->serie_remision }}">
                                {{ $sucursal->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Fecha inicio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Desde
                    </label>
                    <input type="date" name="fecha_inicio"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                {{-- Fecha fin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Hasta
                    </label>
                    <input type="date" name="fecha_fin"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Generar Reporte
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
<script>
    document.getElementById('sucursal_select').addEventListener('change', function() {
        const tipoSelect = document.getElementById('tipo_documento_select');
        tipoSelect.innerHTML = '<option value="" disabled selected>Seleccione un tipo</option>';

        if (!this.value) {
            tipoSelect.disabled = true;
            return;
        }
        // Remisión
        tipoSelect.innerHTML += `<option value="3">Remisión</option>`;
        // Factura
        tipoSelect.innerHTML += `<option value="2">Factura</option>`;
        // Ambos
        tipoSelect.innerHTML += `<option value="4">Factura y Remisión</option>`;

        tipoSelect.disabled = false;
    });
</script>
