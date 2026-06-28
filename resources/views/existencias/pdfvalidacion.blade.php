<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>POR SURTIR</title>

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
                Fecha: {{ now()->format('d/m/Y') }}
<br>
             </td>
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
                <th colspan="2">Almacen</th>
                </tr>
        </thead>
        <tbody>
            @foreach ($existencias as $d)
                <tr>
                    <td class="text-right">{{ $d->cantidad }}</td>
                    <td class="text-center">PZ</td>
                    <td> {{ $d->producto->codigo_producto }}</td>
                    <td>{{ $d->producto->nombre_producto }}</td>
                    <td colspan="2">{{ $d->almacen->nombre }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <br>

</body>

</html>
