<x-app-layout>
<x-slot name="header">
    <h2 class="text-xl font-semibold">Carga inicial de inventario</h2>
</x-slot>

<div class="max-w-7xl mx-auto py-6"
     x-data="compraApp()">

<form method="POST" action="{{ route('compras.store') }}">
@csrf

{{-- Proveedor --}}
<div class="mb-4">
    <label class="font-medium">Proveedor</label>
    <input type="text" x-model="proveedorQuery"
           @input.debounce.300ms="buscarProveedor"
           class="w-full rounded border-gray-300"
           placeholder="Buscar proveedor">

    <ul x-show="proveedores.length"
        class="border bg-white mt-1 rounded shadow">
        <template x-for="p in proveedores" :key="p.id">
            <li @click="seleccionarProveedor(p)"
                class="p-2 hover:bg-gray-100 cursor-pointer"
                x-text="p.nombre"></li>
        </template>
    </ul>

    <input type="hidden" name="proveedor_id" :value="proveedor?.id">
</div>

{{-- Productos --}}
<div class="bg-white shadow rounded p-4">
    <table class="w-full text-sm">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Costo</th>
                <th>Importe</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(item, index) in items" :key="index">
                <tr>
                    <td>
                        <input type="text"
                               x-model="item.query"
                               @input.debounce.300ms="buscarProducto(index)"
                               class="w-full rounded border-gray-300"
                               placeholder="Buscar producto">

                        <ul x-show="item.resultados.length"
                            class="border bg-white mt-1 rounded shadow absolute z-10">
                            <template x-for="p in item.resultados" :key="p.id">
                                <li @click="seleccionarProducto(index, p)"
                                    class="p-2 hover:bg-gray-100 cursor-pointer"
                                    x-text="p.nombre"></li>
                            </template>
                        </ul>

                        <input type="hidden"
                               :name="`productos[${index}][producto_id]`"
                               :value="item.producto?.id">
                    </td>

                    <td>
                        <input type="number" min="1"
                               x-model.number="item.cantidad"
                               @input="calcular"
                               class="w-full rounded border-gray-300">
                    </td>

                    <td>
                        <input type="number"
                               x-model="item.costo"
                               readonly
                               class="w-full bg-gray-100 rounded border-gray-300">
                    </td>

                    <td x-text="(item.cantidad * item.costo).toFixed(2)"
                        class="text-right"></td>

                    <td>
                        <button type="button"
                            @click="items.splice(index,1); calcular()"
                            class="text-red-600">✕</button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>

    <button type="button"
            @click="agregarFila"
            class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
        + Agregar producto
    </button>
</div>

{{-- Total --}}
<div class="flex justify-end text-xl font-bold mt-6">
    Total: $<span x-text="total.toFixed(2)"></span>
</div>

<button class="mt-6 bg-green-600 text-white px-6 py-2 rounded">
    Guardar compra
</button>

</form>
</div>

<script>
function compraApp() {
    return {
        proveedor: null,
        proveedorQuery: '',
        proveedores: [],

        items: [],
        total: 0,

        agregarFila() {
            this.items.push({
                query: '',
                producto: null,
                cantidad: 1,
                costo: 0,
                resultados: []
            })
        },

        async buscarProveedor() {
            if (this.proveedorQuery.length < 3) return
            const res = await fetch(`/proveedores/buscar?q=${this.proveedorQuery}`)
            this.proveedores = await res.json()
        },

        seleccionarProveedor(p) {
            this.proveedor = p
            this.proveedores = []
            this.proveedorQuery = p.nombre
        },

        async buscarProducto(index) {
            const q = this.items[index].query
            if (q.length < 3) return

            const res = await fetch(`/productos/buscar?q=${q}`)
            this.items[index].resultados = await res.json()
        },

        seleccionarProducto(index, p) {
            const item = this.items[index]
            item.producto = p
            item.query = p.nombre
            item.costo = p.costo
            item.resultados = []
            this.calcular()
        },

        calcular() {
            this.total = this.items.reduce((t, i) => t + (i.cantidad * i.costo), 0)
        }
    }
}
</script>
</x-app-layout>
