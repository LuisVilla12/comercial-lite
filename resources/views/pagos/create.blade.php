@section('title', 'Registrar pago')
<x-app-layout>
    <x-slot name="header">
        <div class="md:flex md:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 text-center">
                Registrar Pago
            </h2>
            <label class="block text-lg font-medium mb-2 dark:text-white text-center">Fecha: {{ now()->format('d/m/Y') }}
            </label>
        </div>
    </x-slot>
    <form method="POST" action="{{ route('pagos.store') }}" x-data="compraApp()" x-init="init();">
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
                            class="w-full border rounded p-2" placeholder="Buscar cliente">

                        @error('proveedor_id')
                            <p class="text-red-600 text-xs mt-1">
                                Debes seleccionar uno.
                            </p>
                        @enderror

                        <ul x-show="proveedores.length"
                            class="border bg-white rounded shadow mt-1 max-h-48 overflow-y-auto">
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
                    {{-- ================= FACTURAS ================= --}}
                    <div>
                        <!-- Cliente -->

                        <div class="col-span-full">
                            <label class="block text-lg font-medium mb-2 dark:text-white">
                                Facturas pendientes:
                            </label>

                            <div class="shadow-md overflow-x-auto rounded-lg">
                                <table class="w-full border bg-white shadow rounded">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="p-2">Folio</th>
                                            <th class="p-2">Fecha</th>
                                            <th class="p-2">Total</th>
                                            <th class="p-2">Saldo pendiente</th>
                                            <th class="p-2">Monto a pagar</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <template x-for="factura in facturas" :key="factura.id">
                                            <tr class="border-t">

                                                <td class="p-2" x-text="factura.serie + factura.folio"></td>

                                                <td class="p-2" x-text="factura.fecha"></td>

                                                <td class="p-2 text-right" x-text="Number(factura.total).toFixed(2)">
                                                </td>

                                                <td class="p-2 text-right"
                                                    x-text="Number(factura.saldo_pendiente).toFixed(2)">
                                                </td>

                                                <td class="p-2">
                                                    <input type="number" step="0.01" min="0"
                                                        :max="factura.saldo_pendiente" x-model="factura.monto"
                                                        class="w-full border rounded p-1 text-right">
                                                </td>

                                            </tr>
                                        </template>

                                        <tr x-show="facturas.length == 0">
                                            <td colspan="5" class="text-center p-4 text-gray-500">
                                                No hay facturas pendientes.
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>


                    {{-- -ENVIO DE DATOS --}}
                    {{-- MANDA LOS DATOS DE LAS FACTURAS QUE ABONO --}}
                    <input type="hidden" name="facturas":value="JSON.stringify(facturas.filter(f => Number(f.monto) > 0))">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="fecha" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="estatus" :value="1">
                    <input type="hidden" name="cliente_id" :value="proveedor?.id">

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

                </div>
            </div>
            <div class="md:col-span-2 flex justify-between gap-3 mt-4">

                <a href="{{ route('agentes.create') }}"
                    class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                    <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" /> Regresar
                </a>
                <div x-data @keydown.window.prevent.f10="$refs.btnGuardar.click()">
                    <button x-ref="btnGuardar" type="submit"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                        GUARDAR [F10]
                    </button>
                </div>

            </div>


        </div>
    </form>
    </div>
    {{-- ================= ALPINE ================= --}}
    <script>
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
                cliente_id: '',
                facturas: [],

                init() {
                    // Inicializar cualquier dato necesario al cargar la página
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
                    //SELECIONA EL CLIENTE
                    this.cliente_id = p.id
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
                    //BUSCAR LAS FACTURAS
                    this.cargarFacturas()

                },

                async cargarFacturas() {
                    if (!this.cliente_id) {
                        this.facturas = [];
                        return;
                    }

                    const response = await fetch(
                        `/buscar/facturas/pendientes?cliente_id=${this.cliente_id}`
                    );
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        console.error('Error fetching facturas:', response.statusText);
                        this.facturas = [];
                        return;
                    }
                    this.facturas = await response.json();

                    this.facturas.forEach(f => {
                        f.monto = 0;
                    });

                },
                get totalPago() {

                    return this.facturas.reduce((total, factura) => {
                        return total + Number(factura.monto || 0);
                    }, 0);

                },
            }
        }
    </script>
</x-app-layout>
