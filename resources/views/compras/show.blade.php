@section('title', content: 'Ver compra')

<x-app-layout>
    @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4 mt-4">
            {{ session('success') }}
        </p>
    @endif
    @if (session('error'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4 mt-4">{{ session('error') }}
        </p>
    @endif

    <div class="display flex justify-between mb-2 mt-4">
<h1 class="block text-lg font-medium text-center uppercase dark:text-white ">
            Compra #{{ $compra->id }}
        </h1>
        <p class="dark:text-white text-lg uppercase">Estado: @php
                $estatusText = match ($compra->estatus) {
                    1 => 'ACTIVA',
                    2 => 'TRANSFORMADA',
                    3 => 'CANCELADA',
                    4 => 'SURTIDA',
                    5 => 'DEVOLUCIÓN APLICADA',
                    default => 'DESCONOCIDO',
                };
            @endphp
                <span class="font-bold text-green-600">{{ $estatusText }}</span>
            </p>
        @if ($compra->estatus == 1)
        <div>
<button
                        onclick="surtirCompra()"
                        class="flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded w-full mb-4 md:mb-0 ">
                        <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2" />
                        Surtir
                    </button>
        </div>
            <form method="POST" action="{{ route('compras.surtir', $compra) }}" class="hidden" id="formSurtirCompra">
                @csrf
            </form>
        @endif

    </div>

    <div class="grid md:grid-cols-2 md:gap-4 mb-4">
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

    {{-- <table class="w-full border bg-white shadow rounded">
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
                    <td class="p-2 text-center">{{ $detalle->producto->codigo_producto }}</td>
                    <td class="p-2">{{ $detalle->producto->nombre_producto }}</td>
                    <td class="p-2 text-center">{{ $detalle->cantidad }}</td>
                    <td class="p-2 text-right">
                        ${{ number_format($detalle->costo_unitario, 2) }}
                    </td>
                    <td class="p-2 text-right">
                        ${{ number_format($detalle->importe, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}
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

    <div class="text-right mt-6 space-y-1">
        <p class="uppercase block text-lg font-medium mb-2 dark:text-white">Subtotal:
            ${{ number_format($compra->subtotal, 2) }}</p>
        <p class="uppercase block text-lg font-medium mb-2 dark:text-white">IVA:
            ${{ number_format($compra->impuestos, 2) }}</p>
        <p class="uppercase block text-lg font-medium mb-2 dark:text-white">
            Total: ${{ number_format($compra->total, 2) }}
        </p>
    </div>
    <div class="mt-6  gap-4">
        <div class="flex justify-between items-center gap-3 mt-4">
            <a href="{{ route('compras.index')  }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
            </a>
            @if ($compra->estatus == 1)
            <div x-data @keydown.window.prevent.f9="$refs.btnRegistrar.click()">
                <a x-ref="btnRegistrar" href="{{ route('compras.edit', $compra) }}"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                    Actualizar [F9]
                </a>
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
