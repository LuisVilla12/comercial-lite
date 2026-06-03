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
<div class="mt-5 flex justify-end gap-4">
    <a href="{{ route('traspasos.pdf', [$traspaso]) }}" target="_blank"
                    class="px-4 py-2 bg-red-600 text-white rounded flex items-center ml-6">
                    <x-heroicon-o-printer class="w-5 h-5 mr-2" /> Imprimir carta
                </a>
    @if ($traspaso->estatus == 1)
                <form method="POST" action="{{ route('traspasos.surtir', $traspaso) }}" class=" flex justify-end">
                    @csrf
                    <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                        REALIZAR TRASPASO
                    </button>
                </form>
            @else
            <div class="flex justify-end">
                <a class="block md:inline-block px-6 py-2 bg-green-600 text-white rounded"> TRASPASO REALIZADO</a>

            </div>
            @endif

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
    <div class="mt-6 flex gap-4">
        <div class="md:col-span-2 flex justify-between gap-3 mt-4">
            <a href=" {{ route('traspasos.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">
                Volver
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
