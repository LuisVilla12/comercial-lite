@section('title', 'TRASPASO')

<x-app-layout>
    <x-slot name="header">
        <div class="md:flex md:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 text-center">
                Registrar un traspaso </h2>
            <label class="block text-lg font-medium mb-2 dark:text-white text-center">Fecha: {{ now()->format('d/m/Y') }}
            </label>
        </div>
    </x-slot>
    <form method="POST" action="{{ route('traspasos.store') }}" x-data="compraApp()" x-init="init();
    $watch('modalProducto', value => { if (value) { $nextTick(() => setTimeout(() => $refs.buscarProductoModal?.focus(), 50)) } })">

        @csrf
        <div x-data="{ tab: 'detalle' }">
            <div class="flex gap-4 border-b mt-4">
                <button type="button" @click="tab='detalle'"
                    :class="tab === 'detalle' ? 'border-b-2 border-blue-500' : ''"
                    class="block text-lg font-medium mb-2 dark:text-white">
                    [1] Movimientos
                </button>


            </div>
            <div x-show="tab === 'detalle'">
                <div class=" mx-auto py-6">
                    <div class="flex gap-5 justify-between">
                        <div class="w-full">
                            <label class="block text-lg font-medium mb-2 dark:text-white">Seleccionar almacen de origen:
                                *</label>
                            <select name="almacen_origen_id" x-model="almacen_origen_id" id="almacen_origen_id"
                                class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 mb-2">
                                <option value="" disabled selected>Seleccione</option>
                                @foreach ($almacenes as $almacen)
                                    <option  :disabled="almacen_destino_id == {{ $almacen->id }}" value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full">
                            <label class="block text-lg font-medium mb-2 dark:text-white">Seleccionar almacen destino:
                                *</label>
                            <select name="almacen_destino_id" x-model="almacen_destino_id" id="almacen_destino_id"
                                class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 mb-2">
                                <option value="" disabled selected>Seleccione</option>
                                @foreach ($almacenes as $almacen)
                                    <option :disabled="almacen_origen_id == {{ $almacen->id }}" value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ================= PRODUCTOS ================= --}}
                    <div class="mt-6">
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
                                                    class="border rounded p-1 w-full" placeholder="Buscar producto">

                                                <ul x-show="item.resultados.length"
                                                    @click.away="
                        item.resultados = [];
                        item.resultadoSeleccionado = -1;
                    "
                                                    class="absolute z-20 bg-white border rounded shadow w-full">
                                                    <template x-for="(p, i) in item.resultados" :key="p.id">
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
                                            <input type="number" x-model.number="item.costo"
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
                        <button type="button" @click="abrirModalProducto()"
                            @keydown.window.prevent.f9="abrirModalProducto()"
                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
                            ➕ Agregar producto [F9]
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
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="fecha" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="subtotal" x-model="total">
                    <input type="hidden" name="impuestos" :value="(total * 1.16) - total">
                    <input type="hidden" name="total" :value="(total * 1.16)">
                    <input type="hidden" name="estatus" :value="1">
                </div>
            </div>

            <div class="md:col-span-2 flex justify-between gap-3 mt-4">

                <a href="{{ route('traspasos.index') }}"
                    class="px-4 py-2 rounded-md border dark:bg-red border-red-300 bg-red-500 text-white hover:bg-red-500">
                    Cancelar
                </a>
                <div x-data @keydown.window.prevent.f10="$refs.btnGuardar.click()">
                    <button x-ref="btnGuardar" type="submit"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                        GUARDAR [F10]
                    </button>
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
                        placeholder="Buscar producto..." class="w-full border rounded p-2" autocomplete="off">

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
                            <div @mouseenter="modalProductoSeleccionado = i" @click="agregarProductoDesdeModal(p)"
                                class="p-3 border-b cursor-pointer"
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
    </form>
    </div>
    {{-- ================= ALPINE ================= --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function compraApp() {
            return {
                almacen_origen_id: '',
                almacen_destino_id:'',
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
