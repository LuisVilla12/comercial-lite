<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg">

                {{-- Encabezado --}}
                <div class="px-6 py-4 border-b">
                    <h2 class="text-2xl font-bold">
                        Kardex del Producto ({{$tipo}})
                    </h2>

                    <div class="mt-2 text-sm text-gray-700">
                        <strong>Nombre del producto:</strong>
                        {{ $producto->nombre_producto . " (".   $producto->codigo_producto . ")"}}
                    </div>

                    <div class="mt-1 text-sm text-gray-700">
                        <strong>Total de movimientos:</strong>
                        {{ $detalles->count() }}
                    </div>
                </div>

                <div class="p-6 overflow-x-auto">

                    @php
                        $saldo = 0;
                        $totalEntradas = 0;
                        $totalSalidas = 0;
                    @endphp

                    <table class="min-w-full border border-gray-300 text-sm">

                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-3 py-2 text-left">
                                    Fecha
                                </th>

                                <th class="border px-3 py-2 text-left">
                                    Tipo
                                </th>

                                <th class="border px-3 py-2 text-center">
                                    Referencia
                                </th>

                                <th class="border px-3 py-2 text-left">
                                    Descripción
                                </th>

                                <th class="border px-3 py-2 text-right">
                                    Movimiento
                                </th>

                                <th class="border px-3 py-2 text-right">
                                    Entrada
                                </th>

                                <th class="border px-3 py-2 text-right">
                                    Salida
                                </th>

                                <th class="border px-3 py-2 text-right">
                                    Saldo
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            {{-- Saldo inicial --}}
                            <tr class="bg-gray-50 font-semibold">
                                <td colspan="7" class="border px-3 py-2 text-right">
                                    Saldo inicial
                                </td>

                                <td class="border px-3 py-2 text-right">
                                    {{ number_format($saldo, 2) }}
                                </td>
                            </tr>

                            @forelse($detalles as $movimiento)

                                @php
                                    $saldo += $movimiento['entrada'];
                                    $saldo -= $movimiento['salida'];

                                    $totalEntradas += $movimiento['entrada'];
                                    $totalSalidas += $movimiento['salida'];
                                @endphp

                                <tr class="hover:bg-gray-50">

                                    <td class="border px-3 py-2">
                                        {{ \Carbon\Carbon::parse($movimiento['fecha'])->format('d/m/Y') }}
                                    </td>

                                    <td class="border px-3 py-2">
                                        {{ $movimiento['tipo'] }}
                                    </td>

                                    <td class="border px-3 py-2 text-center flex items-center justify-center">
                                        {{  $movimiento['serie'] . "-". $movimiento['referencia'] }}
                                        @if ($movimiento['tipo']=='Compra')
                                            <a href="{{route('compras.show',$movimiento['id'])}}"> 
                                                <x-heroicon-o-document class="w-4 h-4" />
                                            </a>
                                        @elseif ($movimiento['tipo']=='Traspaso')
                                            <a href="{{route('traspasos.show',$movimiento['id'])}}"> 
                                                <x-heroicon-o-document class="w-4 h-4" />
                                            </a>
                                        @elseif ($movimiento['tipo']=='Documento')
                                            <a href="{{ route('documentos.show', ['sucursal' => $movimiento['sucursal'], 'documento' => $movimiento['id']]) }}"
                                        class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
                                                <x-heroicon-o-document class="w-4 h-4" />
                                    </a>
                                        @endif
                                        
                                    </td>

                                    <td class="border px-3 py-2">
                                        {{ $movimiento['descripcion'] }}
                                    </td>

                                    <td class="border px-3 py-2 text-right">
                                        @if(!is_null($movimiento['movimiento']))
                                            {{ number_format($movimiento['movimiento'], 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="border px-3 py-2 text-right">
                                        {{ number_format($movimiento['entrada'], 2) }}
                                    </td>

                                    <td class="border px-3 py-2 text-right">
                                        {{ number_format($movimiento['salida'], 2) }}
                                    </td>

                                    <td class="border px-3 py-2 text-right font-semibold">
                                        {{ number_format($saldo, 2) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8"
                                        class="border px-3 py-4 text-center text-gray-500">

                                        No se encontraron movimientos para el período seleccionado.

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                        @if($detalles->count() > 0)

                            <tfoot class="bg-gray-100 font-semibold">

                                <tr>

                                    <td colspan="5"
                                        class="border px-3 py-2 text-right">

                                        Totales

                                    </td>

                                    <td class="border px-3 py-2 text-right">
                                        {{ number_format($totalEntradas, 2) }}
                                    </td>

                                    <td class="border px-3 py-2 text-right">
                                        {{ number_format($totalSalidas, 2) }}
                                    </td>

                                    <td class="border px-3 py-2 text-right">
                                        {{ number_format($saldo, 2) }}
                                    </td>

                                </tr>

                            </tfoot>

                        @endif

                    </table>

                </div>

            </div>

        </div>
    </div>
     <div class="flex justify-between items-center gap-3 mt-4">
            <a href="{{ route('kardex.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">
                Volver
            </a>
        </div>
</x-app-layout>