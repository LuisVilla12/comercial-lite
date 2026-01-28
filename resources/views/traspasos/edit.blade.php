@section('title', 'Editar Traspaso')
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Editar Traspaso
            </h2>
            <div class="md:flex gap-4">
                <label class="block text-lg font-medium mb-2 dark:text-white">Fecha: {{ now()->format('d/m/Y') }}
                </label>
            </div>
        </div>

    </x-slot>
    <form method="POST" action="{{ route('traspasos.update', $traspaso) }}">
        @csrf
        @method('PUT')
        <div x-data="trasladoEdit(@js($traspaso ?? null))" x-init="init()" class="max-w-7xl mx-auto py-6">
            <div class="mb-6">
                <div class="grid  md:grid-cols-2 md:gap-6 ">
                    {{-- ================= Almacen origen ================= --}}
                    {{-- Almacén salida --}}
                    <div class="mb-4">
                        <label class="block text-lg font-medium mb-2 dark:text-white">
                            Almacén salida: *
                        </label>

                        <select name="almacen_origen_id" x-model="almacen_origen_id" @change="cambioAlmacenOrigen"
                            class="p-2 w-full rounded-md border-gray-300">
                            <option value="" disabled>Seleccione almacén de salida</option>
                            @foreach ($almacenes as $almacen)
                                <option value="{{ $almacen->id }}">
                                    {{ $almacen->nombre }}
                                </option>
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
            <div class="">
                <div class="hidden lg:block">
                    <table class="w-full border border-gray-200 bg-white shadow-lg rounded-xl overflow-hidden">
                        <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                            <tr>
                                <th class="px-4 py-3 text-center">Código</th>
                                <th class="px-4 py-3 text-left">Producto</th>
                                <th class="px-4 py-3 text-center">Cantidad</th>
                                <th class="px-4 py-3 text-center">Existencia</th>
                                <th class="px-4 py-3 text-center">Restaría</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 text-sm">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-2 text-center font-mono" x-text="item.codigo"></td>

                                    <td class="px-4 py-2 relative">
                                        <input type="text" x-model="item.query"
                                            @input.debounce.300ms="buscarProducto(index)"
                                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 text-sm px-3 py-2"
                                            placeholder="Buscar producto">

                                        <ul x-show="item.resultados.length"
                                            class="absolute z-20 mt-1 w-full bg-white border rounded-lg shadow max-h-48 overflow-auto">
                                            <template x-for="p in item.resultados" :key="p.id">
                                                <li @click="seleccionarProducto(index, p)"
                                                    class="px-4 py-2 hover:bg-blue-50 cursor-pointer flex justify-between">
                                                    <span x-text="p.nombre"></span>
                                                    <span class="text-xs text-gray-500">$<span
                                                            x-text="p.costo"></span></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        <input type="number" min="1" x-model.number="item.cantidad"
                                            class="w-20 rounded-lg border-gray-300 text-center">
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        <input type="number" disabled x-model.number="item.stock"
                                            class="w-24 bg-gray-100 text-gray-600 text-center rounded-lg border">
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        <span x-text="item.stock - item.cantidad"
                                            :class="(item.stock - item.cantidad) < 0
                                                ?
                                                'text-red-600 font-bold' :
                                                'text-green-600 font-semibold'">
                                        </span>
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        <button @click="eliminarFila(index)"
                                            class="text-red-500 hover:text-red-700 transition">
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
                        <div class="bg-white rounded-xl shadow border p-4 space-y-3">

                            <!-- Header -->
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Código</span>
                                <span class="font-mono font-semibold" x-text="item.codigo"></span>
                            </div>

                            <!-- Producto -->
                            <div class="relative">
                                <label class="text-xs text-gray-500">Producto</label>
                                <input type="text" x-model="item.query" @input.debounce.300ms="buscarProducto(index)"
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 px-3 py-2"
                                    placeholder="Buscar producto">

                                <ul x-show="item.resultados.length"
                                    class="absolute z-20 mt-1 w-full bg-white border rounded-lg shadow max-h-48 overflow-auto">
                                    <template x-for="p in item.resultados" :key="p.id">
                                        <li @click="seleccionarProducto(index, p)"
                                            class="px-4 py-2 hover:bg-blue-50 cursor-pointer flex justify-between">
                                            <span x-text="p.nombre"></span>
                                            <span class="text-xs text-gray-500">$<span x-text="p.costo"></span></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            <!-- Cantidad / Stock -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500">Cantidad</label>
                                    <input type="number" min="1" x-model.number="item.cantidad"
                                        class="w-full rounded-lg border-gray-300 text-center">
                                </div>

                                <div>
                                    <label class="text-xs text-gray-500">Existencia</label>
                                    <input type="number" disabled x-model.number="item.stock"
                                        class="w-full bg-gray-100 text-gray-600 text-center rounded-lg border">
                                </div>
                            </div>

                            <!-- Restaría -->
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Restaría</span>
                                <span x-text="item.stock - item.cantidad"
                                    :class="(item.stock - item.cantidad) < 0
                                        ?
                                        'text-red-600 font-bold' :
                                        'text-green-600 font-semibold'"
                                    class="px-3 py-1 rounded-full bg-gray-100">
                                </span>
                            </div>

                            <!-- Acción -->
                            <div class="flex justify-end">
                                <button @click="eliminarFila(index)"
                                    class="text-red-500 hover:text-red-700 transition">
                                    ❌ Eliminar
                                </button>
                            </div>

                        </div>
                    </template>
                </div>

                @error('productos')
                    <p class="text-red-600 text-xs mt-1">{{ 'Debes seleccionar al menos un producto' }}</p>
                @enderror
                <button type="button" @click="agregarFila" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
                    ➕ Agregar producto
                </button>
            </div>

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
                    class="px-4 py-2 rounded-md border dark:bg-white border-gray-300 text-gray-700 hover:bg-gray-400">
                    Cancelar
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                    Actualizar
                </button>
            </div>
        </div>
    </form>

    {{-- ================= ALPINE ================= --}}
    <script>
        function trasladoEdit(traspaso = null) {
            return {
                almacen_origen_id: traspaso?.almacen_origen_id ?? '',
                almacen_destino_id: traspaso?.almacen_destino_id ?? '',
                items: [],
                total: 0,

                init() {
                    if (traspaso && traspaso.detalles?.length) {
                        this.items = traspaso.detalles.map(d => ({
                            detalle_id: d.id,
                            producto_id: d.producto_id,
                            codigo: d.producto.codigo_producto,
                            query: d.producto.nombre_producto,
                            cantidad: d.cantidad,
                            costo: parseFloat(d.costo ?? 0),
                            stock: d.stock ?? 0,
                            resultados: []
                        }))
                    } else {
                        this.agregarFila()
                    }
                },

                cambioAlmacenOrigen() {
                    this.items = []
                    this.agregarFila()
                },

                agregarFila() {
                    this.items.push({
                        detalle_id: null,
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
                    if (this.items.length === 1) return
                    this.items.splice(index, 1)
                },

                async buscarProducto(index) {
                    if (!this.almacen_origen_id) return

                    const q = this.items[index].query
                    if (q.length < 2) return

                    const res = await fetch(
                        `/api/productos-existencias/buscar?q=${q}&almacen=${this.almacen_origen_id}`
                    )
                    this.items[index].resultados = await res.json()
                },

                seleccionarProducto(index, p) {
                    if (this.items.some(i => i.producto_id === p.id)) return

                    const item = this.items[index]
                    item.producto_id = p.id
                    item.codigo = p.codigo
                    item.query = p.nombre
                    item.costo = parseFloat(p.costo ?? 0)
                    item.stock = p.stock
                    item.resultados = []

                    if (index === this.items.length - 1) {
                        this.agregarFila()
                    }
                }
            }
        }
    </script>


</x-app-layout>
