<x-app-layout>
    @if (session('open_pdf'))
        <script>
            if (confirm('¿Deseas imprimir la cotización?')) {
                window.open("{{ route('documentos.pdf', $documento) }}", "_blank");
            }
        </script>
    @endif
    @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4 mt-4">
            {{ session('success') }}
        </p>
    @endif
    @if (session('error'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4 mt-4">{{ session('error') }}
        </p>
    @endif

    <div class="display flex justify-between mt-6">
        <h1 class="block text-lg font-medium mb-2 dark:text-white">
            {{ match ($documento->documento_modelo_id) {
                1 => 'Cotización',
                2 => 'Factura',
                3 => 'Remisión'
            } }}
            # {{ $documento->id }}
        </h1>
        @if ($documento->estatus == 1 and $documento->documento_modelo_id ==1)
            <form method="POST" action="{{ route('cotizacionToFactura', $documento) }}">
                @csrf
                <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                    CONVERTIR FACTURA
                </button>
            </form>
        @elseif($documento->estatus == 1 and $documento->documento_modelo_id ==3)
            <form method="POST" action="{{ route('documentos.surtir', $documento) }}">
                @csrf
                <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                    SURTIR REMISION
                </button>
            </form>
        @endif

    </div>


    {{-- PROVEEDOR --}}
    <div class="mb-6">
        <label class="block text-lg font-medium mb-2 dark:text-white">Cliente: *</label>
        <input type="text" value="{{ $documento->cliente->nombre }}" disabled
            class="w-full border rounded p-2 bg-gray-100">
    </div>
    <table class="w-full border bg-white shadow rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Código</th>
                <th class="p-2">Producto</th>
                <th class="p-2">Cantidad</th>
                <th class="p-2">Costo</th>
                <th class="p-2">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($documento->detalles as $detalle)
                <tr class="border-t">
                    <td class="p-2 text-center">{{ $detalle->producto->codigo_producto }}</td>
                    <td class="p-2">{{ $detalle->producto->nombre_producto }}</td>
                    <td class="p-2 text-center">{{ $detalle->cantidad }}</td>
                    <td class="p-2 text-right">
                        ${{ number_format($detalle->costo_unitario, 2) }}
                    </td>
                    <td class="p-2 text-right">
                        ${{ number_format($detalle->importe, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-right mt-6 space-y-1">
        <p class="uppercase block text-lg font-medium mb-2 dark:text-white">Subtotal:
            ${{ number_format($documento->subtotal, 2) }}</p>
        <p class="uppercase block text-lg font-medium mb-2 dark:text-white">IVA:
            ${{ number_format($documento->impuestos, 2) }}</p>
        <p class="uppercase block text-lg font-medium mb-2 dark:text-white">
            Total: ${{ number_format($documento->total, 2) }}
        </p>
    </div>
    <div class="mt-6 flex gap-4">
        <div class="md:col-span-2 flex justify-between gap-3 mt-4">
            <a href="{{ route(match ($documento->documento_modelo_id) {1 => 'cotizaciones.index',2 => 'facturas.index',3 => 'remisiones.index'}) }}"
                class="px-4 py-2 bg-gray-500 text-white rounded">
                Volver
                @if ($documento->estatus == 1)
                    <a href="{{ route('documentos.edit', $documento) }}"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                        Actualizar
                        {{ match ($documento->documento_modelo_id) {
                            1 => 'Cotización',
                            2 => 'Factura',
                            3 => 'Remisión',
                            default => 'Documento',
                        } }}
                    </a>
                @endif
                <a href="{{ route('documentos.pdf', $documento) }}" target="_blank"
                    class="px-4 py-2 bg-red-600 text-white rounded">
                    📄 Imprimir PDF
                </a>
        </div>
    </div>
</x-app-layout>
