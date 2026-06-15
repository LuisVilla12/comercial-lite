@section('title', content: 'Detalles de ajuste')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Detalles sobre ajuste de inventario
        </h2>
    </x-slot>

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
    <h1 class="block text-lg font-medium mb-2 dark:text-white mt-4">
        @if ($ajuste->tipo == 1)
            Entradas de almacen
        @else
            Salidas de almacen
        @endif #{{ $ajuste->id }}
    </h1>
    <div class="display  mt-6">
        <div class="flex flex justify-between gap-5">
            <p class="dark:text-white">Estado: @php
                $estatusText = match ($ajuste->estatus) {
                    1 => 'ACTIVO',
                    2 => 'TRANSFORMADA',
                    3 => 'CANCELADA',
                    4 => 'SURTIDO',
                    5 => 'DEVOLUCIÓN APLICADA',
                    default => 'DESCONOCIDO',
                };
            @endphp
                <span class="font-bold text-green-600">{{ $estatusText }}</span>
            </p>
            <div>
                <a href="{{ route('ajustes-almacen.pdf', $ajuste) }}" target="_blank"
                    class="px-4 py-2 bg-red-600 text-white rounded flex items-center ml-6">
                    <x-heroicon-o-printer class="w-5 h-5 mr-2" /> Imprimir
                </a>
                @if ($ajuste->estatus == 1)
                    <div>
                        <button onclick="surtirAjuste()"
                            class="flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded w-full mb-4 md:mb-0 ">
                            <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2" />
                            Surtir
                        </button>
                    </div>
                    <form method="POST" action="{{ route('ajustes-almacen.surtir', $ajuste) }}" class="hidden"
                        id="formSurtirAJUSTE">
                        @csrf
                    </form>
                @endif
            </div>


        </div>

    </div>

    <div class="grid md:grid-cols-2 md:gap-4 mb-4">
        {{-- almacen --}}
        <div>
            <label class="block text-lg font-medium mb-2 dark:text-white">Almacen : </label>
            <input type="text" value="{{ $ajuste->almacen->nombre }}" disabled
                class="w-full border rounded p-2 bg-gray-100">
        </div>
    </div>
    <div class="w-full">
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
            <a href="{{ route('ajustes-almacen.index', $ajuste->tipo) }}"
                class="px-4 py-2 bg-gray-500 text-white rounded">
                Volver
            </a>
            @if ($ajuste->estatus == 1)
                <a href="" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                    Actualizar
                </a>
            @endif
        </div>

    </div>
</x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
