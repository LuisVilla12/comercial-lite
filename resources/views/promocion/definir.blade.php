@section('title','Registrar productos')
    <x-app-layout>
        <x-slot name="header">
            <div class="md:flex md:justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 text-center">
                    Registrar productos en promocion
                </h2>
            </div>
        </x-slot>
        <form method="POST" action="{{ route('promociones.definir') }}" id="formDocumento" x-data="compraApp()"
            x-init="init();
            $watch('modalProducto', value => { if (value) { $nextTick(() => setTimeout(() => $refs.buscarProductoModal?.focus(), 50)) } })">
            @csrf
            <div>
                <div class="mt-4">
                        {{-- ================= PRODUCTOS ================= --}}
                        <div class="mt-2">
                            <div class="hidden lg:block">
                                <table class="w-full border bg-white shadow rounded">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="p-2">Código</th>
                                            <th class="p-2">Producto</th>
                                            <th class="p-2">Precio</th>
                                            {{-- <th class="p-2">Descuento %</th> --}}
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
                                                    <input readonly type="number" :name="`productos[${index}][costo]`"
                                                        x-model.number="item.costo"
                                                        class="border rounded p-1 w-24 text-center bg-gray-100">
                                                </td>
                                                {{-- <td class="p-2 text-center">
                                                    <input type="number" :name="`productos[${index}][descuento]`"
                                                        x-model.number="item.descuento" min="0" max="100"
                                                        class="border rounded p-1 w-24 text-center bg-gray-100">
                                                </td> --}}

                                                {{-- <td class="p-2 text-center">
                                                    <input disabled type="number" x-model.number="item.stock"
                                                        class="border rounded p-1 w-24 text-center bg-gray-100">
                                                </td> --}}

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
                                                <label class="text-xs text-gray-500">Descuento %</label>
                                                <input type="number" x-model.number="item.descuento"
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

                        {{-- -ENVIO DE DATOS --}}

                    </div>
                </div>

                <div class="md:col-span-2 flex justify-between gap-3 mt-4">

                    <a href=""
                        class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                        <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" /> Regresar
                    </a>
                    <div x-data @keydown.window.prevent.f10="$refs.btnGuardar.click()">
                        <button x-ref="btnGuardar" id="btnGuardar" type="submit"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                            GUARDAR [F10]
                        </button>
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

                                                    <option :value="String(p.costo)">
                                                        1.- - $<span x-text="p.costo"></span>
                                                    </option>

                                                    <option x-show="Number(p.costo2) > 0" :value="String(p.costo2)">
                                                        2.- $<span x-text="p.costo2"></span>
                                                    </option>

                                                    @if (auth()->user()->isAdmin())
                                                        <option x-show="Number(p.costo3) > 0" :value="String(p.costo3)">
                                                            3.- $<span x-text="p.costo3"></span>
                                                        </option>

                                                        <option x-show="Number(p.costo4) > 0" :value="String(p.costo4)">
                                                            4.- $<span x-text="p.costo4"></span>
                                                        </option>

                                                        <option x-show="Number(p.costo5) > 0" :value="String(p.costo5)">
                                                            5.- $<span x-text="p.costo5"></span>
                                                        </option>
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
        </div>
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

            const ALMACEN_ID = 1;

            function compraApp() {
                return {
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
                            costo: parseFloat(p.precioSeleccionado ?? p.costo) || 0,
                            costo2: parseFloat(p.costo2) || 0,
                            costo3: parseFloat(p.costo3) || 0,
                            costo4: parseFloat(p.costo4) || 0,
                            costo5: parseFloat(p.costo5) || 0,

                            iva: Number(p.iva ?? 16),
                            stock: p.stock,
                            resultados: [],
                            resultadoSeleccionado: -1
                        });


                        this.modalProducto = false;
                        this.busquedaProducto = '';
                        this.resultadosModal = [];
                        this.modalProductoSeleccionado = -1;
                    },

                }
            }
        </script>
    </x-app-layout>
