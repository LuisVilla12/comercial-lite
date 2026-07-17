@section('title', content: 'Detalles de una compra')

<x-app-layout>
    <div class="flex items-center mt-4 py-2 gap-3 mb-4 bg-white dark:bg-slate-800  rounded-md w-full">
        <a href="{{ route('compras.index') }}" class="flex text-white  bg-red-600 border-1  rounded-lg p-4">
            <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />Regresar
        </a>
        <div class="md:flex md:justify-between items-center w-full">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                    Detalles de la compra {{ $compra->serie . ' #' . $compra->folio }}
                </h2>
                <p class="dark:text-white mt-2 font-semibold"> <span> Fecha:
                        {{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</span></p>
            </div>
            <div class="mt-2 md:mt-0 mb-4 md:mb-0">
                <p class="dark:text-white font-semibold">Estado: @php
                    $estatusText = match ($compra->estatus) {
                        1 => 'ACTIVA',
                        2 => 'TRANSFORMADA',
                        3 => 'CANCELADA',
                        4 => 'SURTIDA',
                        5 => 'DEVOLUCIÓN APLICADA',
                        default => 'DESCONOCIDO',
                    };
                @endphp
                    <span class="font-bold bg-green-500 rounded-md py-2 px-6 text-white mr-6">{{ $estatusText }}</span>
                </p>
            </div>
        </div>
    </div>
    <div class="flex flex-wrap justify-end gap-3">
        @if ($compra->estatus == 1)
            <button onclick="surtirCompra()"
                class="group w-24 rounded-xl border border-orange-500/30 bg-orange-500/10 dark:bg-orange-500/50 hover:bg-orange-500 hover:text-white transition-all duration-200">
                <div class="flex flex-col items-center py-1">
                    <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2 text-orange-400 group-hover:text-white" />
                    <span class="mt-1 font-semibold text-sm dark:text-white">Surtir <span
                            class="text-xs opacity-70">F1</span></span>
                </div>
            </button>


            <form method="POST" action="{{ route('compras.surtir', $compra) }}" class="hidden" id="formSurtirCompra">
                @csrf
            </form>
        @endif

    </div>


    <div class="md:flex md:justify-between gap-2 mt-2">
        <div class="md:w-9/12 px-1">
            <div class="grid md:grid-cols-2 md:gap-4 mb-4 bg-white dark:bg-slate-800 w-full rounded-md p-2">
                {{-- PROVEEDOR --}}
                <div class="mb-2">
                    <label class="block text-lg font-medium mb-2 dark:text-white">Proveedor: *</label>
                    <input type="text" value="{{ $compra->proveedor->nombre }}" disabled
                        class="w-full border rounded p-2 bg-gray-100">
                </div>
                {{-- almacen --}}
                <div>
                    <label class="block text-lg font-medium mb-2 dark:text-white">Seleccionar almacen: *</label>
                    <input type="text" value="{{ $compra->almacen->nombre }}" disabled
                        class="w-full border rounded p-2 bg-gray-100">
                </div>
            </div>

            <div class="w-full">
                <!-- ===== TABLA (DESKTOP) ===== -->
                <div class="hidden md:block">
                    <table class="w-full border bg-white shadow rounded">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2">Código</th>
                                <th class="p-2">Producto</th>
                                <th class="p-2">Cantidad</th>
                                <th class="p-2">Costo</th>
                                <th class="p-2">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($compra->detalles as $detalle)
                                <tr class="border-t">
                                    <td class="p-2 text-center">
                                        {{ $detalle->producto->codigo_producto }}
                                    </td>
                                    <td class="p-2">
                                        {{ $detalle->producto->nombre_producto }}
                                    </td>
                                    <td class="p-2 text-center">
                                        {{ $detalle->cantidad }}
                                    </td>
                                    <td class="p-2 text-right">
                                        ${{ number_format($detalle->costo_unitario, 2) }}
                                    </td>
                                    <td class="p-2 text-right">
                                        ${{ number_format($detalle->importe, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ===== CARDS (MÓVIL) ===== -->
                <div class="md:hidden space-y-4">
                    @foreach ($compra->detalles as $detalle)
                        <div class="border rounded-lg shadow bg-white p-4 space-y-2">

                            <div class="text-sm text-gray-500">
                                Código:
                                <span class="font-medium text-gray-800">
                                    {{ $detalle->producto->codigo_producto }}
                                </span>
                            </div>

                            <div>
                                <span class="text-sm text-gray-500">Producto</span>
                                <div class="font-medium">
                                    {{ $detalle->producto->nombre_producto }}
                                </div>
                            </div>

                            <div class="flex justify-between text-sm">
                                <div>
                                    <span class="text-gray-500">Cantidad:</span>
                                    <span class="font-medium">{{ $detalle->cantidad }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Costo:</span>
                                    <span class="font-medium">
                                        ${{ number_format($detalle->costo_unitario, 2) }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="font-semibold">Importe</span>
                                <span class="text-lg font-bold">
                                    ${{ number_format($detalle->importe, 2) }}
                                </span>
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>
        </div>
        {{-- TOTAL --}}
        <div class="md:w-3/12 px-1 mt-4 md:mt-0">
            <div class="bg-white  dark:bg-slate-800 rounded-md p-4">
                <h4 class=" text-center font-semibold uppercase dark:text-white">Resumen:</h4>
                <div class="">
                    {{-- ================= TOTALES ================= --}}
                    <div class="mt-4">
                        <div class="flex justify-between">
                            <p class=" text-base font-semibold dark:text-white uppercase mb-2">Total de articulos:</p>
                            <p class="dark:text-white">0</p>
                        </div>
                        <div class="flex justify-between">
                            <p class=" text-base font-semibold dark:text-white uppercase mb-2">Subtotal:</p>
                            <p class="dark:text-white"> ${{ number_format($compra->subtotal, 2) }}</p>
                        </div>

                        <div class="flex justify-between">
                            <p class=" text-base font-semibold dark:text-white uppercase mb-2">IVA (16%):</p>
                            <p class="dark:text-white">${{ number_format($compra->impuestos, 2) }}</p>
                        </div>
                        <div class="flex justify-between">
                            <p class="dark:text-white text-xl font-bold uppercase mb-2">Total: </p>
                            <p class="text-center text-2xl text-green-600 ">${{ number_format($compra->total, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="flex justify-between items-center gap-3 my-2">
                        @if ($compra->estatus == 1)
                            <div x-data @keydown.window.prevent.f9="$refs.btnRegistrar.click()" class="mx-auto">
                                <a x-ref="btnRegistrar" href="{{ route('compras.edit', $compra) }}"
                                    class="px-6 py-4 uppercase bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium text-center ">
                                    Actualizar [F9]
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>




</x-app-layout>
<script>
    function surtirCompra() {
        Swal.fire({
            title: '¿Deseas surtir la compra?',
            text: "Al surtir la compra, se marcará como surtida y no podrá ser editada ni convertida nuevamente. Esta acción es irreversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, surtir compra',
            cancelButtonText: 'No, cancelar',
            reverseButtons: true
        }).then((confirmResult) => {
            if (confirmResult.isConfirmed) {
                document.getElementById('formSurtirCompra').submit();
            }
        });
    }
</script>
