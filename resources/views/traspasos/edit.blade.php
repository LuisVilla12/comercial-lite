@section('title', 'Editar Traspaso')
<x-app-layout>
    <x-slot name="header">
        <div class="md:flex justify-between">
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
                <div class="grid  grid-cols-2 gap-10 ">
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
                <table class="w-full border bg-white shadow rounded p-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Código</th>
                            <th class="p-2">Producto</th>
                            <th class="p-2">Cantidad</th>
                            {{-- <th class="p-2">Precio</th> --}}
                            <th class="p-2">Existencia</th>
                            <th class="p-2">Restaria</th>
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
                                            x-model.number="item.cantidad" class="border rounded p-1 w-20 text-center">
                                    </div>
                                </td>
                                {{-- <td class="p-2">
                                    <div class="flex justify-center">
                                        <input readonly type="number" step="0.01"
                                            :name="`productos[${index}][costo]`" x-model.number="item.costo"
                                            class="border rounded p-1 w-24 text-center">
                                    </div>
                                </td> - --}}
                                {{-- Existencias --}}
                                <td class="p-2">
                                    <div class="flex justify-center">
                                        <input type="number" disabled step="1" x-model.number="item.stock"
                                            class="border rounded p-1 w-24 text-center bg-gray-100 text-gray-700">
                                    </div>
                                </td>
                                {{-- <td class="p-2">
                                    $<span x-text="(item.cantidad * item.costo).toFixed(2)" class="text-center"></span>
                                    <input type="hidden" :name="`productos[${index}][importe]`"
                                        :value="(item.cantidad * item.costo).toFixed(2)" class="">
                                </td> --}}

                                <td class="p-2">
                                    <div class="flex justify-center">
                                    <span x-text="item.stock - item.cantidad " class="text-center"></span>
                                    </div>
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
                    Guardar Traspaso
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
