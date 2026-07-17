@section('title', content: 'Detalles de ajuste')

<x-app-layout>
    <div class="flex items-center mt-4 py-2 gap-3 mb-4 bg-white dark:bg-slate-800  rounded-md w-full">
        <a href="{{ route('ajustes-almacen.index', $ajuste->tipo) }}" class="flex text-white  bg-red-600 border-1  rounded-lg p-4">
            <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />Regresar
        </a>
        <div class="md:flex md:justify-between items-center w-full">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                    @if ($ajuste->tipo == 1)
                        Entradas de almacen
                    @else
                        Salidas de almacen
                    @endif # {{ $ajuste->id }}
                </h2>
                <p class="dark:text-white mt-2 font-semibold"> <span> Fecha:
                        {{ \Carbon\Carbon::parse($ajuste->fecha)->format('d/m/Y') }}</span></p>
            </div>
            <div class="mt-2 md:mt-0 mb-4 md:mb-0">
                <p class="dark:text-white font-semibold">Estado: @php
                    $estatusText = match ($ajuste->estatus) {
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
        @if ($ajuste->estatus == 1)
            <!-- Surtir -->
            <button onclick="surtirAjuste()"
                class="group w-24 rounded-xl border border-orange-500/30 bg-orange-500/10 dark:bg-orange-500/50 hover:bg-orange-500 hover:text-white transition-all duration-200">
                <div class="flex flex-col items-center py-1">
                    <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2 text-orange-400 group-hover:text-white" />
                    <span class="mt-1 font-semibold text-sm dark:text-white">Surtir <span
                            class="text-xs opacity-70">F1</span></span>
                </div>
            </button>

            <form method="POST" action="{{ route('ajustes-almacen.surtir', $ajuste) }}" class="hidden"
                id="formSurtirAJUSTE">
                @csrf
            </form>
        @endif
        <!-- Imprimir -->
        <a href="{{ route('ajustes-almacen.pdf', $ajuste) }}" target="_blank"
            class="flex items-center justify-center group w-24 rounded-xl border border-blue-500/30 bg-blue-500/10 dark:bg-blue-500/50 hover:bg-blue-500 hover:text-white transition-all duration-200">
            <div class="flex flex-col items-center">
                <x-heroicon-o-printer class="w-5 h-5 mr-2 text-blue-400 group-hover:text-white" />
                <span class="mt-1 font-semibold text-sm dark:text-white">Imprimir<span
                        class=" text-xs opacity-70">F4</span></span>
            </div>
        </a>
    </div>

    <div class="my-2 p-2 bg-white dark:bg-slate-800  rounded-md ">
        {{-- almacen --}}
        <div>
            <label class="block text-lg font-medium mb-2 dark:text-white">Almacen : {{ $ajuste->almacen->nombre }}</label>
        </div>
    </div>
    <div class="w-full  bg-white dark:bg-slate-800  rounded-md p-2 ">
        <label class="block text-lg font-medium mb-2 dark:text-white">Listado de productos </label>

        <!-- ===== TABLA (DESKTOP) ===== -->
        <div class="hidden md:block">
            <table class="w-full border bg-white shadow rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Código</th>
                        <th class="p-2">Producto</th>
                        <th class="p-2">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ajuste->detalles as $detalle)
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- ===== CARDS (MÓVIL) ===== -->
        <div class="md:hidden space-y-4">
            @foreach ($ajuste->detalles as $detalle)
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

                    </div>

                </div>
            @endforeach
        </div>

    </div>
    <div class="mt-6  gap-4">
        <div class="flex justify-end gap-3 mt-4">

            @if ($ajuste->estatus == 1)
                <a href="" class="px-6 py-2 uppercase bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                    Actualizar
                </a>
            @endif
        </div>

    </div>
</x-app-layout>
<script>
    function surtirAjuste() {
        Swal.fire({
            title: '¿Deseas surtir el ajuste de inventario?',
            text: "Al surtir el ajuste, se marcará como surtida y no podrá ser editada. Esta acción es irreversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, surtir ajuste',
            cancelButtonText: 'No, cancelar',
            reverseButtons: true
        }).then((confirmResult) => {
            if (confirmResult.isConfirmed) {
                document.getElementById('formSurtirAJUSTE').submit();
            }
        });
    }
</script>
