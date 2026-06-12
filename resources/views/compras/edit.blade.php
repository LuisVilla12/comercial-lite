<x-app-layout>
    <form method="POST" action="{{ route('compras.update', $compra) }}">
        @csrf
        @method('PUT')
        <div x-data="compraEdit(@js($compra->toArray()))" x-init="init()" class="max-w-7xl mx-auto py-6">
            {{-- ================= PROVEEDOR ================= --}}
            <div class="mb-6">
                <div class="flex justify-end">
                    <div class="">
                        <label class="block text-lg font-medium mb-2 dark:text-white">Fecha:
                            {{ now()->format('d/m/Y') }} </label>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 md:gap-4">
                    <div>
                        <label class="block text-lg font-medium mb-2 dark:text-white">Proveedor: *</label>
                        <input type="text" x-model="proveedorQuery" @input.debounce.300ms="buscarProveedor"
                            class="w-full border rounded p-2" placeholder="Buscar proveedor">
                        @error('proveedor_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
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
                    {{-- Almacen --}}
                    <div>
                        <label class="block text-lg font-medium mb-2 dark:text-white">Seleccionar almacen: *</label>
                        <select name="almacen_id" id="almacen_id"
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 mb-2">
                            <option value="" disabled selected>Seleccione</option>
                            @foreach ($almacenes as $almacen)
                                <option value="{{ $almacen->id }} " @selected($compra->almacen_id == $almacen->id)>{{ $almacen->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            {{-- ================= PRODUCTOS ================= --}}
            {{-- <div class="">
                <table class="w-full border bg-white shadow rounded p-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Código</th>
                            <th class="p-2">Producto</th>
                            <th class="p-2">Cantidad</th>
                            <th class="p-2">Precio</th>
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
                                        @input.debounce.300ms="buscarProducto(index)" class="border rounded p-1 w-full">
                                    <ul x-show="item.resultados.length"
                                        class="absolute z-10 bg-white border rounded shadow w-full">
                                        <template x-for="p in item.resultados" :key="p.id">
                                            <li @click="seleccionarProducto(index, p)"
                                                class="p-2 hover:bg-gray-100 cursor-pointer">
                                                <span x-text="p.nombre"></span>
                                                <span class="text-sm text-gray-500">
                                                    ($<span x-text="p.costo"></span>)
                                                </span>
                                            </li>
                                        </template>
                                    </ul>
                                    <input type="hidden" :name="`productos[${index}][producto_id]`"
                                        x-model="item.producto_id">
                                </td>
                                <td class="p-2">
                                    <div class="flex justify-center">
                                        <input type="number" min="1" :name="`productos[${index}][cantidad]`"
                                            x-model.number="item.cantidad" @input="calcular"
                                            class="border rounded p-1 w-20">
                                    </div>
                                </td>
                                <td class="p-2">
                                    <div class="flex justify-center">
                                        <input type="number" step="0.01" :name="`productos[${index}][costo]`"
                                            x-model.number="item.costo" @input="calcular"
                                            class="border rounded p-1 w-24">
                                    </div>
                                </td>
                                <td class="p-2">
                                    $<span x-text="(item.cantidad * item.costo).toFixed(2)"></span>
                                    <input type="hidden":name="`productos[${index}][importe]`"
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
                @error('productos')
                    <p class="text-red-600 text-xs mt-1">{{ 'Debes seleccionar al menos un producto' }}</p>
                @enderror
                <button type="button" @click="agregarFila" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
                    ➕ Agregar producto
                </button>
            </div> --}}
            <div class="w-full">

                <!-- ===== TABLA (DESKTOP) ===== -->
                <div class="hidden md:block">
                    <table class="w-full border bg-white shadow rounded">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2">Código</th>
                                <th class="p-2">Producto</th>
                                <th class="p-2">Cantidad</th>
                                <th class="p-2">Precio</th>
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
                                            class="border rounded p-1 w-full">

                                        <ul x-show="item.resultados.length"
                                            class="absolute z-10 bg-white border rounded shadow w-full">
                                            <template x-for="p in item.resultados" :key="p.id">
                                                <li @click="seleccionarProducto(index, p)"
                                                    class="p-2 hover:bg-gray-100 cursor-pointer">
                                                    <span x-text="p.nombre"></span>
                                                    <span class="text-sm text-gray-500">
                                                        ($<span x-text="p.costo"></span>)
                                                    </span>
                                                </li>
                                            </template>
                                        </ul>

                                        <input type="hidden" :name="`productos[${index}][producto_id]`"
                                            x-model="item.producto_id">
                                    </td>

                                    <td class="p-2 text-center">
                                        <input type="number" min="1" :name="`productos[${index}][cantidad]`"
                                            x-model.number="item.cantidad" @input="calcular"
                                            class="border rounded p-1 w-20">
                                    </td>

                                    <td class="p-2 text-center">
                                        <input type="number" step="0.01" :name="`productos[${index}][costo]`"
                                            x-model.number="item.costo" @input="calcular"
                                            class="border rounded p-1 w-24">
                                    </td>

                                    <td class="p-2 text-center">
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

                <!-- ===== CARDS (MÓVIL) ===== -->
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
                                    class="border rounded p-2 w-full">

                                <ul x-show="item.resultados.length"
                                    class="absolute z-10 bg-white border rounded shadow w-full">
                                    <template x-for="p in item.resultados" :key="p.id">
                                        <li @click="seleccionarProducto(index, p)"
                                            class="p-2 hover:bg-gray-100 cursor-pointer">
                                            <span x-text="p.nombre"></span>
                                            <span class="text-sm text-gray-500">
                                                ($<span x-text="p.costo"></span>)
                                            </span>
                                        </li>
                                    </template>
                                </ul>

                                <input type="hidden" :name="`productos[${index}][producto_id]`"
                                    x-model="item.producto_id">
                            </div>

                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <label class="text-sm text-gray-600">Cantidad</label>
                                    <input type="number" min="1" :name="`productos[${index}][cantidad]`"
                                        x-model.number="item.cantidad" @input="calcular"
                                        class="border rounded p-2 w-full">
                                </div>

                                <div class="flex-1">
                                    <label class="text-sm text-gray-600">Precio</label>
                                    <input type="number" step="0.01" :name="`productos[${index}][costo]`"
                                        x-model.number="item.costo" @input="calcular"
                                        class="border rounded p-2 w-full">
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-2 border-t">
                                <div class="text-lg font-semibold">
                                    Importe:
                                    $<span x-text="(item.cantidad * item.costo).toFixed(2)"></span>
                                </div>

                                <button type="button" @click="eliminarFila(index)" class="text-red-600 text-lg">
                                    ❌
                                </button>
                            </div>

                            <input type="hidden" :name="`productos[${index}][importe]`"
                                :value="(item.cantidad * item.costo).toFixed(2)">
                        </div>
                    </template>
                </div>

                <!-- ERROR -->
                @error('productos')
                    <p class="text-red-600 text-xs mt-2">
                        Debes seleccionar al menos un producto
                    </p>
                @enderror

                <!-- BOTÓN AGREGAR -->
                <button type="button" @click="agregarFila" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
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
            <input type="hidden" name="almacen_id" value="1">
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="fecha" value="{{ now()->format('Y-m-d') }}">
            <input type="hidden" name="subtotal" x-model="total">
            <input type="hidden" name="impuestos" :value="total * 1.16 - total">
            <input type="hidden" name="total" :value="total * 1.16">
            <input type="hidden" name="estatus" :value="1">

            <div class="md:col-span-2 flex justify-between gap-3 mt-4">
                <a href="{{ route('compras.index') }}"
                    class="px-4 py-2 rounded-md border-red-100 font-medium  text-white bg-red-600 hover:bg-red-600">
                    Cancelar
                </a>
                <div x-data @keydown.window.prevent.f9="$refs.btnRegistrar.click()">
                    <button
                        x-ref="btnRegistrar"
                        type="submit"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                        Actualizar [F9]
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- ================= ALPINE ================= --}}
    <script>
        function compraEdit(compra) {
            return {
                proveedor: compra.proveedor,
                proveedorQuery: compra.proveedor.nombre,
                proveedores: [],
                items: [],
                total: 0,

                init() {
                    this.items = compra.detalles.map(d => ({
                        detalle_id: d.id,
                        producto_id: d.producto_id,
                        codigo: d.producto.codigo_producto,
                        query: d.producto.nombre_producto,
                        cantidad: d.cantidad,
                        costo: parseFloat(d.costo_unitario),
                        resultados: []
                    }))
                    // print(resultados)
                    this.calcular()
                },
                // console.log(compra.detalles)

                agregarFila() {
                    this.items.push({
                        detalle_id: null,
                        producto_id: null,
                        codigo: '',
                        query: '',
                        cantidad: 1,
                        costo: 0,
                        resultados: []
                    })
                },

                eliminarFila(index) {
                    this.items.splice(index, 1)
                    this.calcular()
                },

                async buscarProveedor() {
                    if (this.proveedorQuery.length < 2) return
                    const res = await fetch(`/proveedores/buscar?q=${this.proveedorQuery}`)
                    this.proveedores = await res.json()
                },

                seleccionarProveedor(p) {
                    this.proveedor = p
                    this.proveedorQuery = p.nombre
                    this.proveedores = []
                },

                async buscarProducto(index) {
                    const q = this.items[index].query
                    if (q.length < 2) return
                    const res = await fetch(`/buscar/productos?q=${q}`)
                    this.items[index].resultados = await res.json()
                },

                seleccionarProducto(index, p) {
                    const item = this.items[index]
                    item.producto_id = p.id
                    item.codigo = p.codigo
                    item.query = p.nombre
                    item.costo = parseFloat(p.costo)
                    item.resultados = []
                    this.calcular()
                },

                calcular() {
                    this.total = this.items.reduce(
                        (t, i) => t + (i.cantidad * i.costo),
                        0
                    )
                }
            }
        }
    </script>

</x-app-layout>
