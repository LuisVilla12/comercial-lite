<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Corte de Caja</title>

<style>
    *{
        margin: 10px 5px;
    }
    body {
        font-family: monospace;
        font-size: 12px;
        width: 250px;
        margin: auto;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .separator {
        border-top: 1px dashed #000;
        margin: 8px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 2px 0;
    }

    .total {
        font-weight: bold;
        font-size: 14px;
    }

    @media print {
        button {
            display: none;
        }
    }
</style>
</head>

<body>

<div class="center">
    <h3>{{ $caja->sucursal->nombre }}</h3>
    <strong>CORTE DE CAJA</strong>
</div>

<div class="separator"></div>

<table>
    <tr>
        <td>Usuario:</td>
        <td class="right">{{ auth()->user()->name }}</td>
    </tr>

    <tr>
        <td>Apertura:</td>
        <td class="right">
            {{ $caja->fecha_apertura->format('d/m/Y H:i') }}
        </td>
    </tr>

    <tr>
        <td>Cierre:</td>
        <td class="right">
            {{ now()->format('d/m/Y H:i') }}
        </td>
    </tr>
</table>

<div class="separator"></div>

<table>
    <tr>
        <td>Fondo Inicial</td>
        <td class="right">
            ${{ number_format($caja->monto_inicial, 2) }}
        </td>
    </tr>
</table>

<div class="separator"></div>

<div class="center">
    <strong>VENTAS POR FORMA DE PAGO</strong>
</div>

<table>

    @foreach($ventas as $venta)

    <tr>
        <td>
            @switch($venta->forma_pago)

                @case('01')
                    Efectivo
                    @break

                @case('02')
                    Cheque
                    @break

                @case('03')
                    Transferencia
                    @break

                @case('04')
                    T. Crédito
                    @break

                @case('28')
                    T. Débito
                    @break

                @default
                    Otro

            @endswitch
        </td>

        <td class="right">
            ${{ number_format($venta->total, 2) }}
        </td>
    </tr>

    @endforeach

</table>

<div class="separator"></div>

<table>
    <tr class="total">
        <td>TOTAL VENDIDO</td>
        <td class="right">
            ${{ number_format($totalVentas, 2) }}
        </td>
    </tr>
</table>

<div class="separator"></div>

<div class="center">
    Firma del Cajero
    <br><br><br>
    ______________________
</div>

<br>

<div class="center">
    {{ now()->format('d/m/Y H:i:s') }}
</div>

</body>

</html>
