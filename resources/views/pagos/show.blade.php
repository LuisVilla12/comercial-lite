@section('title', 'Detalles de un REP')
<x-app-layout>
    <x-slot name="header">
        <div class="md:flex md:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 text-center">
                Detalles del ERP
            </h2>
            <label class="block text-lg font-medium mb-2 dark:text-white text-center">Fecha: {{ now()->format('d/m/Y') }}
            </label>
        </div>
    </x-slot>
    <div class="md:flex md:justify-between mt-4 md:items-center md:gap-2 ">
            <div>
                <p class="dark:text-white text-center uppercase md:text-left">Estado:
                    @php
                        $estatusText = match (true) {
                            $documento->estatus == 1 => 'ACTIVA',
                            $documento->estatus == 3 => 'CANCELADA',
                            $documento->estatus == 4 => 'TIMBRADA',

                            default => 'DESCONOCIDO',
                        };
                    @endphp
                    <span class="font-bold text-green-600">{{ $estatusText }}</span>
                </p>

            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <a href="{{ route('pagos.pdf',$documento) }}" target="_blank"
                        class="px-4 py-2 bg-blue-600 text-white rounded flex items-center ml-6">
                        <x-heroicon-o-printer class="w-5 h-5 mr-2" /> Imprimir
                    </a>
                <div>
                    <button onclick="timbrar()" disabled
                        class="flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded  w-full">
                        <x-heroicon-o-arrow-up-on-square-stack class="w-5 h-5" />
                        Timbrar
                    </button>
                    <form method="POST" id="formTimbrar"
                        action="">
                        @csrf
                    </form>
                </div>
            </div>
    </div>
    <form method="POST" action="{{ route('pagos.store') }}" x-data="compraApp()" x-init="init();">
        @csrf
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
                <div class=" mx-auto py-6">
                    <div class="mb-6">
                        <div class="md:flex justify-between">
                            <label class="block text-lg font-medium mb-2 dark:text-white">Cliente: *</label>
                        </div>
                        <input type="text" readonly class="w-full border rounded p-2"  value="{{ $documento->cliente->nombre }}">
                    </div>
                    {{-- ================= FACTURAS ================= --}}
                    <div>
                        <!-- Cliente -->

                        <div class="col-span-full">
                            <label class="block text-lg font-medium mb-2 dark:text-white">
                                Facturas pendientes:
                            </label>

                            <div class="shadow-md overflow-x-auto rounded-lg">
                                <table class="w-full border bg-white shadow rounded">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="p-2">Serie</th>
                                            <th class="p-2">Folio</th>
                                            <th class="p-2">Fecha</th>
                                            <th class="p-2">Total</th>
                                            <th class="p-2">Saldo pendiente</th>
                                            <th class="p-2">Monto a pagar</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @foreach ($documento->detalles as $detalle)
                                            <tr class="border-t">
                                                <td class="p-2 text-center">{{ $detalle->documento->serie }}</td>
                                                <td class="p-2 text-center">{{ $detalle->documento->folio }}</td>
                                                <td class="p-2 text-center">{{ $detalle->documento->fecha }}</td>
                                                <td class="p-2 text-center"> {{ number_format($detalle->documento->total,2) }}</td>
                                                <td class="p-2 text-center"> {{ number_format($detalle->documento->saldo_pendiente,2) }} </td>
                                                <td class="p-2 text-center">{{ number_format($detalle->monto,2) }}</td>
                                            </tr>
                                    @endforeach
                                        </tbody>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div x-show="tab === 'info'" x-cloak class="space-y-4">
                <div class="md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4 lg:gap-4">
                    <div class="col-span-full">
                        <label
                            class="block text-xl mt-4 text-center md:text-left font-medium text-gray-700 dark:text-white">
                            Datos del cliente: </span>
                        </label>
                    </div>
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                            RFC: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="rfc" placeholder="RFC" value="{{ $documento->cliente->rfc }}"
                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                            Codigo postal: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="codigo_postal" placeholder="Codigo postal" value="{{ optional($documento->domicilios->first())->cp }}"
                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                            Ciudad: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="ciudad" placeholder="Ciudad" value="{{ optional($documento->domicilios->first())->ciudad }}"
                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    {{--  --}}
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                            Calle: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="calle" placeholder="calle" value="{{ optional($documento->domicilios->first())->calle }}"
                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                    </div>
                    <div>
                        <div class="mb-2">
                            <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                                Número exterior: <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="numero_exterior" placeholder="Número exterior" value="{{ optional($documento->domicilios->first())->numero_exterior }}"
                                class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700  dark:text-white mb-1">
                            Colonia: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="colonia" placeholder="colonia" value="{{ optional($documento->domicilios->first())->colonia }}"
                            class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="col-span-full">
                        <label for="metodo_pago"
                            class="block mt-4 text-center md:text-left text-xl font-medium text-gray-700 dark:text-white mb-1">
                            Datos del pago: </span>
                        </label>
                    </div>

                    {{-- Forma de pago --}}
                    <div class="mb-2">
                        <label class="block text-md font-medium text-gray-700 mb-1 dark:text-white">
                            Forma de pago:<span class="text-red-500">*</span>
                        </label>
                        <select name="forma_pago" id="forma_pago"
                            class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="01" @selected(old('forma_pago', $documento->forma_pago) === '01')>01 Efectivo</option>
                            <option value="03" @selected(old('forma_pago', $documento->forma_pago) === '03')>03 Transferencia</option>
                            <option value="04" @selected(old('forma_pago', $documento->forma_pago) === '04')>04 Tarjeta de crédito</option>
                            <option value="28" @selected(old('forma_pago', $documento->forma_pago) === '28')>28 Tarjeta de débito</option>
                            <option value="05" @selected(old('forma_pago', $documento->forma_pago) === '05')>05 Monedero electrónico</option>
                            <option value="02" @selected(old('forma_pago', $documento->forma_pago) === '02')>02 Cheque nominativo</option>
                        </select>
                    </div>

                </div>
            </div>
            <div class="md:col-span-2 flex justify-between gap-3 mt-4">

                <a href="{{ route('pagos.index') }}"
                    class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                    <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" /> Regresar
                </a>
                @if ($documento->estatus == 1)
            <div x-data @keydown.window.prevent.f9="$refs.btnRegistrar.click()">
                <a x-ref="btnRegistrar" href="{{ route('pagos.edit', $documento) }}"
                    class="px-6 py-2 uppercase bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                    Actualizar [F9]
                </a>
            </div>
            @endif

            </div>


        </div>
    </form>
    </div>
    {{-- ================= ALPINE ================= --}}
    <script>
        function compraApp() {
            return {
                init() {
                },
            }
        }
        function timbrar() {
            Swal.fire({
                title: '¿Esta seguro que requiere timbrar este recibo electronico?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Si, timbrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Generando REP...',
                        text: 'Por favor espere mientras se realiza el timbrado del documento.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('formTimbrar').submit();
                }
            });
        }
    </script>
</x-app-layout>
