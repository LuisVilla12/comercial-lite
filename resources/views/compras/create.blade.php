<x-app-layout>
    <form method="POST" action="{{ route('compras.store') }}">
        @csrf
        <div x-data="compraApp()" x-init="init()" class="max-w-7xl mx-auto py-6">
            {{-- ================= PROVEEDOR ================= --}}
            <div class="mb-6">
                <div class="md:flex justify-between">
                    <label class="block text-lg font-medium mb-2 dark:text-white">Proveedor: *</label>
                    <div class="md:flex gap-4">
                        <label class="block text-lg font-medium mb-2 mr-10 dark:text-white"></label>
                        <label class="block text-lg font-medium mb-2 dark:text-white">Fecha: {{ now()->format('d/m/Y')
                            }} </label>
                    </div>
                </div>

                <input type="text" x-model="proveedorQuery" @input.debounce.300ms="buscarProveedor"
                    class="w-full border rounded p-2" placeholder="Buscar proveedor">
                @error('proveedor_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror

                <ul x-show="proveedores.length" class="border bg-white rounded shadow mt-1 max-h-48 overflow-y-auto">
                    <template x-for="p in proveedores" :key="p.id">
                        <li @click="seleccionarProveedor(p)" class="p-2 hover:bg-gray-100 cursor-pointer"
                            x-text="p.nombre">
                        </li>
                    </template>
                </ul>
            </div>
            {{-- ================= PRODUCTOS ================= --}}
            <div class="">
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
                                        @input.debounce.300ms="buscarProducto(index)" class="border rounded p-1 w-full"
                                        placeholder="Buscar producto">
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
                                    <input type="hidden":name="`productos[${index}][importe]`" :value="(item.cantidad * item.costo).toFixed(2)">
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
            <input type="hidden" name="impuestos" :value="(total * 1.16) - total">
            <input type="hidden" name="total" :value="(total*1.16)">
            <input type="hidden" name="estatus" :value="1">

            <div class="md:col-span-2 flex justify-between gap-3 mt-4">
                <a href="{{ route('compras.index') }}"
                    class="px-4 py-2 rounded-md border dark:bg-white border-gray-300 text-gray-700 hover:bg-gray-400">
                    Cancelar
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                    Guardar compra
                </button>
            </div>
    </div>
    </form>

    {{-- ================= ALPINE ================= --}}
    <script>
        function compraApp() {
    return {
        proveedor: null,
        proveedorQuery: '',
        proveedores: [],

        items: [],
        total: 0,

        init() {
            this.items = []
            this.agregarFila()
        },

        agregarFila() {
            this.items.push({
                producto_id: null,
                codigo: '',
                query: '',
                cantidad: 1,
                costo: 0,
                resultados: []
            })
        },

        eliminarFila(index) {
            if (this.items.length === 1) return
            this.items.splice(index, 1)
            this.calcular()
        },

        async buscarProveedor() {
            if (this.proveedorQuery.length < 2) return
            const res = await fetch(`/api/proveedores/buscar?q=${this.proveedorQuery}`)
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

            const res = await fetch(`/api/productos/buscar?q=${q}`)
            this.items[index].resultados = await res.json()
        },

        seleccionarProducto(index, p) {
            if (this.items.some(i => i.producto_id === p.id)) return

            const item = this.items[index]
            item.producto_id = p.id
            item.codigo = p.codigo
            item.query = p.nombre
            item.costo = parseFloat(p.costo)
            item.resultados = []

            this.calcular()

            if (index === this.items.length - 1) {
                this.agregarFila()
            }
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
