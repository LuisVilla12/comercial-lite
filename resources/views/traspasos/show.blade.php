@section('title', 'Ver traspaso')

<x-app-layout>

    <div class="flex items-center mt-4 py-2 gap-3 mb-4 bg-white dark:bg-slate-800 w-full rounded-md">
        <a href="{{ route('traspasos.index') }}" class="flex text-white  bg-red-600 border-1  rounded-lg p-4">
            <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />Regresar
        </a>
        <div class="md:flex md:justify-between items-center w-full">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                    Detalles del Traspaso # {{ $traspaso->id }}
                </h2>
                <p class="dark:text-white mt-2 font-semibold"> <span> Fecha:
                        {{ \Carbon\Carbon::parse($traspaso->fecha)->format('d/m/Y') }}</span></p>
            </div>
            <div class="mt-2 md:mt-0 mb-4 md:mb-0">
                <p class="dark:text-white font-semibold">Estado: @php
                    $estatusText = match ($traspaso->estatus) {
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
        @if ($traspaso->estatus == 1)
        <!-- Surtir -->
        <button onclick="surtirTraspaso()"
            class="group w-24 rounded-xl border border-orange-500/30 bg-orange-500/10 dark:bg-orange-500/50 hover:bg-orange-500 hover:text-white transition-all duration-200">
            <div class="flex flex-col items-center py-1">
                <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2 text-orange-400 group-hover:text-white" />
                <span class="mt-1 font-semibold text-sm dark:text-white">Surtir <span
                        class="text-xs opacity-70">F1</span></span>
            </div>
        </button>
        <form method="POST" id="formSurtirTraspaso" action="{{ route('traspasos.surtir', $traspaso) }}" class="hidden">
        @csrf
        </form>
        @endif
        <!-- Imprimir -->
        <a class="flex items-center justify-center group w-24 rounded-xl border border-blue-500/30 bg-blue-500/10 dark:bg-blue-500/50 hover:bg-blue-500 hover:text-white transition-all duration-200" href="{{ route('traspasos.pdf', [$traspaso]) }}" target="_blank">
            <div class="flex flex-col items-center">
                <x-heroicon-o-printer class="w-5 h-5 mr-2 text-blue-400 group-hover:text-white" />
                <span class="mt-1 font-semibold text-sm dark:text-white">Imprimir<span
                        class=" text-xs opacity-70 ml-1">F4</span></span>
            </div>
        </a>
    </div>
    <div class="grid  md:grid-cols-2 md:gap-6 mt-4 bg-white dark:bg-slate-800  rounded-md p-2 ">
        {{-- ================= Almacen origen ================= --}}
        <div class="mb-4">
            <div class="">
                <label class="block text-lg font-medium mb-2 dark:text-white">Almacen salida: *</label>
                <input type="text" value="{{ $traspaso->almacenOrigen->nombre }}" disabled
                    class="w-full border rounded p-2 bg-gray-100">

            </div>
        </div>
        {{-- Almacen destino --}}
        <div class="mb-6">
            <div class="">
                <label class="block text-lg font-medium mb-2 dark:text-white">Almacen entrada: *</label>
                <input type="text" value="{{ $traspaso->almacenDestino->nombre }}" disabled
                    class="w-full border rounded p-2 bg-gray-100">

            </div>
        </div>
    </div>

    <table class="w-full mt-2 border bg-white shadow rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Código</th>
                <th class="p-2">Producto</th>
                <th class="p-2">Cantidad</th>
                {{-- <th class="p-2">Costo</th>
                <th class="p-2">Importe</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($traspaso->detalles as $detalle)
                <tr class="border-t">
                    <td class="p-2 text-center">{{ $detalle->producto->codigo_producto }}</td>
                    <td class="p-2">{{ $detalle->producto->nombre_producto }}</td>
                    <td class="p-2 text-center">{{ $detalle->cantidad }}</td>
                    {{-- <td class="p-2 text-right">
                    ${{ number_format($detalle->costo_unitario, 2) }}
                </td>
                <td class="p-2 text-right">
                    ${{ number_format($detalle->importe, 2) }}
                </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <div class="text-right mt-6 space-y-1">
        <p class="uppercase block text-lg font-medium mb-2 dark:text-white">Subtotal:
            ${{ number_format($documento->subtotal, 2) }}</p>
        <p class="uppercase block text-lg font-medium mb-2 dark:text-white">IVA:
            ${{ number_format($documento->impuestos, 2) }}</p>
        <p class="uppercase block text-lg font-medium mb-2 dark:text-white">
            Total: ${{ number_format($documento->total, 2) }}
        </p>
    </div> --}}
    <div class="mt-6">
        <div class="flex justify-end gap-3 mt-4">
            @if ($traspaso->estatus == 1)
                <a href="{{ route('traspasos.edit', $traspaso) }}"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                    Actualizar
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
<script>
    function surtirTraspaso() {
        Swal.fire({
            title: '¿Deseas surtir el traspaso?',
            text: "Al surtir el traspaso, se marcará como surtido y no podrá ser editado ni convertido nuevamente. Esta acción es irreversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, surtir traspaso',
            cancelButtonText: 'No, cancelar',
            reverseButtons: true
        }).then((confirmResult) => {
            if (confirmResult.isConfirmed) {
                document.getElementById('formSurtirTraspaso').submit();
            }
        });
    }
</script>
