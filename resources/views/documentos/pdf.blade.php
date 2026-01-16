<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cotización {{ $documento->serie }} {{ $documento->folio }}</title>

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
th, td {
    border: 1px solid #000;
    padding: 4px;
}
.no-border td {
    border: none;
}
.text-right { text-align: right; }
.text-center { text-align: center; }
.bold { font-weight: bold; }
.small { font-size: 10px; }
</style>
</head>

<body>

{{-- ================= ENCABEZADO ================= --}}
<table class="no-border">
    <tr>
        <td class="bold">CARDENAS E HIJOS</td>
        <td class="text-right bold">COTIZACIÓN</td>
    </tr>
    <tr>
        <td>CHI961130ME0</td>
        <td class="text-right">
            Cotización Número:<br>
            <strong>{{ $documento->serie }} {{ $documento->folio }}</strong>
        </td>
    </tr>
</table>

<br>

{{-- ================= DATOS EMPRESA ================= --}}
<table class="no-border">
    <tr>
        <td width="60%">
            AV. ORIZABA No. 623<br>
            Col. Obrero Campesina<br>
            C.P. 91020<br>
            Xalapa-Enríquez, Veracruz
        </td>
        <td width="40%">
            Fecha: {{ \Carbon\Carbon::parse($documento->fecha)->format('d/M/Y') }}<br>
            Expedida en: (Ninguno)<br>
            Vendedor:
        </td>
    </tr>
</table>

<br>

{{-- ================= CLIENTE ================= --}}
<table>
    <tr>
        <td class="bold">CLIENTE:</td>
        <td colspan="5">
            {{ $documento->cliente->rfc }} - {{ $documento->cliente->nombre }}
        </td>
    </tr>
    <tr>
        <td colspan="6">
            {{ $documento->cliente->direccion ?? '' }}
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
            <th>Valor unitario</th>
            <th>Impuestos</th>
            <th>Importe</th>
        </tr>
    </thead>
    <tbody>
        @foreach($documento->detalles as $d)
        <tr>
            <td class="text-right">{{ number_format($d->cantidad, 6) }}</td>
            <td class="text-center">PZ</td>
            <td>{{ $d->producto->codigo_producto }}</td>
            <td>{{ $d->producto->nombre_producto }}</td>
            <td class="text-right">${{ number_format($d->costo_unitario, 2) }}</td>
            <td class="text-right">IVA</td>
            <td class="text-right">${{ number_format($d->importe, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>

{{-- ================= TOTALES ================= --}}
<table>
    <tr>
        <td width="70%" class="no-border"></td>
        <td width="30%">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">${{ number_format($documento->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Descuentos:</td>
                    <td class="text-right">$0.00</td>
                </tr>
                <tr>
                    <td>I.V.A (16%):</td>
                    <td class="text-right">${{ number_format($documento->impuestos, 2) }}</td>
                </tr>
                <tr class="bold">
                    <td>Total:</td>
                    <td class="text-right">${{ number_format($documento->total, 2) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br>

{{-- ================= TOTAL CON LETRA ================= --}}
<table>
    <tr>
        <td class="bold">Total con letra:</td>
    </tr>
    <tr>
        <td>
            {{ \App\Helpers\NumeroALetras::convertir($documento->total) }}
        </td>
    </tr>
</table>

<br>

{{-- ================= BANCOS ================= --}}
<table class="no-border small">
<tr>
<td>
<strong>DEPOSITOS Y TRANSFERENCIAS</strong><br>
BANAMEX Sucursal: 101<br>
Cuenta: 36137-7<br>
CLABE: 002840010103613775
</td>
</tr>
</table>

<br>

{{-- ================= CONTACTO ================= --}}
<table class="no-border small">
<tr>
<td>
WhatsApp: 22.82.43.88.56<br>
e-Mail: cardenasorizaba@hotmail.com<br>
</td>
</tr>
</table>

<br>

<p class="small">
PRECIOS SUJETOS A CAMBIO SIN PREVIO AVISO. MATERIAL SUJETO A DISPONIBILIDAD.
</p>

</body>
</html>
