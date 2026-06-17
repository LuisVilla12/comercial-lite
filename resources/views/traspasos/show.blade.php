@section('title', 'Ver traspaso')

<x-app-layout>
    <x-slot name="header">
        <div class="">
            <div class="flex justify-between">
                <h2 class="mb-2 font-semibold text-xl text-gray-800 dark:text-gray-200">
                    Traspaso # {{ $traspaso->id }}
                </h2>
                <h2 class="mb-2 font-semibold text-xl text-gray-800 dark:text-gray-200">
                    Fecha: {{ $traspaso->fecha }}
                </h2>
            </div>

        </div>

    </x-slot>
    @if (session('open_pdf'))
        {{-- <script>
            if (confirm('¿Deseas imprimir el traspaso?')) {
                // window.open("{{ route('documentos.pdf', $documento) }}", "_blank");
            }
        </script> --}}
    @endif
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



    {{-- ORIGEN --}}
    <div class="mt-5  gap-4">
        <div class="flex justify-between gap-4">
            <p class="dark:text-white">Estado: @php
                $estatusText = match ($traspaso->estatus) {
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
        <div class="flex gap-4">
        @if ($traspaso->estatus == 1)
            <div>
                <button onclick="surtirTraspaso()"
                    class="flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded w-full mb-4 md:mb-0 ">
                    <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2" />
                    Surtir
                </button>
            </div>

            <form method="POST" id="formSurtirTraspaso" action="{{ route('traspasos.surtir', $traspaso) }}"
                class=" flex justify-end">
                @csrf
            </form>
        @endif
        <a href="{{ route('traspasos.pdf', [$traspaso]) }}" target="_blank"
            class="px-4 py-2 bg-blue-600 text-white rounded flex items-center ">
            <x-heroicon-o-printer class="w-5 h-5 mr-2" /> Imprimir carta
        </a>
        </div>
        </div>
    </div>
    <div class="grid  md:grid-cols-2 md:gap-6 mt-6">
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

    <table class="w-full border bg-white shadow rounded">
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
        <div class="md:flex justify-between gap-3 mt-4">
  <a href="{{ route('traspasos.index')  }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
            </a>
            @if ($traspaso->estatus == 1)
                <a href="{{ route('traspasos.edit', $traspaso) }}"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white  rounded-md font-medium">
                    Actualizar
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
