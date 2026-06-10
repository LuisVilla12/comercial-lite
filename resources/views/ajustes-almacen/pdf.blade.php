<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>
        {{ match ($ajuste->tipo) {
            1 => 'Cotización',
            2 => 'Factura',
        } }}
        {{ $ajuste->id }}</title>

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
                Fecha: {{ \Carbon\Carbon::parse($ajuste->fecha)->format('d/m/Y') }}<br>
                Usuario: {{ $ajuste->usuario->name ?? 'Nombre no disponible' }}
            </td>
        </tr>
    </table>

    <br>

    {{-- ================= CLIENTE ================= --}}
    <table>
        <tr>
            <td class="bold"> Ajuste de @if ($ajuste->tipo == 1)
                Entradas
            @else
                Salidas
            @endif #{{ $ajuste->id }}</td>
            <td class="bold">ALMACEN: {{ $ajuste->almacen->nombre }}</td>
        </tr>
    </table>

    <br>

    {{-- ================= PRODUCTOS ================= --}}
    <table>
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Clave Prod.</th>
                <th>Descripción</th>
                </tr>
        </thead>
        <tbody>
            @foreach ($ajuste->detalles as $d)
                <tr>
                    <td class="text-right">{{ number_format($d->cantidad, 2) }}</td>
                    <td class="text-center">PZ</td>
                    <td>{{ $d->producto->codigo_producto }}</td>
                    <td>{{ $d->producto->nombre_producto }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <br>

</body>

</html>
