@section('title', 'Compras')

<x-app-layout>
    <div class="flex items-center mt-4 py-2 gap-3 mb-4 bg-white dark:bg-slate-800 w-full rounded-md">
        <a href="{{ route('compras.index') }}" class="flex text-white  bg-red-600 border-1  rounded-lg p-4">
            <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />Regresar
        </a>
        <div class="">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Registrar una compra
            </h2>
            <p class="dark:text-white mt-2 font-semibold"> Fecha:
                {{ now()->format('d/m/Y') }}</span></p>
        </div>
    </div>
    <form method="POST" id="formCompras" action="{{ route('compras.store') }}" x-data="compraApp()"
        x-init="init();
        $watch('modalProducto', value => { if (value) { $nextTick(() => setTimeout(() => $refs.buscarProductoModal?.focus(), 50)) } })">
        @csrf
        <div class="md:flex md:justify-between gap-2">
            <div class="md:w-9/12 px-1">
                <div x-data="{ tab: 'detalle' }" class=" ">
                    <div class="flex gap-4 border-b bg-white dark:bg-slate-800 p-1" >
                        <button type="button" @click="tab='detalle'"
                            :class="tab === 'detalle' ? 'border-b-2 border-blue-500' : ''"
                            class="block text-lg font-medium mb-2 dark:text-white">
                            [1] Movimientos
                        </button>


                    </div>
                    <div x-show="tab === 'detalle'">
                        <div class=" mx-auto  " >
                            <div class="md:flex gap-5 md:justify-between bg-white dark:bg-slate-800 mt-2  p-2">
                                <div class="w-full">
                                    <div class="md:flex justify-between ">
                                        <label class="block text-lg font-medium mb-2 dark:text-white">Proveedor:
                                            *</label>
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
                                        class="w-full border rounded p-2" placeholder="Buscar proveedor">

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
                                <div class="w-full mt-4 md:mt-0">
                                    <label class="block text-lg font-medium mb-2 dark:text-white">Seleccionar almacen:
                                        *</label>
                                    <select name="almacen_id" x-model="almacen_origen_id" id="almacen_id"
                                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 mb-2">
                                        <option value="" disabled selected>Seleccione</option>
                                        @foreach ($almacenes as $almacen)
                                            <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- ================= PRODUCTOS ================= --}}
                            <div class="bg-white dark:bg-slate-800 mt-2  p-2">
                            <div class="flex justify-between items-center mb-2  ">
                                <label class="block text-lg font-medium dark:text-white ">Productos: </label>
                                <button type="button" @click="abrirModalProducto()"
                                    @keydown.window.prevent.f9="abrirModalProducto()"
                                    class="px-4 py-2 bg-blue-600 text-white rounded flex items-center mr-2">
                                    <x-heroicon-o-plus class="w-5 h-5 mr-2" />Agregar [F9]
                                </button>
                            </div>
                            <div class="mt-2">
                                <div class="hidden lg:block">
                                    <table class="w-full border bg-white shadow rounded">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="p-2">Código</th>
                                                <th class="p-2">Producto</th>
                                                <th class="p-2">Cantidad</th>
                                                <th class="p-2">Precio</th>
                                                <th class="p-2">Importe</th>
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
                                                                    :class="item.resultadoSeleccionado === i ? 'bg-blue-100' :
                                                                        'hover:bg-gray-100'">
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
                                                        <input type="number" :name="`productos[${index}][costo]`"
                                                            @input="calcular" x-model.number="item.costo"
                                                            class="border rounded p-1 w-24 text-center bg-gray-100">
                                                    </td>

                                                    {{-- <td class="p-2 text-center">
                                                    <input disabled type="number" x-model.number="item.stock"
                                                        class="border rounded p-1 w-24 text-center bg-gray-100">
                                                </td> --}}

                                                    <td class="p-2 text-center font-semibold">
                                                        $<span x-text="(item.cantidad * item.costo).toFixed(2)"></span>
                                                        <input type="hidden" :name="`productos[${index}][importe]`"
                                                            :value="(item.cantidad * item.costo).toFixed(2)">
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
                                                        :name="`productos[${index}][cantidad]`"
                                                        x-model.number="item.cantidad" @input="calcular"
                                                        class="border rounded p-2 w-full text-center">
                                                </div>

                                                <div>
                                                    <label class="text-xs text-gray-500">Precio</label>
                                                    <input type="number" x-model.number="item.costo"
                                                        class="border rounded p-2 w-full text-center bg-gray-100">
                                                </div>

                                                <div>
                                                    <label class="text-xs text-gray-500">Importe</label>
                                                    <div
                                                        class="border rounded p-2 text-center font-semibold bg-gray-50">
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

                            </div>

                            </div>


                            {{-- ================= TOTAL ================= --}}


                            {{-- -ENVIO DE DATOS --}}
                            <input type="hidden" name="proveedor_id" :value="proveedor?.id">
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="fecha" value="{{ now()->format('Y-m-d') }}">
                            <input type="hidden" name="subtotal" x-model="total">
                            <input type="hidden" name="impuestos" :value="(total * 1.16) - total">
                            <input type="hidden" name="total" :value="(total * 1.16)">
                            <input type="hidden" name="estatus" :value="1">
                        </div>
                    </div>

                    {{-- MODAL --}}
                    <div x-show="modalProducto" @keydown.window.escape="cerrarModalProducto()" x-cloak
                        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

                        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6">
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
                                placeholder="Buscar producto..." class="w-full border rounded p-2"
                                autocomplete="off">

                            <div class="mt-4 border rounded max-h-96 overflow-y-auto">
                                {{-- <template x-for="(p, i) in resultadosModal" :key="p.id">
                                <div @mouseenter="modalProductoSeleccionado = i" @click="agregarProductoDesdeModal(p)"
                                    class="p-3 border-b cursor-pointer"
                                    :class="modalProductoSeleccionado === i ? 'bg-blue-100' : 'hover:bg-gray-100'">
                                    <div class="flex justify-between items-center gap-4 mb-1">
                                        <div>
                                            <p class="font-semibold" x-text="p.nombre"></p>
                                            <div class="flex items-center gap-3">
                                                <p>Código: <span x-text="p.codigo" class="font-bold"></span></p>
                                                <p>Clave: <span x-text="p.clave" class="font-bold"></span></p>
                                                <p>Existencia: <span x-text="p.stock" class="font-bold"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template> --}}
                                <template x-for="(p, i) in resultadosModal" :key="p.id">
                                    <div @mouseenter="modalProductoSeleccionado = i"
                                        @click="agregarProductoDesdeModal(p)" class="p-3 border-b cursor-pointer"
                                        :class="modalProductoSeleccionado === i ? 'bg-blue-100' : 'hover:bg-gray-100'">
                                        <div class="flex justify-between items-center gap-4 mb-1">
                                            <div>
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
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- TOTAL --}}
            <div class="md:w-3/12 mt-4 md:mt-0 bg-white dark:bg-slate-800">
                <div class="bg-white  dark:bg-slate-800 rounded-md p-4">
                    <h4 class=" text-center font-semibold uppercase dark:text-white">Resumen:</h4>
                    <div class="">
                        {{-- ================= TOTALES ================= --}}
                        <div class="mt-4">
                            <div class="flex justify-between">
                                <p class=" text-base font-semibold dark:text-white uppercase mb-2">Total de articulos:
                                </p>
                                <p class="dark:text-white">0</p>
                            </div>
                            <div class="flex justify-between">
                                <p class=" text-base font-semibold dark:text-white uppercase mb-2">Subtotal:</p>
                                <p class="dark:text-white">$<span x-text="total.toFixed(2)"></span></p>
                            </div>

                            <div class="flex justify-between">
                                <p class=" text-base font-semibold dark:text-white uppercase mb-2">IVA (16%):</p>
                                <p class="dark:text-white">$<span x-text="(total*1.16-total).toFixed(2)"></span></p>
                            </div>
                            <div class="flex justify-between">
                                <p class="dark:text-white text-xl font-bold uppercase mb-2">Total: </p>
                                <p class="text-center text-2xl text-green-600 ">$<span
                                        x-text="(total * 1.16).toFixed(2)"></span></p>
                            </div>

                            <div class="">
                                <div x-data @keydown.window.prevent.f10="$refs.btnGuardar.click()"
                                    class="mt-4 flex items-center">
                                    <button x-ref="btnGuardar" id="btnGuardar" type="submit"
                                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  mx-auto rounded-md font-medium">
                                        GUARDAR [F10]
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- ================= ALPINE ================= --}}
    <script>
        // VALIDAR GUARDAR UNA VEZ
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formCompras');
            const btn = document.getElementById('btnSave');
            if (!form || !btn) return;
            form.addEventListener('submit', function() {
                btn.disabled = true;
                btn.innerText = 'Guardando...';
            });
        });

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
                almacen_origen_id: '',
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

                    const res = await fetch(`/proveedores/buscar?q=${encodeURIComponent(this.proveedorQuery)}`)
                    this.proveedores = await res.json()
                },

                seleccionarProveedor(p) {
                    this.proveedor = p
                    this.proveedorQuery = p.nombre
                    this.proveedorRfc = p.rfc
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
                        // `/productos-existencias/buscar?q=${q}&almacen=${this.almacen_origen_id}`
                        `/productos-existencias/buscar?q=${encodeURIComponent(q)}&almacen=${this.almacen_origen_id}`
                    );
                    // `/productos-existencias/buscar?q=${encodeURIComponent(q)}&almacen=${ALMACEN_ID}`);
                    this.items[index].resultados = await res.json();
                    this.items[index].resultadoSeleccionado = 0;
                },

                seleccionarProducto(index, p) {
                    if (this.items.some(i => i.producto_id === p.id)) return

                    const item = this.items[index]

                    item.producto_id = p.id
                    item.codigo = p.codigo
                    item.query = p.nombre
                    item.costo = parseFloat(p.costo) || 0
                    item.costo2 = parseFloat(p.costo2) || 0
                    item.costo3 = parseFloat(p.costo3) || 0
                    item.costo4 = parseFloat(p.costo4) || 0
                    item.stock = p.stock
                    item.resultados = []
                    item.resultadoSeleccionado = -1

                    this.calcular()
                },

                async buscarProductoModal() {
                    const q = this.busquedaProducto?.trim() || '';

                    if (q.length < 2) {
                        this.resultadosModal = [];
                        this.modalProductoSeleccionado = -1;
                        return;
                    }

                    const res = await fetch(
                        // `/buscar/productos?q=${encodeURIComponent(q)}`);
                        `/productos-existencias/buscar?q=${encodeURIComponent(q)}&almacen=${this.almacen_origen_id}`
                    );
                    // `/productos-existencias/buscar?q=${q}&almacen=${this.almacen_origen_id}`
                    this.resultadosModal = await res.json();
                    this.modalProductoSeleccionado = this.resultadosModal.length ? 0 : -1;
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

                        // Precio seleccionado en el combo
                        costo: 0,

                        costo2: parseFloat(p.costo2) || 0,
                        costo3: parseFloat(p.costo3) || 0,
                        costo4: parseFloat(p.costo4) || 0,
                        costo5: parseFloat(p.costo5) || 0,

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
                    this.total = this.items.reduce(
                        (t, i) => t + (Number(i.cantidad) * Number(i.costo)),
                        0
                    )
                }
            }
        }
    </script>
</x-app-layout>
