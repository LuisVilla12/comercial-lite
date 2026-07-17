@section('title',
    match ($tipo) {
    '1' => 'Cotización',
    '2' => 'Facturación',
    '3' => 'Remisión',
    })
    <x-app-layout>
        <div class="flex items-center mt-4 py-2 gap-3 mb-4 bg-white dark:bg-slate-800 w-full rounded-md">
            <a href="{{ route(
                match ($tipo) {
                    '1' => 'cotizaciones.index',
                    '2' => 'facturas.index',
                    '3' => 'remisiones.index',
                },
                ['sucursal' => $sucursal],
            ) }}"
                class="flex text-white  bg-red-600 border-1  rounded-lg p-4">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />Regresar
            </a>
            <div class="">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                    Registrar
                    {{ match ($tipo) {'1' => 'Cotización','2' => 'Factura','3' => 'Remisión'} . ' ' }}
                </h2>
                <p class="dark:text-white mt-2 font-semibold"> Sucursal: {{ $sucursal->nombre }}<span class="ml-6"> Fecha:
                        {{ now()->format('d/m/Y') }}</span></p>
            </div>
        </div>
        <form method="POST" action="{{ route('documentos.store', $sucursal) }}" id="formDocumento" x-data="compraApp()"
            x-init="init();
            $watch('modalProducto', value => { if (value) { $nextTick(() => setTimeout(() => $refs.buscarProductoModal?.focus(), 50)) } })">

            @csrf
            <div class="md:flex md:justify-between gap-2">
                {{-- VENTAS --}}
                <div class="md:w-9/12 px-1 ">
                    <div x-data="{ tab: 'detalle' }">
                        <div class="flex gap-4 border-b  bg-white dark:bg-slate-800 rounded-md p-2">
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
                        <div x-show="tab === 'detalle'">
                            <div class="mt-2">
                                <div class="bg-white dark:bg-slate-800 p-2 rounded-md">
                                    <div class="md:flex justify-between items-center">
                                        <label class="block text-lg font-medium dark:text-white">
                                            Cliente: </label>
                                    </div>
                                    <input type="text" x-model="proveedorQuery" autofocus
                                        @input.debounce.300ms="
        buscarProveedor();
        proveedorSeleccionado = -1;
    "
                                        @keydown.arrow-down.prevent="
        if (proveedores.length) {
            proveedorSeleccionado =
                proveedorSeleccionado < proveedores.length - 1
                    ? proveedorSeleccionado + 1
                    : 0;
        }
    "
                                        @keydown.arrow-up.prevent="
        if (proveedores.length) {
            proveedorSeleccionado =
                proveedorSeleccionado > 0
                    ? proveedorSeleccionado - 1
                    : proveedores.length - 1;
        }
    "
                                        @keydown.enter.prevent="
        if (proveedorSeleccionado >= 0) {
            seleccionarProveedor(proveedores[proveedorSeleccionado]);
        }
    "
                                        @keydown.escape="
        proveedores = [];
        proveedorSeleccionado = -1;
    "
                                        class="w-full border rounded mt-1 " placeholder="Buscar cliente">
                                    @error('proveedor_id')
                                        <p class="text-red-600 text-xs mt-1">
                                            Debes seleccionar uno.
                                        </p>
                                    @enderror

                                    <ul x-show="proveedores.length"
                                        class="border bg-white  rounded shadow mt-1 max-h-48 overflow-y-auto">
                                        <template x-for="(p, index) in proveedores" :key="p.id">
                                            <li @click="seleccionarProveedor(p)" class="p-2 cursor-pointer"
                                                :class="proveedorSeleccionado === index ?
                                                    'bg-blue-100' :
                                                    'hover:bg-gray-100'"
                                                x-text="p.nombre + ' (' + p.codigo + ')'">
                                            </li>
                                        </template>
                                    </ul>

                                </div>
                                {{-- ================= PRODUCTOS ================= --}}
                                <div class="bg-white  dark:bg-slate-800 p-2 rounded-md mt-4">
                                    <div class="flex justify-between items-center mb-2 mt-4 ">
                                        <label class="block text-lg font-medium dark:text-white ">Productos: </label>
                                        <button type="button" @click="abrirModalProducto()"
                                            @keydown.window.prevent.f9="abrirModalProducto()"
                                            class="px-4 py-2 bg-blue-600 text-white rounded flex items-center mr-2">
                                            <x-heroicon-o-plus class="w-5 h-5 mr-2" />Agregar [F9]
                                        </button>
                                    </div>
                                    <div class="">
                                        <div class="hidden lg:block">
                                            <table class="w-full border bg-white  shadow rounded">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="p-2">Código</th>
                                                        <th class="p-2">Producto</th>
                                                        <th class="p-2">Cantidad</th>
                                                        <th class="p-2">Precio</th>
                                                        <th class="p-2">Descuento %</th>
                                                        {{-- <th class="p-2">Existencia</th> --}}
                                                        <th class="p-2">Importe</th>
                                                        <th class="p-2"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="(item, index) in items" :key="index">
                                                        <tr class="border-t">
                                                            <td class="p-2 text-center" x-text="item.codigo"></td>

                                                            <td class="p-2 relative">
                                                                <input type="text" x-model="item.query"
                                                                    x-ref="`productoInput-${index}`"
                                                                    @input.debounce.300ms="
                        buscarProducto(index);
                        item.resultadoSeleccionado = -1;
                    "
                                                                    @keydown="
                        if ($event.key === 'ArrowDown' && item.resultados.length) {
                            $event.preventDefault();
                            item.resultadoSeleccionado =
                                item.resultadoSeleccionado < item.resultados.length - 1
                                    ? item.resultadoSeleccionado + 1
                                    : 0;
                        }

                        if ($event.key === 'ArrowUp' && item.resultados.length) {
                            $event.preventDefault();
                            item.resultadoSeleccionado =
                                item.resultadoSeleccionado > 0
                                    ? item.resultadoSeleccionado - 1
                                    : item.resultados.length - 1;
                        }

                        if ($event.key === 'Enter' && item.resultadoSeleccionado >= 0) {
                            $event.preventDefault();
                            seleccionarProducto(index, item.resultados[item.resultadoSeleccionado]);
                        }

                        if ($event.key === 'Escape') {
                            item.resultados = [];
                            item.resultadoSeleccionado = -1;
                        }
                    "
                                                                    class="border rounded p-1 w-full"
                                                                    placeholder="Buscar producto">

                                                                <ul x-show="item.resultados.length"
                                                                    @click.away="
                        item.resultados = [];
                        item.resultadoSeleccionado = -1;
                    "
                                                                    class="absolute z-20 bg-white border rounded shadow w-full">
                                                                    <template x-for="(p, i) in item.resultados"
                                                                        :key="p.id">
                                                                        <li @click="seleccionarProducto(index, p)"
                                                                            class="p-2 cursor-pointer"
                                                                            :class="item.resultadoSeleccionado === i ?
                                                                                'bg-blue-100' :
                                                                                'hover:bg-gray-100'">
                                                                            <span x-text="p.nombre"></span>
                                                                            <span class="text-sm text-gray-500">
                                                                                (<span x-text="p.codigo"></span>)
                                                                            </span>
                                                                        </li>
                                                                    </template>
                                                                </ul>

                                                                <input type="hidden"
                                                                    :name="`productos[${index}][producto_id]`"
                                                                    x-model="item.producto_id">
                                                            </td>

                                                            <td class="p-2 text-center">
                                                                <input type="number" min="1"
                                                                    :name="`productos[${index}][cantidad]`"
                                                                    x-model.number="item.cantidad" @input="calcular"
                                                                    class="border rounded p-1 w-20 text-center">
                                                            </td>

                                                            <td class="p-2 text-center">
                                                                <input readonly type="number"
                                                                    :name="`productos[${index}][costo]`"
                                                                    x-model.number="item.costo"
                                                                    class="border rounded p-1 w-24 text-center bg-gray-100">
                                                            </td>
                                                            <td class="p-2 text-center">
                                                                <input type="number"
                                                                    :name="`productos[${index}][descuento]`"
                                                                    x-model.number="item.descuento" min="0"
                                                                    max="100"
                                                                    class="border rounded p-1 w-24 text-center bg-gray-100">
                                                            </td>

                                                            {{-- <td class="p-2 text-center">
                                                    <input disabled type="number" x-model.number="item.stock"
                                                        class="border rounded p-1 w-24 text-center bg-gray-100">
                                                </td> --}}

                                                            {{-- <td class="p-2 text-center font-semibold">
                                                    $<span x-text="(item.cantidad * item.costo).toFixed(2)"></span>
                                                    <input type="hidden" :name="`productos[${index}][importe]`"
                                                        :value="(item.cantidad * item.costo).toFixed(2)">
                                                </td> --}}
                                                            <td class="p-2 text-center font-semibold">
                                                                $<span x-text="calcularImporte(item).toFixed(2)"></span>

                                                                <input type="hidden"
                                                                    :name="`productos[${index}][importe]`"
                                                                    :value="calcularImporte(item).toFixed(2)">
                                                            </td>
                                                            <td class="p-2 text-center">
                                                                <button type="button" @click="eliminarFila(index)"
                                                                    class="text-red-600 hover:text-red-800">
                                                                    <x-heroicon-o-trash class="w-5 h-5 " />
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="lg:hidden space-y-4">
                                            <template x-for="(item, index) in items" :key="index">
                                                <div class="bg-white  dark:bg-slate-800 border shadow rounded p-4 space-y-3">
                                                    <div class="flex justify-between text-sm">
                                                        <span class="text-gray-500">Código</span>
                                                        <span class="font-mono" x-text="item.codigo"></span>
                                                    </div>

                                                    <div class="relative">
                                                        <label class="text-xs text-gray-500">Producto</label>
                                                        <input type="text" x-model="item.query"
                                                            @input.debounce.300ms="buscarProducto(index)"
                                                            class="border rounded p-2 w-full"
                                                            placeholder="Buscar producto">

                                                        <ul x-show="item.resultados.length"
                                                            @click.away="item.resultados = []"
                                                            class="absolute z-20 bg-white border rounded shadow w-full">
                                                            <template x-for="p in item.resultados" :key="p.id">
                                                                <li @click="seleccionarProducto(index, p)"
                                                                    class="p-2 hover:bg-gray-100 cursor-pointer">
                                                                    <span x-text="p.nombre"></span>
                                                                    <span class="text-xs text-gray-500">
                                                                        ($<span x-text="p.codigo"></span>)
                                                                    </span>
                                                                </li>
                                                            </template>
                                                        </ul>

                                                        <input type="hidden" :name="`productos[${index}][producto_id]`"
                                                            x-model="item.producto_id">
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div>
                                                            <label class="text-xs text-gray-500">Cantidad</label>
                                                            <input type="number" min="1"
                                                                :name="`productos[${index}][cantidad]`"
                                                                x-model.number="item.cantidad" @input="calcular"
                                                                class="border rounded p-2 w-full text-center">
                                                        </div>

                                                        <div>
                                                            <label class="text-xs text-gray-500">Precio</label>
                                                            <input readonly type="number" x-model.number="item.costo"
                                                                class="border rounded p-2 w-full text-center bg-gray-100">
                                                        </div>

                                                        <div>
                                                            <label class="text-xs text-gray-500">Descuento %</label>
                                                            <input type="number" x-model.number="item.descuento"
                                                                class="border rounded p-2 w-full text-center bg-gray-100">
                                                        </div>

                                                        <div>
                                                            <label class="text-xs text-gray-500">Importe</label>
                                                            <div
                                                                class="border rounded p-2 text-center font-semibold bg-gray-50">
                                                                $<span
                                                                    x-text="(item.cantidad * item.costo).toFixed(2)"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex justify-end">
                                                        <button type="button" @click="eliminarFila(index)"
                                                            class="text-red-600 text-sm">
                                                            ❌ Eliminar
                                                        </button>
                                                    </div>

                                                </div>
                                            </template>
                                        </div>

                                        @error('productos')
                                            <p class="text-red-600 text-xs mt-1">{{ 'Debes seleccionar al menos un producto' }}
                                            </p>
                                        @enderror

                                    </div>


                                </div>
                            </div>
                        </div>
                        <div x-show="tab === 'info'" x-cloak class="space-y-4">
                            <div
                                class="md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4 lg:gap-4 bg-white  dark:bg-slate-800 rounded-md p-2 mt-2">
                                <div class="col-span-full">
                                    <label
                                        class="block text-xl mt-4 text-center md:text-left font-medium text-gray-700 dark:text-white">
                                        Datos del cliente: </span>
                                    </label>
                                </div>
                                <div class="mb-2">
                                    <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                                        RFC: <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="rfc" placeholder="RFC" x-model="proveedorRfc"
                                        class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    @error('rfc')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                                        Codigo postal: <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="codigo_postal" placeholder="Codigo postal"
                                        x-model="proveedorCP" autocomplete="off"
                                        class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    @error('codigo_postal')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-2">
                                    <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                                        Ciudad: <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="ciudad" placeholder="Ciudad" x-model="proveedorCiudad"
                                        autocomplete="off"
                                        class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    @error('ciudad')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                {{--  --}}
                                <div class="mb-2">
                                    <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                                        Calle: <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="calle" placeholder="calle" x-model="proveedorCalle"
                                        autocomplete="off"
                                        class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    @error('calle')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <div class="mb-2">
                                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                                            Número exterior: <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="numero_exterior" placeholder="Número exterior"
                                            x-model="proveedorNumeroExterior" autocomplete="off"
                                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        @error('numero_exterior')
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                                        Colonia: <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="colonia" placeholder="colonia"
                                        x-model="proveedorColonia" autocomplete="off"
                                        class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    @error('colonia')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-span-full">
                                    <label for="metodo_pago"
                                        class="block mt-4 text-center md:text-left text-xl font-medium text-gray-700 dark:text-white mb-1">
                                        Datos del pago: </span>
                                    </label>
                                </div>
                                {{-- Metodo de pago --}}
                                <div class="mb-2">
                                    <label for="metodo_pago"
                                        class="block text-md font-medium text-gray-700 dark:text-white mb-1">
                                        Metodo de pago: <span class="text-red-500">*</span>
                                    </label>
                                    <select name="metodo_pago" id="metodo_pago"
                                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="" disabled>Seleccione una opcion</option>
                                        <option value="PUE"selected>PUE Pago en una sola exhibición</option>
                                        <option value="PPD">PPD Pago en Parcialidades o Diferido</option>
                                    </select>
                                    @error('metodo_pago')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                {{-- Forma de pago --}}
                                <div class="mb-2">
                                    <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                                        Forma de pago:<span class="text-red-500">*</span>
                                    </label>
                                    <select name="forma_pago" id="forma_pago"
                                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="01" selected>01 Efectivo</option>
                                        <option value="03">03 Transferencia</option>
                                        <option value="04">04 Tarjeta de crédito</option>
                                        <option value="28">28 Tarjeta de débito</option>
                                        <option value="05">05 Monedero electrónico</option>
                                        <option value="02">02 Cheque nominativo</option>
                                    </select>
                                    @error('forma_pago')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                {{-- Uso de cfdi --}}
                                <div class="mb-2">
                                    <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                                        Uso de CFDI <span class="text-red-500">*</span>
                                    </label>
                                    <select name="uso_cfdi" id="uso_cfdi"
                                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="" disabled>Seleccione una opcion</option>
                                        @foreach ($usos as $uso)
                                            <option value="{{ $uso->clave }}">
                                                {{ $uso->clave . ' ' . $uso->descripcion }}</option>
                                        @endforeach
                                    </select>
                                    @error('uso_cfdi')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                @if ($tipo == '1')
                                    <div class="mb-2">
                                        <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                                            Vigencia del documento:<span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="vigencia"
                                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        @error('vigencia')
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                                <div class="mb-2">
                                    <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                                        Agente:<span class="text-red-500">*</span>
                                    </label>
                                    <select name="agente_id" id="agente_id"
                                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="" disabled>Seleccione un agente</option>
                                        @foreach ($agentes as $agente)
                                            <option value="{{ $agente->id }}">
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
                                    <textarea class="w-full" name="observaciones"></textarea>
                                    @error('observaciones')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- PAGAR --}}
                <div class="md:w-3/12 mt-4 md:mt-0">
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
                                    <p class="dark:text-white">$<span x-text="subtotal().toFixed(2)"></span></p>
                                </div>
                                <div class="flex justify-between">
                                    <p class=" text-base font-semibold dark:text-white uppercase mb-2">Descuentos:</p>
                                    <p class="dark:text-white">$<span x-text="totalDescuentos().toFixed(2)"></span></p>
                                </div>
                                <div class="flex justify-between">
                                    <p class=" text-base font-semibold dark:text-white uppercase mb-2">IVA (16%):</p>
                                    <p class="dark:text-white">$<span x-text="iva().toFixed(2)"></span></p>
                                </div>
                                <div class="flex justify-between">
                                    <p class="dark:text-white text-xl font-bold uppercase mb-2">Total: </p>
                                    <p class="text-center text-2xl text-green-600 ">$<span
                                            x-text="totalFinal().toFixed(2)"></span></p>
                                </div>

                                <div class="">
                                    <div x-data @keydown.window.prevent.f10="$refs.btnGuardar.click()" class="mt-4 flex items-center">
                                        <button x-ref="btnGuardar" id="btnGuardar" type="submit"
                                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  mx-auto rounded-md font-medium">
                                            GUARDAR [F10]
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- -ENVIO DE DATOS --}}
                        <input type="hidden" name="proveedor_id" :value="proveedor?.id">
                        <input type="hidden" name="almacen_id" value="{{ $sucursal->almacen_id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="fecha" value="{{ now()->format('Y-m-d') }}">
                        <input type="hidden" name="subtotal" x-model="subtotal">
                        <input type="hidden" name="descuentos" x-model="totalDescuentos">
                        <input type="hidden" name="impuestos" x-model="iva">
                        <input type="hidden" name="total" x-model="totalFinal">
                        <input type="hidden" name="estatus" :value="1">
                        <input type="hidden" name="tipo" value="{{ $tipo }}">
                        <input type="hidden" name="sucursal_id" value="{{ $sucursal->id }}">
                    </div>
                </div>
            </div>


            {{-- MODAL --}}
            <div x-show="modalProducto" @keydown.window.escape="cerrarModalProducto()" x-cloak
                class="fixed inset-0 bg-black/50 flex  items-center justify-center z-50">

                <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Buscar producto</h2>

                        <button type="button" @click="cerrarModalProducto()" class="text-red-600 text-xl">
                            ✕
                        </button>
                    </div>

                    <input type="text" x-ref="buscarProductoModal" x-model="busquedaProducto"
                        @input.debounce.300ms="buscarProductoModal(); modalProductoSeleccionado = -1"
                        @keydown="
            if ($event.key === 'ArrowDown' && resultadosModal.length) {
                $event.preventDefault();
                modalProductoSeleccionado =
                    modalProductoSeleccionado < resultadosModal.length - 1
                        ? modalProductoSeleccionado + 1
                        : 0;
            }

            if ($event.key === 'ArrowUp' && resultadosModal.length) {
                $event.preventDefault();
                modalProductoSeleccionado =
                    modalProductoSeleccionado > 0
                        ? modalProductoSeleccionado - 1
                        : resultadosModal.length - 1;
            }

            if ($event.key === 'Enter' && modalProductoSeleccionado >= 0 && resultadosModal[modalProductoSeleccionado]) {
                $event.preventDefault();
                agregarProductoDesdeModal(resultadosModal[modalProductoSeleccionado]);
            }

            if ($event.key === 'Escape') {
                resultadosModal = [];
                modalProductoSeleccionado = -1;
            }
        "
                        placeholder="Buscar producto..." class="w-full border rounded p-2" autocomplete="off">
                    <div class="mt-4 border rounded max-h-96 overflow-y-auto">
                        <template x-for="(p, i) in resultadosModal" :key="p.id">
                            <div @mouseenter="modalProductoSeleccionado = i" @click="agregarProductoDesdeModal(p)"
                                class="p-3 border-b cursor-pointer"
                                :class="modalProductoSeleccionado === i ? 'bg-blue-100' : 'hover:bg-gray-100'">
                                <div class="grid md:grid-cols-5 gap-4 mb-1">
                                    <div class="md:col-span-3 ">
                                        <p class="font-semibold" x-text="p.nombre"></p>

                                        <div class="flex flex-wrap items-center gap-3 text-sm">
                                            <p>
                                                Código:
                                                <span x-text="p.codigo" class="font-bold"></span>
                                            </p>

                                            <p>
                                                Clave:
                                                <span x-text="p.clave" class="font-bold"></span>
                                            </p>

                                            <p>
                                                Existencia:
                                                <span x-text="p.stock" class="font-bold"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Lista de precios -->
                                    <div @click.stop
                                        class="md:col-span-2 flex md:grid md:grid-cols-4 gap-4 items-center justify-center">
                                        <div class="md:col-span-2">
                                            <label class="font-bold text-gray-700 text-sm block mb-1">
                                                Precios:
                                            </label>

                                            <select x-model="p.precioSeleccionado" x-init="if (!p.precioSeleccionado) p.precioSeleccionado = String(p.costo)"
                                                class="border rounded p-1 text-sm w-full">
                                                <option :value="String(p.costo)">$<span x-text="p.costo"></span></option>
                                                <option x-show="Number(p.costo2) > 0" :value="String(p.costo2)">$<span x-text="p.costo2"></span></option>
                                                @if (auth()->user()->isAdmin())
                                                    <option x-show="Number(p.costo3) > 0" :value="String(p.costo3)"><span x-text="p.costo3"></span></option>
                                                    <option x-show="Number(p.costo4) > 0" :value="String(p.costo4)">$<span x-text="p.costo4"></span></option>
                                                    <option x-show="Number(p.costo5) > 0" :value="String(p.costo5)">$<span x-text="p.costo5"></span></option>
                                                @endif

                                            </select>
                                        </div>


                                        <!-- Cantidad y descuento -->

                                        <div>
                                            <label class="font-bold text-gray-700 text-sm block mb-1">
                                                Cantidad
                                            </label>

                                            <input type="number" min="1" step="1" x-model="p.cantidad"
                                                @click.stop class="border rounded p-1 text-sm w-full">
                                        </div>

                                        <div>
                                            <label class="font-bold text-gray-700 text-sm block mb-1">
                                                Desc. %
                                            </label>

                                            <input type="number" min="0" max="100" step="0.01"
                                                x-model="p.descuento" @click.stop
                                                class="border rounded p-1 text-sm w-full">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            </div>
        </form>
        {{-- ================= ALPINE ================= --}}
        <script>
            //DESHABILITAR BOTON DE GUARDAR AL DAR CLICK
            document.getElementById('formDocumento').addEventListener('submit', function() {
                const boton = document.getElementById('btnGuardar');
                boton.disabled = true;
                boton.textContent = 'Guardando...';
                Swal.fire({
                    title: 'Guardando documento ...',
                    text: 'Por favor espere mientras se guarda el documento.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });

            const ALMACEN_ID = {{ $sucursal->almacen_id }};

            function compraApp() {
                return {
                    proveedor: null,
                    proveedorQuery: '',
                    proveedorRfc: '',
                    proveedorCP: '',
                    proveedorCalle: '',
                    proveedorNumeroInterior: '',
                    proveedorNumeroExterior: '',
                    proveedorCiudad: '',
                    proveedorColonia: '',
                    proveedores: [],
                    proveedorSeleccionado: -1,

                    items: [],
                    total: 0,
                    modalProducto: false,
                    busquedaProducto: '',
                    resultadosModal: [],
                    modalProductoSeleccionado: -1,

                    abrirModalProducto() {
                        this.modalProducto = true;
                        this.busquedaProducto = '';
                        this.resultadosModal = [];
                        this.modalProductoSeleccionado = -1;

                        this.$nextTick(() => {
                            setTimeout(() => {
                                this.$refs.buscarProductoModal?.focus();
                            }, 100);
                        });
                    },

                    cerrarModalProducto() {
                        this.modalProducto = false;
                        this.resultadosModal = [];
                        this.modalProductoSeleccionado = -1;
                    },


                    init() {
                        this.items = []
                    },

                    agregarFila() {
                        this.items.push({
                            producto_id: null,
                            codigo: '',
                            query: '',
                            cantidad: 1,
                            costo: 0,
                            costo2: 0,
                            costo3: 0,
                            costo4: 0,
                            stock: 0,
                            iva: 0,
                            descuento: 0,
                            resultados: [],
                            resultadoSeleccionado: -1
                        })
                    },

                    eliminarFila(index) {
                        if (this.items.length === 0) return
                        this.items.splice(index, 1)
                        this.calcular()
                    },

                    async buscarProveedor() {
                        if (this.proveedorQuery.length < 2) {
                            this.proveedores = []
                            this.proveedorSeleccionado = -1
                            return
                        }

                        this.proveedores = []
                        this.proveedorSeleccionado = -1

                        const res = await fetch(`/clientes/buscar?q=${encodeURIComponent(this.proveedorQuery)}`)
                        this.proveedores = await res.json()
                    },

                    seleccionarProveedor(p) {
                        if (!p.domicilios || !p.domicilios[0]) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Domicilio no encontrado',
                                text: 'El cliente seleccionado no tiene un domicilio registrado.'
                            })
                            return
                        }

                        this.proveedor = p
                        this.proveedorQuery = p.nombre
                        this.proveedorRfc = p.rfc
                        this.proveedorCalle = p.domicilios[0].calle ?? ''
                        this.proveedorCP = p.domicilios[0].cp ?? ''
                        this.proveedorNumeroInterior = p.domicilios[0].numero_interior ?? ''
                        this.proveedorNumeroExterior = p.domicilios[0].numero_exterior ?? ''
                        this.proveedorCiudad = p.domicilios[0].ciudad ?? ''
                        this.proveedorColonia = p.domicilios[0].colonia ?? ''
                        this.proveedores = []
                        this.proveedorSeleccionado = -1
                    },

                    async buscarProducto(index) {
                        const q = this.items[index].query?.trim() || '';
                        if (q.length < 2) {
                            this.items[index].resultados = [];
                            this.items[index].resultadoSeleccionado = -1;
                            return;
                        }

                        const res = await fetch(
                            `/productos-existencias/buscar?q=${encodeURIComponent(q)}&almacen=${ALMACEN_ID}`);
                        this.items[index].resultados = await res.json();
                        this.items[index].resultadoSeleccionado = 0;
                    },

                    async buscarProductoModal() {
                        const q = this.busquedaProducto?.trim() || '';
                        if (q.length < 2) {
                            this.resultadosModal = [];
                            this.modalProductoSeleccionado = -1;
                            return;
                        }

                        const res = await fetch(
                            `/productos-existencias/buscar?q=${encodeURIComponent(q)}&almacen=${ALMACEN_ID}`);
                        const productos = await res.json();
                        this.resultadosModal = productos.map(p => ({
                            ...p,
                            cantidad: 1,
                            descuento: 0,
                            precioSeleccionado: String(p.costo)
                        }));
                        this.modalProductoSeleccionado = this.resultadosModal.length ? 0 : -1;
                    },

                    agregarProductoDesdeModal(p) {
                        if (this.items.some(i => i.producto_id === p.id)) {
                            alert('El producto ya fue agregado');
                            return;
                        }
                        console.log(p)
                        this.items.push({
                            producto_id: p.id,
                            codigo: p.codigo,
                            query: p.nombre,
                            cantidad: Number(p.cantidad) || 1,
                            descuento: Number(p.descuento) || 0,

                            // Precio seleccionado en el combo
                            costo: (parseFloat(p.precioSeleccionado ?? p.costo)) || 0,
                            costo2: parseFloat(p.costo2) || 0,
                            costo3: parseFloat(p.costo3) || 0,
                            costo4: parseFloat(p.costo4) || 0,
                            costo5: parseFloat(p.costo5) || 0,

                            iva: Number(p.iva ?? 16),
                            stock: p.stock,
                            resultados: [],
                            resultadoSeleccionado: -1
                        });

                        this.calcular();

                        this.modalProducto = false;
                        this.busquedaProducto = '';
                        this.resultadosModal = [];
                        this.modalProductoSeleccionado = -1;
                    },


                    calcular() {
                        this.total = this.items.reduce((t, i) => {

                            const subtotal =
                                Number(i.cantidad) * Number(i.costo);

                            const descuento =
                                subtotal * (Number(i.descuento || 0) / 100);

                            return t + (subtotal - descuento);

                        }, 0);
                    },
                    calcularIVA(item) {
                        const subtotal =
                            Number(item.cantidad) * Number(item.costo);

                        const descuento =
                            subtotal * (Number(item.descuento || 0) / 100);

                        const base = subtotal - descuento;

                        return base * (Number(item.iva || 0) / 100);
                    },
                    calcularImporte(item) {
                        const subtotal = Number(item.cantidad) * Number(item.costo);
                        const descuento = subtotal * (Number(item.descuento || 0) / 100);

                        return subtotal - descuento;
                    },
                    subtotal() {
                        return this.items.reduce((total, item) => {
                            return total + (Number(item.cantidad) * Number(item.costo));
                        }, 0);
                    },

                    totalDescuentos() {
                        return this.items.reduce((total, item) => {
                            const subtotalLinea =
                                Number(item.cantidad) * Number(item.costo);

                            return total + (
                                subtotalLinea * (Number(item.descuento || 0) / 100)
                            );
                        }, 0);
                    },

                    subtotalConDescuento() {
                        return this.subtotal() - this.totalDescuentos();
                    },

                    iva() {
                        return this.items.reduce((total, item) => {
                            return total + this.calcularIVA(item);
                        }, 0);
                    },

                    totalFinal() {
                        return this.subtotalConDescuento() + this.iva();
                    },
                }
            }
        </script>
    </x-app-layout>
