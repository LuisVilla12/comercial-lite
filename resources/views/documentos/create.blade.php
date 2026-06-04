@section('title',
    match ($tipo) {
    '1' => 'Cotización',
    '2' => 'Facturación',
    '3' => 'Remisión',
    })
    <x-app-layout>
        <x-slot name="header">
            <div class="md:flex md:justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 text-center">
                    Registrar
                    {{ match ($tipo) {'1' => 'Cotización','2' => 'Factura','3' => 'Remisión'} .' ' .$sucursal->nombre }}
                </h2>
                <label class="block text-lg font-medium mb-2 dark:text-white text-center">Fecha: {{ now()->format('d/m/Y') }}
                </label>
            </div>
        </x-slot>
        <form method="POST" action="{{ route('documentos.store', $sucursal) }}" x-data="compraApp()"
            x-init="init()">
            @csrf
            <div x-data="{ tab: 'detalle' }">
                <div class="flex gap-4 border-b mt-4">
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
                    <div class=" mx-auto py-6">
                        <div class="mb-6">
                            <div class="md:flex justify-between">
                                <label class="block text-lg font-medium mb-2 dark:text-white">Cliente: *</label>
                            </div>

                            <input type="text" x-model="proveedorQuery" @input.debounce.300ms="buscarProveedor"
                                class="w-full border rounded p-2" placeholder="Buscar cliente">
                            @error('proveedor_id')
                                <p class="text-red-600 text-xs mt-1">Debes selecciona uno.</p>
                            @enderror

                            <ul x-show="proveedores.length"
                                class="border bg-white rounded shadow mt-1 max-h-48 overflow-y-auto">
                                <template x-for="p in proveedores" :key="p.id">
                                    <li @click="seleccionarProveedor(p)" class="p-2 hover:bg-gray-100 cursor-pointer"
                                        x-text="p.nombre">
                                    </li>
                                </template>
                            </ul>

                        </div>
                        {{-- ================= PRODUCTOS ================= --}}
                        <div class="mt-2">
                            <div class="hidden lg:block">
                                <table class="w-full border bg-white shadow rounded">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="p-2">Código</th>
                                            <th class="p-2">Producto</th>
                                            <th class="p-2">Cantidad</th>
                                            <th class="p-2">Precio</th>
                                            <th class="p-2">Existencia</th>
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
                                                        @input.debounce.300ms="buscarProducto(index)"
                                                        class="border rounded p-1 w-full" placeholder="Buscar producto">

                                                    <ul x-show="item.resultados.length" @click.away="item.resultados = []"
                                                        class="absolute z-20 bg-white border rounded shadow w-full">
                                                        <template x-for="p in item.resultados" :key="p.id">
                                                            <li @click="seleccionarProducto(index, p)"
                                                                class="p-2 hover:bg-gray-100 cursor-pointer">
                                                                <span x-text="p.nombre"></span>
                                                                <span class="text-sm text-gray-500">
                                                                    (<span x-text="p.codigo"></span>)
                                                                </span>
                                                            </li>
                                                        </template>
                                                    </ul>

                                                    <input type="hidden" :name="`productos[${index}][producto_id]`"
                                                        x-model="item.producto_id">
                                                </td>

                                                <td class="p-2 text-center">
                                                    <input type="number" min="1"
                                                        :name="`productos[${index}][cantidad]`"
                                                        x-model.number="item.cantidad" @input="calcular"
                                                        class="border rounded p-1 w-20 text-center">
                                                </td>

                                                <td class="p-2 text-center">
                                                    <input readonly type="number" :name="`productos[${index}][costo]`"
                                                        x-model.number="item.costo"
                                                        class="border rounded p-1 w-24 text-center bg-gray-100">
                                                </td>

                                                <td class="p-2 text-center">
                                                    <input disabled type="number" x-model.number="item.stock"
                                                        class="border rounded p-1 w-24 text-center bg-gray-100">
                                                </td>

                                                <td class="p-2 text-center font-semibold">
                                                    $<span x-text="(item.cantidad * item.costo).toFixed(2)"></span>
                                                    <input type="hidden" :name="`productos[${index}][importe]`"
                                                        :value="(item.cantidad * item.costo).toFixed(2)">
                                                </td>

                                                <td class="p-2 text-center">
                                                    <button type="button" @click="eliminarFila(index)"
                                                        class="text-red-600 hover:text-red-800">
                                                        ❌
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="lg:hidden space-y-4">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="bg-white border shadow rounded p-4 space-y-3">

                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Código</span>
                                            <span class="font-mono" x-text="item.codigo"></span>
                                        </div>

                                        <div class="relative">
                                            <label class="text-xs text-gray-500">Producto</label>
                                            <input type="text" x-model="item.query"
                                                @input.debounce.300ms="buscarProducto(index)"
                                                class="border rounded p-2 w-full" placeholder="Buscar producto">

                                            <ul x-show="item.resultados.length" @click.away="item.resultados = []"
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
                                                    :name="`productos[${index}][cantidad]`" x-model.number="item.cantidad"
                                                    @input="calcular" class="border rounded p-2 w-full text-center">
                                            </div>

                                            <div>
                                                <label class="text-xs text-gray-500">Precio</label>
                                                <input readonly type="number" x-model.number="item.costo"
                                                    class="border rounded p-2 w-full text-center bg-gray-100">
                                            </div>

                                            <div>
                                                <label class="text-xs text-gray-500">Existencia</label>
                                                <input disabled type="number" x-model.number="item.stock"
                                                    class="border rounded p-2 w-full text-center bg-gray-100">
                                            </div>

                                            <div>
                                                <label class="text-xs text-gray-500">Importe</label>
                                                <div class="border rounded p-2 text-center font-semibold bg-gray-50">
                                                    $<span x-text="(item.cantidad * item.costo).toFixed(2)"></span>
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
                                <p class="text-red-600 text-xs mt-1">{{ 'Debes seleccionar al menos un producto' }}</p>
                            @enderror
                            <button type="button" @click="modalProducto = true"
                                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
                                ➕ Agregar producto
                            </button>
                        </div>

                        {{-- ================= TOTAL ================= --}}
                        <div class="flex justify-end text-xl font-bold mt-4 dark:text-white">
                            Subtotal: $<span x-text="total.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-end text-xl font-bold mt-4 dark:text-white">
                            IVA: $<span x-text="(total*1.16-total).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-end text-xl font-bold mt-4 dark:text-white">
                            Total: $<span x-text="(total * 1.16).toFixed(2)"></span>
                        </div>

                        {{-- -ENVIO DE DATOS --}}
                        <input type="hidden" name="proveedor_id" :value="proveedor?.id">
                        <input type="hidden" name="almacen_id" value="{{ $sucursal->almacen_id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="fecha" value="{{ now()->format('Y-m-d') }}">
                        <input type="hidden" name="subtotal" x-model="total">
                        <input type="hidden" name="impuestos" :value="(total * 1.16) - total">
                        <input type="hidden" name="total" :value="(total * 1.16)">
                        <input type="hidden" name="estatus" :value="1">
                        <input type="hidden" name="tipo" value="{{ $tipo }}">
                        <input type="hidden" name="sucursal_id" value="{{ $sucursal->id }}">
                    </div>
                </div>
                <div x-show="tab === 'info'" x-cloak class="space-y-4">
                    <div class="md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4 lg:gap-4">
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
                            <input type="text" name="codigo_postal" placeholder="Codigo postal" x-model="proveedorCP"
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
                                    x-model="proveedorNumeroExterior"
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
                            <input type="text" name="colonia" placeholder="colonia" x-model="proveedorColonia"
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
                            <label for="metodo_pago" class="block text-md font-medium text-gray-700 dark:text-white mb-1">
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
                                    <option selected value="{{ $uso->clave }}">
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
                                        {{ $agente->codigo . ' - ' . $agente->nombre . ' ' . $agente->apellidoP }}</option>
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
                <div class="md:col-span-2 flex justify-between gap-3 mt-4">

                    <a href="{{ route(
                        match ($tipo) {
                            '1' => 'cotizaciones.index',
                            '2' => 'facturas.index',
                            '3' => 'remisiones.index',
                        },
                        ['sucursal' => $sucursal],
                    ) }}"
                        class="px-4 py-2 rounded-md border dark:bg-red border-red-300 bg-red-500 text-white hover:bg-red-500">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                        Guardar
                    </button>
                </div>

                {{-- MODAL --}}
                <div x-show="modalProducto" x-cloak
                    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

                    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6">

                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">
                                Buscar producto
                            </h2>

                            <button type="button" @click="modalProducto = false" class="text-red-600 text-xl">
                                ✕
                            </button>
                        </div>

                        <input type="text" x-model="busquedaProducto" @input.debounce.300ms="buscarProductoModal"
                            placeholder="Buscar producto..." class="w-full border rounded p-2">

                        <div class="mt-4 border rounded max-h-96 overflow-y-auto">

                            <template x-for="p in resultadosModal" :key="p.id">
                                <div @click="agregarProductoDesdeModal(p)"
                                    class="p-3 border-b hover:bg-gray-100 cursor-pointer">
                                    <div class="flex justify-between  items-center gap-4 mb-1">
                                        <div class="">
                                            <p class="font-semibold" x-text="p.nombre"></p>
                                            <div class="flex items-center gap-3">
                                                <p>Código: <span x-text="p.codigo" class=" font-bold"> </span></p>
                                                <p>Clave: <span x-text="p.clave" class=" font-bold"> </span></p>
                                                <p>Existencia: <span x-text="p.stock" class=" font-bold"> </span></p>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="mt-2">
                                                <label class=" font-bold text-gray-700 mb-1">
                                                    Precios:
                                                </label>
                                                <select x-model="p.precioSeleccionado" @click.stop  x-init="p.precioSeleccionado = p.costo"
                                                    class="border rounded p-1 text-sm">
                                                    <option :value="String(p.costo)">
                                                        1 - $<span x-text="p.costo"></span>
                                                    </option>
                                                    <option :value="String(p.costo2)" x-show="p.costo2 > 0">
                                                        2 - $<span x-text="p.costo2"></span>
                                                    </option>

                                                    @if (auth()->user()->tipo == 1)
                                                        <option :value="String(p.costo3)" x-show="p.costo3 > 0">
                                                            3 - $<span x-text="p.costo3"></span>
                                                        </option>

                                                        <option :value="String(p.costo4)" x-show="p.costo4 > 0">
                                                            4 - $<span x-text="p.costo4"></span>
                                                        </option>

                                                        <option :value="String(p.costo5)" x-show="p.costo5 > 0">
                                                            5 - $<span x-text="p.costo5"></span>
                                                        </option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </template>
                    </div>
                </div>
            </div>
        </form>
        </div>
        {{-- ================= ALPINE ================= --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
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

                    items: [],
                    total: 0,
                    modalProducto: false,
                    busquedaProducto: '',
                    resultadosModal: [],

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
                            stock: 0,
                            resultados: []
                        })
                    },

                    eliminarFila(index) {
                        if (this.items.length === 0) return
                        this.items.splice(index, 1)
                        this.calcular()
                    },

                    async buscarProveedor() {
                        if (this.proveedorQuery.length < 2) return
                        const res = await fetch(`/api/clientes/buscar?q=${this.proveedorQuery}`)
                        this.proveedores = await res.json()
                    },

                    seleccionarProveedor(p) {
                        if (!p.domicilios[0]) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Domicilio no encontrado',
                                text: 'El cliente seleccionado no tiene un domicilio registrado.'
                            });
                        } else {
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
                        }

                    },

                    async buscarProducto(index) {
                        const q = this.items[index].query
                        if (q.length < 2) return

                        const res = await fetch(`/api/productos-existencias/buscar?q=${q}&almacen=${ALMACEN_ID}}`)
                        this.items[index].resultados = await res.json()
                    },

                    seleccionarProducto(index, p) {
                        if (this.items.some(i => i.producto_id === p.id)) return
                        // console.log(p);
                        const item = this.items[index]
                        item.producto_id = p.id
                        item.codigo = p.codigo
                        item.query = p.nombre
                        item.costo = parseFloat(p.costo)
                        item.costo2 = parseFloat(p.costo2)
                        item.costo3 = parseFloat(p.costo3)
                        item.costo4 = parseFloat(p.costo4)
                        item.stock = p.stock
                        item.resultados = []

                        this.calcular()
                    },
                    async buscarProductoModal() {
                        if (this.busquedaProducto.length < 2) {
                            this.resultadosModal = [];
                            return;
                        }

                        const res = await fetch(
                            `/api/productos-existencias/buscar?q=${this.busquedaProducto}&almacen=${ALMACEN_ID}`
                        );

                        this.resultadosModal = await res.json();
                    },

                    agregarProductoDesdeModal(p) {

                        if (this.items.some(i => i.producto_id === p.id)) {
                            alert('El producto ya fue agregado');
                            return;
                        }

                        this.items.push({
                            producto_id: p.id,
                            codigo: p.codigo,
                            query: p.nombre,
                            cantidad: 1,
                            costo: parseFloat(p.precioSeleccionado),
                            stock: p.stock,
                            resultados: []
                        });

                        this.modalProducto = false;
                        this.busquedaProducto = '';
                        this.resultadosModal = [];

                        this.calcular();
                    },

                    calcular() {
                        this.total = this.items.reduce(
                            (t, i) => t + (i.cantidad * i.costo), 0
                        )
                    }
                }
            }
        </script>
    </x-app-layout>
