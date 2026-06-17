<x-app-layout>
    <x-slot name="header">
        <div class="md:flex md:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Devolución
                {{ match ($documento->documento_modelo_id) {1 => 'Cotización',2 => 'Factura',3 => 'Remisión'} }}
                {{ $sucursal->nombre . '#' . $documento->folio }}
            </h2>
            <label class="block text-lg font-medium mb-2 dark:text-white">Fecha: {{ now()->format('d/m/Y') }}
        </div>
    </x-slot>

    <form method="POST" @submit.prevent="prepararEnvio"
        action="{{ route('devolucion.update', parameters: ['sucursal' => $sucursal, 'documento' => $documento]) }}"
        x-data="documentoEdit(@js($documento->toArray()))" x-init="init()">
        @csrf
        @method('PUT')
        <div x-data="{ tab: 'detalle' }">
            <div class="flex gap-4 border-b mt-4">
                <button type="button" @click="tab='detalle'"
                    :class="tab === 'detalle' ? 'border-b-2 border-blue-500' : ''"
                    class="block text-lg font-medium mb-2 dark:text-white">
                    [1] Movimientos
                </button>

                <button type="button" @click="tab='info'"
                    :class="tab === 'info' ? 'border-b-2 border-blue-500' : ''"
                    class="block text-lg font-medium mb-2 dark:text-white">
                    [2] Datos generales
                </button>
            </div>
            <div x-show="tab === 'detalle'">
                <div class="mb-6 mt-4">
                    <div class="md:flex justify-between">
                        <label class="block text-lg font-medium mb-2 dark:text-white">Cliente: *</label>
                        <div class="md:flex gap-4">
                            <label class="block text-lg font-medium mb-2 mr-10 dark:text-white"></label>
                            <label class="block text-lg font-medium mb-2 dark:text-white">Fecha:
                                {{ now()->format('d/m/Y') }} </label>
                        </div>
                    </div>

                    <input type="text" x-model="proveedorQuery" readonly
                        class="w-full border rounded p-2 cursor-not-allowed" placeholder="Buscar cliente">
                    @error('proveedor_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                </div>
                {{-- ================= PRODUCTOS ================= --}}
                <div class="hidden md:block">
                    <table class="w-full bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                            <tr>
                                <th class="p-3 text-center">Código</th>
                                <th class="p-3">Producto</th>
                                <th class="p-3 text-center">Cantidad</th>
                                <th class="p-3 text-center">Precio</th>
                                <th class="p-3 text-center">Existencia</th>
                                <th class="p-3 text-center">Importe</th>
                                <th class="p-3"></th>
                            </tr>
                        </thead>

                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="border-t hover:bg-gray-50 transition">
                                    <td class="p-3 text-center font-medium" x-text="item.codigo"></td>

                                    <td class="p-3">
                                        <input type="text" x-model="item.query" readonly
                                            class="border rounded-md p-2 w-full bg-gray-100 cursor-not-allowed">
                                        <input type="hidden" :name="`productos[${index}][producto_id]`"
                                            x-model="item.producto_id">
                                    </td>

                                    <td class="p-3 text-center">
                                        <input type="number" min="1" :name="`productos[${index}][cantidad]`"
                                            x-model.number="item.cantidad" @input="calcular"
                                            class="border rounded-md p-2 w-20 text-center">
                                    </td>

                                    <td class="p-3 text-center">
                                        <input readonly type="number" step="0.01"
                                            :name="`productos[${index}][costo]`" x-model.number="item.costo"
                                            class="border rounded-md p-2 w-24 text-center bg-gray-100">
                                    </td>

                                    <td class="p-3 text-center">
                                        <input readonly type="number" x-model.number="item.stock"
                                            class="border rounded-md p-2 w-24 text-center bg-gray-100">
                                    </td>

                                    <td class="p-3 text-center font-semibold">
                                        $<span x-text="(item.cantidad * item.costo).toFixed(2)"></span>
                                        <input type="hidden" :name="`productos[${index}][importe]`"
                                            :value="(item.cantidad * item.costo).toFixed(2)">
                                    </td>

                                    <td class="p-3 text-center">
                                        <button type="button" @click="eliminarFila(index)"
                                            class="text-red-500 hover:text-red-700 text-lg">
                                            ❌
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="md:hidden space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="bg-white border rounded-lg shadow-sm p-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Código</span>
                                <span class="font-semibold" x-text="item.codigo"></span>
                            </div>

                            <div>
                                <span class="text-sm text-gray-500">Producto</span>
                                <input type="text" x-model="item.query" readonly
                                    class="border rounded-md p-2 w-full bg-gray-100 cursor-not-allowed mt-1">
                                <input type="hidden" :name="`productos[${index}][producto_id]`"
                                    x-model="item.producto_id">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-sm text-gray-500">Cantidad</span>
                                    <input type="number" min="1" :name="`productos[${index}][cantidad]`"
                                        x-model.number="item.cantidad" @input="calcular"
                                        class="border rounded-md p-2 w-full text-center">
                                </div>

                                <div>
                                    <span class="text-sm text-gray-500">Precio</span>
                                    <input readonly type="number" :name="`productos[${index}][costo]`"
                                        x-model.number="item.costo"
                                        class="border rounded-md p-2 w-full text-center bg-gray-100">
                                </div>

                                <div>
                                    <span class="text-sm text-gray-500">Existencia</span>
                                    <input readonly type="number" x-model.number="item.stock"
                                        class="border rounded-md p-2 w-full text-center bg-gray-100">
                                </div>

                                <div>
                                    <span class="text-sm text-gray-500">Importe</span>
                                    <div class="font-semibold text-center mt-2">
                                        $<span x-text="(item.cantidad * item.costo).toFixed(2)"></span>
                                        <input type="hidden" :name="`productos[${index}][importe]`"
                                            :value="(item.cantidad * item.costo).toFixed(2)">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" @click="eliminarFila(index)"
                                    class="text-red-500 hover:text-red-700">
                                    ❌ Eliminar
                                </button>
                            </div>
                        </div>
                    </template>
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
                <input type="hidden" name="almacen_id" value="{{ $sucursal->almacen_id }}">
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <input type="hidden" name="fecha" value="{{ now()->format('Y-m-d') }}">
                <input type="hidden" name="subtotal" x-model="total">
                <input type="hidden" name="impuestos" :value="total * 1.16 - total">
                <input type="hidden" name="total" :value="total * 1.16">
                <input type="hidden" name="estatus" :value="1">
                <input type="hidden" name="tipo" value="{{ $documento->documento_modelo_id }}">
            </div>
            <div x-show="tab === 'info'" x-cloak class="space-y-4">
                <div class="md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4 lg:gap-4">
                    <div class="col-span-full">
                        <label
                            class="block text-xl text-center md:text-left  mt-4  font-medium text-gray-700 dark:text-white">
                            Datos del cliente: </span>
                        </label>
                    </div>
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                            RFC: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="rfc" placeholder="RFC" x-model="proveedorRfc" readonly
                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                        @error('rfc')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                            Codigo postal: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="codigo_postal" placeholder="Codigo postal" x-model="proveedorCP"
                            readonly
                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                        @error('codigo_postal')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                            Ciudad: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="ciudad" placeholder="Ciudad" x-model="proveedorCiudad" readonly
                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                        @error('ciudad')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{--  --}}
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                            Calle: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="calle" placeholder="calle" x-model="proveedorCalle" readonly
                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                        @error('calle')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                            Número interior: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="numero_interior" placeholder="Número interior" readonly
                            x-model="proveedorNumeroInterior"
                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                        @error('numero_interior')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                            Colonia: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="colonia" placeholder="colonia" x-model="proveedorColonia"
                            readonly
                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                        @error('colonia')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-full">
                        <label for="metodo_pago" class="block text-xl font-medium text-gray-700 dark:text-white mb-1">
                            Datos del pago: </span>
                        </label>
                    </div>
                    {{-- Metodo de pago --}}
                    <div class="mb-2">
                        <label for="metodo_pago" class="block text-md font-medium text-gray-700 dark:text-white mb-1">
                            Metodo de pago: <span class="text-red-500">*</span>
                        </label>
                        <select name="metodo_pago" disabled
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="PUE" @selected(old('metodo_pago', $documento->metodo_pago) === 'PUE')>PUE Pago en una sola exhibición
                            </option>
                            <option value="PPD" @selected(old('metodo_pago', $documento->metodo_pago) === 'PPD')>PPD Pago en Parcialidades o Diferido
                            </option>
                        </select>
                        @error('metodo_pago')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Forma de pago --}}
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                            Forma de pago:<span class="text-red-500">*</span>
                        </label>
                        <select name="forma_pago" disabled
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="01" @selected(old('forma_pago', $documento->forma_pago) === '01')>01 Efectivo</option>
                            <option value="03" @selected(old('forma_pago', $documento->forma_pago) === '03')>03 Transferencia</option>
                            <option value="04" @selected(old('forma_pago', $documento->forma_pago) === '04')>04 Tarjeta de crédito</option>
                            <option value="28" @selected(old('forma_pago', $documento->forma_pago) === '28')>28 Tarjeta de débito</option>
                            <option value="05" @selected(old('forma_pago', $documento->forma_pago) === '05')>05 Monedero electrónico</option>
                            <option value="02" @selected(old('forma_pago', $documento->forma_pago) === '02')>02 Cheque nominativo</option>
                        </select>
                        @error('forma_pago')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Uso de cfdi --}}
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                            Uso de CFDI <span class="text-red-500">*</span>
                        </label>
                        <select name="uso_cfdi" id="uso_cfdi" disabled
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @foreach ($usos as $uso)
                                <option value="{{ $uso->clave }}" @selected(old('uso_cfdi', $documento->uso_cfdi) === $uso->clave)>
                                    {{ $uso->clave }} - {{ $uso->descripcion }}
                                </option>
                            @endforeach
                        </select>

                        @error('uso_cfdi')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                            Observaciones <span class="text-red-500">*</span>
                        </label>
                        <textarea class="w-full" name="observaciones">{{ $documento->observaciones }}</textarea>
                        @error('observaciones')
                            <p class="text-red-600 text-xs mt-1"></p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="devoluciones" x-ref="devoluciones">
        <div class="md:col-span-2 flex justify-between gap-3 mt-4">
            <a href="{{ route(match ($documento->documento_modelo_id) {1 => 'cotizaciones.index',2 => 'facturas.index',3 => 'remisiones.index'},$sucursal) }}"
                class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                 <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
            </a>

            <button type="submit"
                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                Realizar </button>
            {{-- <a href="{{ route('cotizacion.pdf', $documento) }}" target="_blank"
                    class="px-4 py-2 bg-red-600 text-white rounded">
                    📄 Imprimir PDF
                </a> --}}
        </div>
    </form>
    {{-- {{ dd($documento->cliente->domicilios) }} --}}
    {{-- ================= ALPINE ================= --}}
    <script>
        function documentoEdit(documento) {
            return {
                // ======================
                // PROVEEDOR
                // ======================
                proveedor: documento.cliente,
                proveedorQuery: documento.cliente.nombre,
                proveedorRfc: documento.cliente.rfc,
                proveedorCP: documento.cliente.domicilios?.[0]?.cp ?? '',
                proveedorCalle: documento.cliente.domicilios?.[0]?.calle ?? '',
                proveedorNumeroInterior: documento.cliente.domicilios?.[0]?.numero_interior ?? '',
                proveedorCiudad: documento.cliente.domicilios?.[0]?.ciudad ?? '',
                proveedorColonia: documento.cliente.domicilios?.[0]?.colonia ?? '',

                proveedores: [],

                // ======================
                // PRODUCTOS
                // ======================
                items: [],
                itemsOriginales: [],

                total: 0,

                // ======================
                // INIT
                // ======================
                init() {
                    this.items = documento.detalles.map(d => ({
                        detalle_id: d.id,
                        producto_id: d.producto_id,
                        codigo: d.producto.codigo_producto,
                        query: d.producto.nombre_producto,
                        cantidad: d.cantidad,
                        costo: parseFloat(d.costo_unitario),
                        stock: d.stock,
                        resultados: []
                    }))

                    // 🔒 copia inmutable para comparar devoluciones
                    this.itemsOriginales = JSON.parse(JSON.stringify(this.items))

                    this.calcular()
                },

                // ======================
                // FILAS
                // ======================
                eliminarFila(index) {
                    this.items.splice(index, 1)
                    this.calcular()
                },

                // ======================
                // BUSCAR PROVEEDOR
                // ======================
                async buscarProveedor() {
                    if (this.proveedorQuery.length < 2) return
                    const res = await fetch(`/clientes/buscar?q=${this.proveedorQuery}`)
                    this.proveedores = await res.json()
                },

                seleccionarProveedor(p) {
                    this.proveedor = p
                    this.proveedorQuery = p.nombre
                    this.proveedorRfc = p.rfc
                    this.proveedorCalle = p.domicilios?.[0]?.calle ?? ''
                    this.proveedorCP = p.domicilios?.[0]?.cp ?? ''
                    this.proveedorNumeroInterior = p.domicilios?.[0]?.numero_interior ?? ''
                    this.proveedorCiudad = p.domicilios?.[0]?.ciudad ?? ''
                    this.proveedorColonia = p.domicilios?.[0]?.colonia ?? ''
                    this.proveedores = []
                },

                // ======================
                // VALIDACIÓN DEVOLUCIÓN
                // ======================
                validarDevolucion() {
                    for (const item of this.items) {
                        const original = this.itemsOriginales.find(
                            o => o.producto_id === item.producto_id
                        )

                        if (original && item.cantidad > original.cantidad) {
                            alert('❌ No puedes aumentar la cantidad en una devolución')
                            return false
                        }
                    }
                    return true
                },

                // ======================
                // DEVOLUCIONES
                // ======================
                obtenerDevoluciones() {
                    const devoluciones = []

                    // 🔹 devoluciones parciales
                    this.items.forEach(item => {
                        const original = this.itemsOriginales.find(
                            o => o.producto_id === item.producto_id
                        )

                        if (original && item.cantidad < original.cantidad) {
                            devoluciones.push({
                                detalle_id: item.detalle_id,
                                producto_id: item.producto_id,
                                cantidad: original.cantidad - item.cantidad,
                                costo_unitario: item.costo,
                                importe: ((original.cantidad - item.cantidad) * item.costo).toFixed(2)
                            })
                        }
                    })

                    // 🔹 devoluciones totales (producto eliminado)
                    this.itemsOriginales.forEach(original => {
                        const existe = this.items.some(
                            i => i.producto_id === original.producto_id
                        )

                        if (!existe) {
                            devoluciones.push({
                                detalle_id: original.detalle_id,
                                producto_id: original.producto_id,
                                cantidad: original.cantidad,
                                costo_unitario: original.costo,
                                importe: (original.cantidad * original.costo).toFixed(2)
                            })
                        }
                    })

                    return devoluciones
                },

                // ======================
                // CALCULO
                // ======================
                calcular() {
                    this.total = this.items.reduce(
                        (t, i) => t + (i.cantidad * i.costo), 0
                    )
                },

                // ======================
                // SUBMIT
                // ======================
                prepararEnvio() {
                    if (!this.validarDevolucion()) return

                    const devoluciones = this.obtenerDevoluciones()

                    // ❌ NO hubo movimientos
                    if (devoluciones.length === 0) {
                        alert('⚠️ No se realizó ninguna devolución')
                        return
                    }

                    // ✔ Guardar JSON en input hidden
                    this.$refs.devoluciones.value = JSON.stringify(devoluciones)

                    console.log('DEVOLUCIONES →', devoluciones)

                    // ✔ Enviar formulario
                    this.$el.submit()
                }


            }
        }
    </script>

</x-app-layout>
