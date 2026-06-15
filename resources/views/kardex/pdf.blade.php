<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title> KARDEX</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        .no-border td {
            border: none;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .small {
            font-size: 10px;
        }
    </style>
</head>

<body>
    <br>

    {{-- ================= DATOS EMPRESA ================= --}}
    <table class="no-border">
        <tr>
            <td width="40%">
                {{-- Fecha: {{ \Carbon\Carbon::parse($ajuste->fecha)->format('d/m/Y') }}<br> --}}
            </td>
        </tr>
    </table>

    <br>

    {{-- ================= CLIENTE ================= --}}
    <table>
        <tr>
            <td class="bold">Producto: {{ $producto->nombre_producto  }}</td>
            <td class="bold">Producto: {{ $producto->codigo_producto  }}</td>
         </tr>
        <tr>
            <td class="mt-2 text-sm text-gray-700">
                        <strong>Fecha de inicio:</strong>
                        {{ request('fecha_inicio')}}
                    </td>
                    <td class="mt-2 text-sm text-gray-700">
                        <strong>Fecha de fin:</strong>
                        {{ request('fecha_fin')}}
                    </td>
        </tr>
    </table>

    <br>

    {{-- ================= PRODUCTOS ================= --}}

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
                                        {{  $movimiento['serie'] . " ". $movimiento['referencia'] }}
                                        @if ($movimiento['tipo']=='Compra')
                                            <a href="{{route('compras.show',$movimiento['id'])}}">
                                            </a>
                                        @elseif ($movimiento['tipo']=='Traspaso')
                                            <a href="{{route('traspasos.show',$movimiento['id'])}}">
                                            </a>
                                        @elseif ($movimiento['tipo']=='Entrada Almacen')
                                            <a href="{{route('ajustes-almacen.show',$movimiento['id'])}}">
                                            </a>
                                        @elseif ($movimiento['tipo']=='Salida Almacen')
                                            <a href="{{route('ajustes-almacen.show',$movimiento['id'])}}">
                                            </a>
                                        @elseif ($movimiento['tipo']=='Venta')
                                            <a href="{{ route('documentos.show', ['sucursal' => $movimiento['sucursal'], 'documento' => $movimiento['id']]) }}"
                                        class="inline-flex items-center gap-1 text-gray-600 hover:text-blue-600 transition">
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


    <br>

</body>

</html>
