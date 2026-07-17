@section('title',
    match ($documento->documento_modelo_id) {
    1 => 'Cotización',
    2 => 'Facturación',
    3 => 'Remisión',
    })

    <x-app-layout>

        <div class="flex items-center mt-4 py-2 gap-3 mb-4 bg-white dark:bg-slate-800 w-full rounded-md">
            <a href="{{ route(
                match ($documento->documento_modelo_id) {
                    1 => 'cotizaciones.index',
                    2 => 'facturas.index',
                    3 => 'remisiones.index',
                },
                ['sucursal' => $sucursal],
            ) }}"
                class="flex text-white  bg-red-600 border-1  rounded-lg p-4">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />Regresar
            </a>
            <div class="md:flex md:justify-between items-center w-full">
                <div class="">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                        {{ match ($documento->documento_modelo_id) {1 => 'Cotización',2 => 'Factura',3 => 'Remisión'} . ' ' }}
                        {{ $documento->serie . ' #' . $documento->folio }}
                    </h2>
                    <p class="dark:text-white mt-2 font-semibold"> Sucursal: {{ $sucursal->nombre }}<span class="ml-6">
                            Fecha:
                            {{ \Carbon\Carbon::parse($documento->fecha)->format('d/m/Y') }}</span></p>
                </div>
                <div class="mt-2 md:mt-0 mb-4 md:mb-0">
                    <p class="dark:text-white font-semibold">Estado: @php
                        $estatusText = match (true) {
                            $documento->estatus == 4 && $documento->documento_modelo_id == 2 => 'TIMBRADA',

                            $documento->estatus == 1 => 'ACTIVA',
                            $documento->estatus == 2 => 'TRANSFORMADA',
                            $documento->estatus == 3 => 'CANCELADA',
                            $documento->estatus == 4 => 'SURTIDA',
                            $documento->estatus == 5 => 'DEVOLUCIÓN APLICADA',

                            default => 'DESCONOCIDO',
                        };
                    @endphp
                        <span class="font-bold bg-green-500 rounded-md py-2 px-6 text-white mr-6">{{ $estatusText }}</span>
                    </p>
                </div>
            </div>

        </div>
        <div class="flex flex-wrap justify-end gap-3">
            {{-- TIMBRAR FACTURA --}}
            @if ($documento->estatus == 1 and $documento->documento_modelo_id == 2)
                <button onclick="timbrar()"
                    class="group w-24 rounded-xl border border-red-500/30 bg-red-500/10 dark:bg-red-500/50 hover:bg-red-500 hover:text-white transition-all duration-200">
                    <div class="flex flex-col items-center py-1">
                        <x-heroicon-o-arrow-up-on-square-stack class="w-5 h-5 mr-2 text-red-400 group-hover:text-white" />
                        <span class="mt-1 font-semibold text-sm dark:text-white">Timbrar <span
                                class="text-xs opacity-70">F1</span></span>
                    </div>
                </button>
                <form method="POST" id="formTimbrar" class="hidden"
                    action="{{ route('documentos.timbrar', ['sucursal' => $sucursal, 'documento' => $documento->id]) }}">
                    @csrf
                </form>
            @endif
            {{-- CANCELAR FACTURA --}}
            @if ($documento->estatus == 4 and $documento->documento_modelo_id == 2)
                <button onclick="cancelar()"
                    class="group w-24 rounded-xl border border-red-500/30 bg-red-500/10 dark:bg-red-500/50 hover:bg-red-500 hover:text-white transition-all duration-200">
                    <div class="flex flex-col items-center py-1">
                        <x-heroicon-o-x-mark class="w-5 h-5 mr-2 text-red-400 group-hover:text-white" />
                        <span class="mt-1 font-semibold text-sm dark:text-white">Cancelar <span
                                class="text-xs opacity-70">F1</span></span>
                    </div>
                </button>
                <form method="POST" id="formCancelar" class="hidden"
                    action="{{ route('documentos.cancelar', ['sucursal' => $sucursal, 'documento' => $documento->id]) }}">
                    @csrf
                    <input type="hidden" name="motivo" id="motivo">
                    <input type="hidden" name="uuid_sustitucion" id="uuid_sustitucion">
                </form>
            @endif
            <!-- Surtir REMISION-->
            @if ($documento->estatus == 1 and $documento->documento_modelo_id == 3)
                <button onclick="surtirRemision()"
                    class="group w-24 rounded-xl border border-orange-500/30 bg-orange-500/10 dark:bg-orange-500/50 hover:bg-orange-500 hover:text-white transition-all duration-200">
                    <div class="flex flex-col items-center py-1">
                        <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2 text-orange-400 group-hover:text-white" />
                        <span class="mt-1 font-semibold text-sm dark:text-white">Surtir <span
                                class="text-xs opacity-70">F1</span></span>
                    </div>
                </button>
                <form method="POST"
                    id="formSurtirRemision"action="{{ route('documentos.surtir', ['sucursal' => $sucursal, 'documento' => $documento]) }}"
                    class="mr-6 hidden">
                    @csrf
                </form>
            @endif
            <!-- Cambio -->
            @if ($documento->estatus == 1 and $documento->documento_modelo_id > 1)
                <button onclick="openCambioModal()"
                    class="group w-24 rounded-xl border border-green-500/30 bg-green-500/10 dark:bg-green-500/50 hover:bg-green-500 hover:text-white transition-all duration-200">
                    <div class="flex flex-col items-center py-1">
                        <x-heroicon-o-currency-dollar class="w-5 h-5 text-green-400 group-hover:text-white" />
                        <span class="mt-1 font-semibold text-sm dark:text-white">Cambio <span
                                class=" text-xs opacity-70">F2</span></span>

                    </div>
                </button>
            @endif
            {{-- COTIZACION CONVERTIR A FACTURA Y REMISION --}}
            @if ($documento->estatus == 1 and $documento->documento_modelo_id == 1)
                <button onclick="seleccionarConversion()"
                    class="group w-24 rounded-xl border border-violet-500/30 bg-violet-500/10 dark:bg-violet-500/50 hover:bg-violet-500 hover:text-white transition-all duration-200">
                    <div class="flex flex-col items-center py-1">
                        <x-heroicon-o-arrow-path-rounded-square
                            class="w-5 h-5 mr-2 text-violet-400 group-hover:text-white" />
                        <span class="mt-1 font-semibold text-sm dark:text-white">Convertir <span
                                class="text-xs opacity-70">F3</span></span>
                    </div>
                </button>
                <form id="formFactura" method="POST"
                    action="{{ route('convertir', ['sucursal' => $sucursal, 'documento' => $documento, 'tipo' => 2]) }}"
                    class="hidden">
                    @csrf
                </form>
                <form id="formRemision" method="POST"
                    action="{{ route('convertir', ['sucursal' => $sucursal, 'documento' => $documento, 'tipo' => 3]) }}"
                    class="hidden">
                    @csrf
                </form>
                <!-- Imprimir -->
                <a href="{{ route('documentos.pdf', [$sucursal, $documento]) }}" target="_blank"
                    class="flex items-center justify-center group w-24 rounded-xl border border-blue-500/30 bg-blue-500/10 dark:bg-blue-500/50 hover:bg-blue-500 hover:text-white transition-all duration-200">
                    <div class="flex flex-col items-center">
                        <x-heroicon-o-printer class="w-5 h-5 mr-2 text-blue-400 group-hover:text-white" />
                        <span class="mt-1 font-semibold text-sm dark:text-white">Imprimir<span
                                class=" text-xs opacity-70 ml-1">F4</span></span>
                    </div>
                </a>
            @endif
            @if ($documento->estatus == 1 and $documento->documento_modelo_id == 3)
                <button onclick="convertirAFactura()"
                    class="group w-24 rounded-xl border border-violet-500/30 bg-violet-500/10 dark:bg-violet-500/50 hover:bg-violet-500 hover:text-white transition-all duration-200">
                    <div class="flex flex-col items-center py-1">
                        <x-heroicon-o-arrow-path-rounded-square
                            class="w-5 h-5 mr-2 text-violet-400 group-hover:text-white" />
                        <span class="mt-1 font-semibold text-sm dark:text-white">Convertir <span
                                class="text-xs opacity-70">F3</span></span>
                    </div>
                </button>
                <form method="POST" class="hidden" id="formConversionFactura"
                    action="{{ route('convertir', ['sucursal' => $sucursal, 'documento' => $documento, 'tipo' => 2]) }}">
                    @csrf
                </form>
            @endif
            @if ($documento->documento_modelo_id > 1)
                <button onclick="seleccionarImpresora()"
                    class="group w-24 rounded-xl border border-blue-500/30 bg-blue-500/10 dark:bg-blue-500/50 hover:bg-blue-500 hover:text-white transition-all duration-200">
                    <div class="flex flex-col items-center">
                        <x-heroicon-o-printer class="w-5 h-5 mr-2 text-blue-400 group-hover:text-white" />
                        <span class="mt-1 font-semibold text-sm dark:text-white">Imprimir<span
                                class=" text-xs opacity-70">F4</span></span>
                    </div>
                </button>
            @endif
            <!-- Enviar -->
            <button onclick="openEmailModal()"
                class="group w-24 rounded-xl border border-yellow-500/30 bg-yellow-500/10 dark:bg-yellow-500/50 hover:bg-yellow-500 hover:text-white transition-all duration-200">
                <div class="flex flex-col items-center py-1">
                    <x-heroicon-o-envelope class="w-5 h-5 mr-2 text-yellow-400 group-hover:text-white" />
                    <span class="mt-1 font-semibold text-sm dark:text-white">Enviar <span
                            class=" text-xs opacity-70 ml-1">F5</span></span>
                </div>
            </button>

            <!-- WhatsApp -->
            <a href="https://wa.me/521{{ $documento->cliente->whatsapp }}?text={{ urlencode('Hola  tu compra fue de $' . $documento->total . '. Gracias por tu preferencia.') }}"
                target="__blank"
                class="flex justify-center items-center group w-24 rounded-xl border border-emerald-500/30 bg-emerald-500/10 dark:bg-emerald-500/50 hover:bg-emerald-500 hover:text-white transition-all duration-200">
                <div class="flex flex-col items-center py-1">
                    <x-heroicon-o-device-phone-mobile class="w-5 h-5 mr-2 text-emerald-400 group-hover:text-white" />
                    <span class="mt-1 font-semibold  text-sm dark:text-white">WhatsApp<span
                            class="text-xs opacity-70">F6</span></span>
                </div>
            </a>
            {{-- DEVOLUCION --}}
            @if ($documento->estatus == 4 and $documento->documento_modelo_id == 3)
                <a href="{{ route('devolucion.edit', ['sucursal' => $sucursal, 'documento' => $documento]) }}"
                    class="flex items-center justify-center group w-24 rounded-xl border border-purple-500/30 bg-purple-500/10 dark:bg-purple-500/50 hover:bg-purple-500 hover:text-white transition-all duration-200">
                    <div class="flex flex-col items-center">
                        <x-heroicon-o-arrow-uturn-right class="w-5 h-5 mr-2 text-purple-400 group-hover:text-white" />
                        <span class="mt-1 font-semibold text-sm dark:text-white">Devolución<span
                                class=" text-xs opacity-70 ml-1">F4</span></span>
                    </div>
                </a>
            @endif
        </div>

        {{-- CONTENEDOR GENERAL --}}
        <div class="md:flex md:justify-between gap-2">
            <div class="md:w-9/12 px-1 ">
                <div x-data="{ tab: 'detalle' }">
                    <div class="flex gap-4 border-b mt-4 bg-white dark:bg-slate-800 w-full p-1 rounded-md">
                        <button type="button" @click="tab='detalle'"
                            :class="tab === 'detalle' ? 'border-b-2 border-blue-500' : ''"
                            class="block text-lg font-medium mb-2 dark:text-white">
                            [1] Movimientos
                        </button>

                        <button type="button" @click="tab='info'"
                            :class="tab === 'info' ? 'border-b-2 border-blue-500' : ''"
                            class="block text-lg font-medium mb-2 dark:text-white">
                            [2] Datos generales
                        </button>
                    </div>
                    <div x-show="tab === 'detalle'" class="">
                        <div class="my-2 bg-white dark:bg-slate-800 w-full rounded-md p-2">
                            <label class="block text-lg font-medium mb-2 dark:text-white">Cliente:
                                {{ $documento->cliente->nombre }}</label>
                        </div>
                        <!-- TABLE: solo visible en desktop -->
                        <div class="hidden md:block bg-white  w-full rounded-md">
                            <table class="w-full border ">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-2">Código</th>
                                        <th class="p-2">Producto</th>
                                        <th class="p-2">Cantidad</th>
                                        <th class="p-2">Costo</th>
                                        <th class="p-2">Descuento</th>
                                        <th class="p-2">Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documento->detalles as $detalle)
                                        <tr class="border-t">
                                            <td class="p-2 text-center">
                                                {{ $detalle->producto->codigo_producto }}
                                            </td>
                                            <td class="p-2">
                                                {{ $detalle->producto->nombre_producto }}
                                            </td>
                                            <td class="p-2 text-center">
                                                {{ $detalle->cantidad }}
                                            </td>
                                            <td class="p-2 text-right">
                                                ${{ number_format($detalle->costo_unitario, 2) }}
                                            </td>
                                            <td class="p-2 text-right">
                                                {{ number_format($detalle->descuento) }}%
                                            </td>
                                            <td class="p-2 text-right">
                                                ${{ number_format($detalle->importe, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- CARDS: visible en tablet y móvil -->
                        <div class="md:hidden space-y-4">
                            @foreach ($documento->detalles as $detalle)
                                <div class="border rounded-lg shadow bg-white p-4">
                                    <div class="flex justify-between text-sm text-gray-500">
                                        <span>Código</span>
                                        <span class="font-medium text-gray-800">
                                            {{ $detalle->producto->codigo_producto }}
                                        </span>
                                    </div>

                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Producto</p>
                                        <p class="font-semibold">
                                            {{ $detalle->producto->nombre_producto }}
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 mt-3 text-sm">
                                        <div>
                                            <p class="text-gray-500">Cantidad</p>
                                            <p class="font-medium">{{ $detalle->cantidad }}</p>
                                        </div>

                                        <div>
                                            <p class="text-gray-500">Costo</p>
                                            <p class="font-medium">
                                                ${{ number_format($detalle->costo_unitario, 2) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Descuento</p>
                                            <p class="font-medium">
                                                {{ number_format($detalle->descuento) }} %
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-3 border-t pt-2 flex justify-between">
                                        <span class="text-gray-500 text-sm">Importe</span>
                                        <span class="font-semibold text-lg">
                                            ${{ number_format($detalle->importe, 2) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <div x-show="tab === 'info'" x-cloak class="space-y-4">
                        <div class="md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4 lg:gap-4">
                            <label
                                class="mt-4 col-span-full block text-center md:text-left text-xl font-medium text-gray-700 dark:text-white">
                                Datos del cliente:</label>
                            <div class="mb-2">
                                <label class="block text-lg font-medium mb-2 dark:text-white">RFC: </label>
                                <input type="text" value="{{ $documento->cliente->rfc }}"
                                    class="w-full border rounded p-2 bg-gray-100">
                            </div>
                            <div class="mb-2">
                                <label class="block text-lg font-medium mb-2 dark:text-white">Codigo Postal: </label>
                                <input type="text" value="{{ optional($documento->domicilios->first())->cp }}"
                                    disabled class="w-full border rounded p-2 bg-gray-100">
                            </div>
                            <div class="mb-2">
                                <label class="block text-lg font-medium mb-2 dark:text-white">Ciudad: </label>
                                <input type="text" value="{{ optional($documento->domicilios->first())->ciudad }}"
                                    disabled class="w-full border rounded p-2 bg-gray-100">
                            </div>
                            <div class="mb-2">
                                <label class="block text-lg font-medium mb-2 dark:text-white">Calle: </label>
                                <input type="text" value="{{ optional($documento->domicilios->first())->calle }}"
                                    disabled class="w-full border rounded p-2 bg-gray-100">
                            </div>
                            {{-- <div class="">
                    <label class="block text-lg font-medium mb-2 dark:text-white">Numero interior: </label>
                    <input type="text"
                        value="{{ optional($documento->domicilios->first())->numero_interior }}" disabled
                        class="w-full border rounded p-2 bg-gray-100">
                </div> --}}
                            <div class="mb-2">
                                <label class="block text-lg font-medium mb-2 dark:text-white">Numero exterior: </label>
                                <input type="text"
                                    value="{{ optional($documento->domicilios->first())->numero_exterior }}" disabled
                                    class="w-full border rounded p-2 bg-gray-100">
                            </div>
                            <div class="mb-2">
                                <label class="block text-lg font-medium mb-2 dark:text-white">Colonia: </label>
                                <input type="text" value="{{ optional($documento->domicilios->first())->colonia }}"
                                    disabled class="w-full border rounded p-2 bg-gray-100">
                            </div>
                            <label
                                class="col-span-full block text-xl text-center md:text-left font-medium text-gray-700 dark:text-white mt-4">
                                Datos del pago:
                                </span></label>
                            <div class="mb-2">
                                <label for="metodo_pago"
                                    class="block text-md font-medium text-gray-700 dark:text-white mb-1">
                                    Metodo de pago: <span class="text-red-500">*</span>
                                </label>
                                <select
                                    class="p-2 w-full  rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="PUE" @selected(old('metodo_pago', $documento->metodo_pago) === 'PUE')>PUE Pago en una sola exhibición
                                    </option>
                                    <option value="PPD" @selected(old('metodo_pago', $documento->metodo_pago) === 'PPD')>PPD Pago en Parcialidades o
                                        Diferido
                                    </option>
                                </select>
                                @error('metodo_pago')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                                    Forma de pago:<span class="text-red-500">*</span>
                                </label>
                                <select
                                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="01" @selected(old('forma_pago', $documento->forma_pago) === '01')>01 Efectivo</option>
                                    <option value="03" @selected(old('forma_pago', $documento->forma_pago) === '03')>03 Transferencia</option>
                                    <option value="04" @selected(old('forma_pago', $documento->forma_pago) === '04')>04 Tarjeta de crédito</option>
                                    <option value="28" @selected(old('forma_pago', $documento->forma_pago) === '28')>28 Tarjeta de débito</option>
                                    <option value="05" @selected(old('forma_pago', $documento->forma_pago) === '05')>05 Monedero electrónico</option>
                                    <option value="02" @selected(old('forma_pago', $documento->forma_pago) === '02')>02 Cheque nominativo</option>
                                </select>
                            </div>
                            <div class="mb-2">
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
                            @if ($documento->documento_modelo_id == 1)
                                <div class="mb-2">
                                    <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                                        Vigencia del documento:<span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="vigencia" value="{{ $documento->vigencia }}"
                                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            @endif
                            <div class="mb-2">
                                <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                                    Agente:<span class="text-red-500">*</span>
                                </label>
                                <select name="agente_id" id="agente_id"
                                    class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="" disabled>Seleccione un agente</option>
                                    {{-- <option value="0">Ninguno</option> --}}
                                    @foreach ($agentes as $agente)
                                        <option value="{{ $agente->id }}" @selected(old('agente_id', $documento->agente_id) === $agente->id)>
                                            {{ $agente->codigo . ' - ' . $agente->nombre . ' ' . $agente->apellidoP }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('agente_id')
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
                </div>
            </div>
            {{-- RESUMEN --}}
            <div class="md:w-3/12 mt-4 ">
                <div class="bg-white  dark:bg-slate-800 rounded-md p-4">
                    <h4 class=" text-center font-semibold uppercase dark:text-white">Resumen:</h4>
                    <div class="">
                        {{-- ================= TOTALES ================= --}}
                        <div class="mt-4">
                            <div class="flex justify-between">
                                <p class=" text-base font-semibold dark:text-white uppercase mb-2">Total de articulos:</p>
                                <p class="dark:text-white">0</p>
                            </div>
                            <div class="flex justify-between">
                                <p class=" text-base font-semibold dark:text-white uppercase mb-2">Subtotal:</p>
                                <p class="dark:text-white"> ${{ number_format($documento->subtotal, 2) }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p class=" text-base font-semibold dark:text-white uppercase mb-2">Descuentos:</p>
                                <p class="dark:text-white"> ${{ number_format($documento->descuentos, 2) }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p class=" text-base font-semibold dark:text-white uppercase mb-2">IVA (16%):</p>
                                <p class="dark:text-white">${{ number_format($documento->impuestos, 2) }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p class="dark:text-white text-xl font-bold uppercase mb-2">Total: </p>
                                <p class="text-center text-2xl text-green-600 ">${{ number_format($documento->total, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ($documento->estatus == 1)
                        <div x-data @keydown.window.prevent.f10="$refs.btnActualizar.click()"
                            class="mt-4 flex items-center justify-center">
                            <a x-ref="btnActualizar"
                                href="{{ route('documentos.edit', ['sucursal' => $sucursal, 'documento' => $documento]) }}"
                                class=" px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                                ACTUALIZAR [F10]
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>

        <div id="emailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg w-full max-w-md p-6">
                <h2 class="text-lg font-semibold mb-4">
                    Enviar
                    {{ match ($documento->documento_modelo_id) {
                        1 => 'Cotización',
                        2 => 'Factura',
                        3 => 'Remisión',
                    } }}
                    por correo
                </h2>

                <form method="POST"
                    action="{{ route('documentos.enviarEmail', ['sucursal' => $sucursal->id, 'documento' => $documento->id]) }}">
                    @csrf

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico
                    </label>

                    <input type="email" name="email" value="{{ $documento->cliente->email1 }}" required
                        class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" onclick="closeEmailModal()" class="px-4 py-2 border rounded">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">
                            Enviar
                        </button>

                    </div>
                </form>
            </div>
        </div>

        <div id="cambioModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-xl w-full max-w-md p-6 shadow-lg">
                <h2 class="text-xl font-semibold mb-6 text-gray-800 text-center">
                    Cálcular cambio
                </h2>

                <div class="space-y-4">
                    <!-- Total -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Total a pagar
                        </label>
                        <input type="number" id="total" value="{{ $documento->total }}" readonly
                            class="w-full p-2 border rounded-md bg-gray-100 text-gray-700">
                    </div>

                    <!-- Pago -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pago del cliente
                        </label>
                        <input type="number" id="pago" step="0.01" placeholder="Ingrese el pago"
                            oninput="calcularCambio()"
                            class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Cambio -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Cambio
                        </label>
                        <input type="number" id="cambio" readonly
                            class="w-full p-2 border rounded-md bg-gray-100 text-gray-700">
                        <p id="mensaje" class="text-sm mt-1"></p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeCambioModal()"
                        class="px-4 py-2 rounded-md border-red-100 font-medium  text-white bg-red-600 hover:bg-red-600">
                        Cancelar
                    </button>
                </div>
            </div>

        </div>
    </x-app-layout>
    <script>
        function seleccionarImpresora() {
            Swal.fire({
                title: 'Selecciona una opción',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Ticket',
                denyButtonText: 'Carta',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open(
                        "{{ route('documentos.pdfTicket', ['sucursal' => $sucursal, 'documento' => $documento, 'mm' => 58]) }}",
                        '_blank');
                } else if (result.isDenied) {
                    window.open(
                        "{{ route('documentos.pdf', ['sucursal' => $sucursal, 'documento' => $documento]) }}",
                        '_blank');
                }
            });
        }

        function timbrar() {
            Swal.fire({
                title: '¿Esta seguro que requiere timbrar esta factura?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Si, timbrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Generando factura...',
                        text: 'Por favor espere mientras se convierte el documento.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('formTimbrar').submit();
                }
            });
        }

        function cancelar() {

            Swal.fire({
                title: 'Cancelar factura',

                html: `
            <div style="text-align:left">

                <p style="font-weight:bold; margin-bottom: 5px;">
                    Motivo de cancelación
                </p>

                <select id="swal-motivo" class="swal2-input">
                    <option value="">Seleccione...</option>
                    <option value="01">
                        01 - Comprobante emitido con errores con relación
                    </option>
                    <option value="02">
                        02 - Comprobante emitido con errores sin relación
                    </option>
                    <option value="03">
                        03 - No se llevó a cabo la operación
                    </option>
                    <option value="04">
                        04 - Operación nominativa relacionada en una factura global
                    </option>
                </select>

                <div id="uuid-container" style="display:none">

                    <p style="font-weight:bold; margin-top: 10px;">
                        UUID de sustitución
                    </p>

                    <input
                        id="swal-uuid"
                        class="swal2-input"
                        placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx">

                </div>

            </div>
        `,

                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Cancelar factura',
                cancelButtonText: 'Regresar',

                didOpen: () => {

                    const motivo = document.getElementById('swal-motivo');
                    const uuidContainer = document.getElementById('uuid-container');

                    motivo.addEventListener('change', function() {

                        if (this.value === '01' || this.value === '04') {
                            uuidContainer.style.display = 'block';
                        } else {
                            uuidContainer.style.display = 'none';
                        }

                    });

                },

                preConfirm: () => {

                    const motivo = document.getElementById('swal-motivo').value;
                    const uuid = document.getElementById('swal-uuid').value;

                    if (!motivo) {
                        Swal.showValidationMessage('Seleccione un motivo.');
                        return false;
                    }

                    if ((motivo === '01' || motivo === '04') && !uuid) {
                        Swal.showValidationMessage('Debe capturar el UUID de sustitución.');
                        return false;
                    }

                    return {
                        motivo,
                        uuid
                    };
                }

            }).then((result) => {

                if (!result.isConfirmed) {
                    return;
                }

                document.getElementById('motivo').value = result.value.motivo;
                document.getElementById('uuid_sustitucion').value = result.value.uuid;

                Swal.fire({
                    title: 'Cancelando factura...',
                    text: 'Por favor espere mientras se realiza la cancelación.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });

                document.getElementById('formCancelar').submit();

            });
        }

        function seleccionarConversion() {
            Swal.fire({
                title: 'Selecciona una opción',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Factura',
                denyButtonText: 'Remisión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '¿Deseas convertir a factura?',
                        text: "Al convertir a factura, el documento original se marcará como convertida y no podrá ser editada ni convertida nuevamente. Esta acción es irreversible.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, convertir a factura',
                        cancelButtonText: 'No, cancelar',
                        reverseButtons: true
                    }).then((confirmResult) => {
                        if (confirmResult.isConfirmed) {
                            Swal.fire({
                                title: 'Generando factura...',
                                text: 'Por favor espere mientras se convierte el documento.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            document.getElementById('formFactura').submit();
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire({
                        title: '¿Deseas convertir a remisión?',
                        text: "Al convertir a remisión, el documento original se marcará como convertida y no podrá ser editada ni convertida nuevamente. Esta acción es irreversible.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, convertir a remisión',
                        cancelButtonText: 'No, cancelar',
                        reverseButtons: true
                    }).then((confirmResult) => {
                        if (confirmResult.isConfirmed) {
                            Swal.fire({
                                title: 'Generando remisión...',
                                text: 'Por favor espere mientras se convierte el documento.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            document.getElementById('formRemision').submit();
                        }
                    });
                }
            });
        }

        function convertirAFactura() {
            Swal.fire({
                title: '¿Deseas convertir a factura?',
                text: "Al convertir a factura, el documento original se marcará como convertida y no podrá ser editada ni convertida nuevamente. Esta acción es irreversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, convertir a factura',
                cancelButtonText: 'No, cancelar',
                reverseButtons: true
            }).then((confirmResult) => {
                if (confirmResult.isConfirmed) {
                    // Animación para conversión
                    Swal.fire({
                        title: 'Generando factura...',
                        text: 'Por favor espere mientras se convierte el documento.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('formConversionFactura').submit();
                }
            });
        }

        function surtirRemision() {
            Swal.fire({
                title: '¿Deseas surtir la remisión?',
                text: "Al surtir la remisión, se marcará como surtida y no podrá ser editada ni convertida nuevamente. Esta acción es irreversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, surtir remisión',
                cancelButtonText: 'No, cancelar',
                reverseButtons: true
            }).then((confirmResult) => {
                if (confirmResult.isConfirmed) {
                    document.getElementById('formSurtirRemision').submit();
                }
            });
        }

        function openEmailModal() {
            document.getElementById('emailModal').classList.remove('hidden');
            document.getElementById('emailModal').classList.add('flex');
        }

        function closeEmailModal() {
            document.getElementById('emailModal').classList.add('hidden');
            document.getElementById('emailModal').classList.remove('flex');
        }

        function openCambioModal() {
            document.getElementById('cambioModal').classList.remove('hidden');
            document.getElementById('cambioModal').classList.add('flex');
        }

        function closeCambioModal() {
            document.getElementById('cambioModal').classList.add('hidden');
            document.getElementById('cambioModal').classList.remove('flex');
        }

        function calcularCambio() {
            const total = parseFloat(document.getElementById('total').value) || 0;
            const pago = parseFloat(document.getElementById('pago').value) || 0;
            const cambioInput = document.getElementById('cambio');
            const mensaje = document.getElementById('mensaje');

            const cambio = pago - total;

            if (pago === 0) {
                cambioInput.value = '';
                mensaje.textContent = '';
                return;
            }

            if (cambio < 0) {
                cambioInput.value = '';
                mensaje.textContent = '⚠️ El pago es insuficiente';
                mensaje.className = 'text-sm mt-1 text-red-600';
            } else {
                cambioInput.value = cambio.toFixed(2);
                mensaje.textContent = '✔️ Pago correcto';
                mensaje.className = 'text-sm mt-1 text-green-600';
            }
        }
    </script>
