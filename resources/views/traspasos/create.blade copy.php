@section('title', 'Registrar traspaso')

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Registrar Traspaso
            </h2>
            <div class="">
                <label class="block text-lg font-medium mb-2 dark:text-white">Fecha: {{ now()->format('d/m/Y') }}
                </label>
            </div>
        </div>
    </x-slot>
    <form method="POST" action="{{ route('traspasos.store') }}">
        @csrf
        <div x-data="compraApp()" x-init="init()" class="max-w-7xl mx-auto py-6">
            <div class="mb-6">
                <div class="grid md:grid-cols-2 md:gap-6 ">
                    {{-- ================= Almacen origen ================= --}}
                    {{-- Almacén salida --}}
                    <div class="mb-4">
                        <label class="block text-lg font-medium mb-2 dark:text-white">
                            Almacén salida: *
                        </label>

                        <select name="almacen_origen_id" x-model="almacen_origen_id" @change="resetProductos()"
                            class="p-2 w-full rounded-md border-gray-300">
                            <option value="" disabled>Seleccione almacén de salida</option>
                            @foreach ($almacenes as $almacen)
                                <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                            @endforeach
                        </select>

                        @error('almacen_origen_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Almacén destino --}}
                    <div class="mb-6">
                        <label class="block text-lg font-medium mb-2 dark:text-white">
                            Almacén entrada: *
                        </label>

                        <select name="almacen_destino_id" x-model="almacen_destino_id"
                            class="p-2 w-full rounded-md border-gray-300">

                            <option value="" disabled>Seleccione almacén de entrada</option>
                            @foreach ($almacenes as $almacen)
                                <option value="{{ $almacen->id }}"
                                    :disabled="almacen_origen_id == {{ $almacen->id }}">
                                    {{ $almacen->nombre }}
                                </option>
                            @endforeach
                        </select>

                        @error('almacen_destino_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            {{-- ================= PRODUCTOS ================= --}}
            <div class="hidden md:block">
                <table class="w-full border bg-white shadow rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Código</th>
                            <th class="p-2">Producto</th>
                            <th class="p-2">Cantidad</th>
                            <th class="p-2">Existencia</th>
                            <th class="p-2">Restarían</th>
                            <th class="p-2"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="border-t">
                                <td class="p-2 text-center" x-text="item.codigo"></td>

                                <td class="p-2 relative">
                                    <input type="text" x-model="item.query"
                                        @input.debounce.300ms="buscarProducto(index)" class="border rounded p-1 w-full"
                                        placeholder="Buscar producto">

                                    <ul x-show="item.resultados.length"
                                        class="absolute z-10 bg-white border rounded shadow w-full">
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
                                    <input type="number" min="0" :name="`productos[${index}][cantidad]`"
                                        x-model.number="item.cantidad" @input="calcular"
                                        class="border rounded p-1 w-20 text-center">
                                </td>

                                <td class="p-2 text-center">
                                    <input type="number" disabled x-model.number="item.stock"
                                        class="border rounded p-1 w-24 text-center bg-gray-100 text-gray-700">
                                </td>

                                <td class="p-2 text-center font-semibold">
                                    <span x-text="item.stock - item.cantidad"></span>
                                </td>

                                <td class="p-2 text-center">
                                    <button type="button" @click="eliminarFila(index)"
                                        class="text-red-600 hover:text-red-800">
                                        ❌
                                    </button>
                                </td>

                                <!-- HIDDEN -->
                                <input type="hidden" :name="`productos[${index}][costo]`" x-model.number="item.costo">

                                <input type="hidden" :name="`productos[${index}][importe]`"
                                    :value="(item.cantidad * item.costo).toFixed(2)">
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="md:hidden space-y-4">
                <template x-for="(item, index) in items" :key="index">
                    <div class="border rounded-lg shadow bg-white p-4 space-y-3">

                        <div class="text-sm text-gray-500">
                            Código:
                            <span class="font-medium text-gray-800" x-text="item.codigo"></span>
                        </div>

                        <div class="relative">
                            <label class="text-sm text-gray-600">Producto</label>
                            <input type="text" x-model="item.query" @input.debounce.300ms="buscarProducto(index)"
                                class="border rounded p-2 w-full" placeholder="Buscar producto">

                            <ul x-show="item.resultados.length"
                                class="absolute z-10 bg-white border rounded shadow w-full">
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
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm text-gray-600">Cantidad</label>
                                <input type="number" min="0" :name="`productos[${index}][cantidad]`"
                                    x-model.number="item.cantidad" @input="calcular"
                                    class="border rounded p-2 w-full text-center">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">Existencia</label>
                                <input type="number" disabled x-model.number="item.stock"
                                    class="border rounded p-2 w-full text-center bg-gray-100">
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-2 border-t">
                            <span class="text-gray-600">Restarían</span>
                            <span class="text-lg font-bold" x-text="item.stock - item.cantidad"></span>
                        </div>

                        <button type="button" @click="eliminarFila(index)" class="text-red-600 text-lg">
                            ❌ Eliminar
                        </button>

                        <!-- HIDDEN -->
                        <input type="hidden" :name="`productos[${index}][costo]`" x-model.number="item.costo">

                        <input type="hidden" :name="`productos[${index}][importe]`"
                            :value="(item.cantidad * item.costo).toFixed(2)">
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
                                            </div>
                                        </div>

                                    </div>
                                </div>
                        </div>
                        </template>
                    </div>
                </div>
        </div>
            {{-- MODAL --}}
            {{-- ================= TOTAL ================= --}}
            {{-- <div class="flex justify-end text-xl font-bold mt-4 dark:text-white">
                Subtotal: $<span x-text="total.toFixed(2)"></span>
            </div>
            <div class="flex justify-end text-xl font-bold mt-4 dark:text-white">
                IVA: $<span x-text="(total*1.16-total).toFixed(2)"></span>
            </div>
            <div class="flex justify-end text-xl font-bold mt-4 dark:text-white">
                Total: $<span x-text="(total * 1.16).toFixed(2)"></span>
            </div> --}}


            {{-- -ENVIO DE DATOS --}}
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="fecha" value="{{ now()->format('Y-m-d') }}">
            {{-- <input type="hidden" name="subtotal" x-model="total"> --}}
            {{-- <input type="hidden" name="impuestos" :value="(total * 1.16) - total"> --}}
            {{-- <input type="hidden" name="total" :value="(total*1.16)"> --}}
            <input type="hidden" name="estatus" :value="1">


            <div class="md:col-span-2 flex justify-between gap-3 mt-4">
                <a href="{{ route('traspasos.index') }}"
                    class="px-4 py-2 rounded-md border-red-100 font-medium  text-white bg-red-600 hover:bg-red-600">
                    Cancelar
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                    Guardar
                </button>
            </div>
        </div>
    </form>

    {{-- ================= ALPINE ================= --}}
    <script>
        function compraApp() {
            return {
                almacen_origen_id: '',
                almacen_destino_id: '',

                items: [],
                total: 0,
                modalProducto: false,
                busquedaProducto: '',
                resultadosModal: [],
                init() {
                    this.items = []
                },
                resetProductos() {
                    this.items = []
                    // this.agregarFila()
                },

                agregarFila() {
                    this.items.push({
                        producto_id: null,
                        codigo: '',
                        query: '',
                        cantidad: 0,
                        costo: 0,
                        stock: 0,
                        resultados: []
                    })
                },

                eliminarFila(index) {
                    if (this.items.length === 1) return
                    this.items.splice(index, 1)
                    this.calcular()
                },

                async buscarProducto(index) {
                    if (!this.almacen_origen_id) return

                    const q = this.items[index].query
                    if (q.length < 2) return

                    const res = await fetch(
                        `/productos-existencias/buscar?q=${q}&almacen=${this.almacen_origen_id}`
                    )

                    this.items[index].resultados = await res.json()
                },

                seleccionarProducto(index, p) {
                    if (this.items.some(i => i.producto_id === p.id)) return

                    const item = this.items[index]
                    item.producto_id = p.id
                    item.codigo = p.codigo
                    item.query = p.nombre
                    item.costo = parseFloat(p.costo)
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
                            `/productos-existencias/buscar?q=${this.busquedaProducto}&almacen=${this.almacen_origen_id}`

                        );
                        this.resultadosModal = await res.json();
                        console.log(this.resultadosModal);
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
                            // costo: parseFloat(p.precioSeleccionado || p.costo),
                            costo: 0,
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
