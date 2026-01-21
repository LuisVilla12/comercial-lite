@section('title', 'Reportes')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Reportes
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">

        <form method="GET" action="{{route('reportes.export')  }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Tipo de documento --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de documento
                    </label>
                    <select name="documento_modelo_id"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="">Seleccione</option>
                        {{-- <option value="1">Cotización</option> --}}
                        <option value="2">Factura</option>
                        <option value="3">Remisión</option>
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
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Generar Excel
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
