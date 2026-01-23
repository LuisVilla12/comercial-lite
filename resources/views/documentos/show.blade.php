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

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                {{ match ($documento->documento_modelo_id) {
                    1 => 'Cotización',
                    2 => 'Factura',
                    3 => 'Remisión',
                } }}
                # {{ $documento->id }} </h2>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Fecha: {{ $documento->fecha }}
            </h2>
            <div class="flex justify-between gap-3">
                @if ($documento->estatus == 1 and $documento->documento_modelo_id == 1)
                    <form method="POST" action="{{ route('cotizacionToFactura', $documento) }}">
                        @csrf
                        <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                            CONVERTIR FACTURA
                        </button>
                    </form>
                @elseif($documento->estatus == 2 and $documento->documento_modelo_id == 1)
                    <p class="px-6 py-2 bg-indigo-600 text-white rounded"> COTIZACIÓN TRANSFORMADA A FACTURA</p>
                @endif

                @if ($documento->estatus == 1 and $documento->documento_modelo_id < 2)
                    <form method="POST" action="{{ route('documentos.surtir', $documento) }}">
                        @csrf
                        <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                            CONVERTIR REMISION
                        </button>
                    </form>
                @elseif($documento->estatus == 2 and $documento->documento_modelo_id == 3)
                    <p class="px-6 py-2 bg-green-600 text-white rounded cursor-not-allowed"> REMISIÓN SURTIDA</p>
                    <a href="{{ route('devolucion.edit',$documento) }}"class="block px-6 py-2 bg-indigo-600 text-white rounded">DEVOLUCIÓN</a>
                @endif
                @if ($documento->estatus == 1 and $documento->documento_modelo_id == 3)
                    <form method="POST" action="{{ route('documentos.surtir', $documento) }}">
                        @csrf
                        <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                            SURTIR REMISION
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>
    <div x-data="{ tab: 'detalle' }">
        <div class="flex gap-4 border-b mt-4">
            <button type="button" @click="tab='detalle'"
                :class="tab === 'detalle' ? 'border-b-2 border-blue-500' : ''"
                class="block text-lg font-medium mb-2 dark:text-white">
                [1] Movimientos
            </button>

            <button type="button" @click="tab='info'" :class="tab === 'info' ? 'border-b-2 border-blue-500' : ''"
                class="block text-lg font-medium mb-2 dark:text-white">
                [2] Datos generales
            </button>
        </div>
        <div x-show="tab === 'detalle'">
            <div class="my-6">
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
        </div>
        <div x-show="tab === 'info'" x-cloak class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 mt-4">
                <label class="col-span-full block text-xl font-medium text-gray-700 dark:text-white"> Datos del cliente:
                    </span></label>
                <div class="">
                    <label class="block text-lg font-medium mb-2 dark:text-white">RFC: </label>
                    <input type="text" value="{{ $documento->cliente->rfc }}" disabled
                        class="w-full border rounded p-2 bg-gray-100">
                </div>
                <div class="">
                    <label class="block text-lg font-medium mb-2 dark:text-white">Codigo Postal: </label>
                    <input type="text" value="{{ optional($documento->cliente->domicilios->first())->cp }}" disabled
                        class="w-full border rounded p-2 bg-gray-100">
                </div>
                <div class="">
                    <label class="block text-lg font-medium mb-2 dark:text-white">Ciudad: </label>
                    <input type="text" value="{{ optional($documento->cliente->domicilios->first())->ciudad }}"
                        disabled class="w-full border rounded p-2 bg-gray-100">
                </div>
                <div class="">
                    <label class="block text-lg font-medium mb-2 dark:text-white">Calle: </label>
                    <input type="text" value="{{ optional($documento->cliente->domicilios->first())->calle }}"
                        disabled class="w-full border rounded p-2 bg-gray-100">
                </div>
                <div class="">
                    <label class="block text-lg font-medium mb-2 dark:text-white">Numero interior: </label>
                    <input type="text"
                        value="{{ optional($documento->cliente->domicilios->first())->numero_interior }}" disabled
                        class="w-full border rounded p-2 bg-gray-100">
                </div>
                <div class="">
                    <label class="block text-lg font-medium mb-2 dark:text-white">Colonia: </label>
                    <input type="text" value="{{ optional($documento->cliente->domicilios->first())->colonia }}"
                        disabled class="w-full border rounded p-2 bg-gray-100">
                </div>
                <label class="col-span-full block text-xl font-medium text-gray-700 dark:text-white"> Datos del pago:
                    </span></label>
                <div class="">
                    <label for="metodo_pago" class="block text-md font-medium text-gray-700 dark:text-white mb-1">
                        Metodo de pago: <span class="text-red-500">*</span>
                    </label>
                    <select class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="PUE" @selected(old('metodo_pago', $documento->metodo_pago) === 'PUE')>PUE Pago en una sola exhibición</option>
                        <option value="PPD" @selected(old('metodo_pago', $documento->metodo_pago) === 'PPD')>PPD Pago en Parcialidades o Diferido
                        </option>
                    </select>
                    @error('metodo_pago')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                        Forma de pago:<span class="text-red-500">*</span>
                    </label>
                    <select class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="01" @selected(old('forma_pago', $documento->forma_pago) === '01')>01 Efectivo</option>
                        <option value="03" @selected(old('forma_pago', $documento->forma_pago) === '03')>03 Transferencia</option>
                        <option value="04" @selected(old('forma_pago', $documento->forma_pago) === '04')>04 Tarjeta de crédito</option>
                        <option value="28" @selected(old('forma_pago', $documento->forma_pago) === '28')>28 Tarjeta de débito</option>
                        <option value="05" @selected(old('forma_pago', $documento->forma_pago) === '05')>05 Monedero electrónico</option>
                        <option value="02" @selected(old('forma_pago', $documento->forma_pago) === '02')>02 Cheque nominativo</option>
                    </select>
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                        Uso de CFDI <span class="text-red-500">*</span>
                    </label>
                    <select
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @foreach ($usos as $uso)
                            <option value="{{ $uso->clave }}" @selected(old('uso_cfdi', $documento->uso_cfdi) === $uso->clave)>
                                {{ $uso->clave }} - {{ $uso->descripcion }}
                            </option>
                        @endforeach
                    </select>
                    @error('uso_cfdi')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                        Observaciones <span class="text-red-500">*</span>
                    </label>
                    <textarea class="w-full" name="observaciones">{{ $documento->observaciones }}</textarea>
                </div>
            </div>
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
    </div>

</x-app-layout>
