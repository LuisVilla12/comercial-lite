<x-app-layout>
<div
    x-data="compraApp()"
    x-init="init()"
    class="max-w-7xl mx-auto py-6"
>

{{-- ================= PROVEEDOR ================= --}}
<div class="mb-6">
    <label class="block text-lg font-medium mb-2">Proveedor *</label>

    <input type="text"
        x-model="proveedorQuery"
        @input.debounce.300ms="buscarProveedor"
        class="w-full border rounded p-2"
        placeholder="Buscar proveedor">

    <ul x-show="proveedores.length"
        class="border bg-white rounded shadow mt-1 max-h-48 overflow-y-auto">
        <template x-for="p in proveedores" :key="p.id">
            <li
                @click="seleccionarProveedor(p)"
                class="p-2 hover:bg-gray-100 cursor-pointer"
                x-text="p.nombre">
            </li>
        </template>
    </ul>

    <input type="hidden" name="proveedor_id" :value="proveedor?.id">
</div>

{{-- ================= PRODUCTOS ================= --}}
<div class="">
<table class="w-full border bg-white shadow rounded p-4">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2">Código</th>
            <th class="p-2">Producto</th>
            <th class="p-2">Cantidad</th>
            <th class="p-2">Costo</th>
            <th class="p-2">Importe</th>
            <th class="p-2"></th>
        </tr>
    </thead>

    <tbody>
        <template x-for="(item, index) in items" :key="index">
            <tr class="border-t">
                <td class="p-2" x-text="item.codigo"></td>

                <td class="p-2 relative">
                    <input type="text"
                        x-model="item.query"
                        @input.debounce.300ms="buscarProducto(index)"
                        class="border rounded p-1 w-full"
                        placeholder="Buscar producto">

                    <ul x-show="item.resultados.length"
                        class="absolute z-10 bg-white border rounded shadow w-full">
                        <template x-for="p in item.resultados" :key="p.id">
                            <li
                                @click="seleccionarProducto(index, p)"
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
                    <input type="number" min="1"
                        x-model.number="item.cantidad"
                        @input="calcular"
                        class="border rounded p-1 w-20">
                </td>

                <td class="p-2">
                    <input type="number" step="0.01"
                        x-model.number="item.costo"
                        @input="calcular"
                        class="border rounded p-1 w-24">
                </td>

                <td class="p-2">
                    $<span x-text="(item.cantidad * item.costo).toFixed(2)"></span>
                </td>

                <td class="p-2 text-center">
                    <button type="button"
                        @click="eliminarFila(index)"
                        class="text-red-600 hover:text-red-800">
                        ❌
                    </button>
                </td>
            </tr>
        </template>
    </tbody>
</table>

<button type="button"
    @click="agregarFila"
    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
    ➕ Agregar producto
</button>
</div>

{{-- ================= TOTAL ================= --}}
<div class="flex justify-end text-xl font-bold mt-6">
    Total: $<span x-text="total.toFixed(2)"></span>
</div>

<button class="mt-6 bg-green-600 text-white px-6 py-2 rounded">
    Guardar compra
</button>

</div>

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

            const res = await fetch(`/productos/buscar?q=${q}`)
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
