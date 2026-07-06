<style>
    @page {
        margin: 0;
    }
    body {
        font-family: monospace;
        font-size: 10px;
        margin-rigt:15px;
        padding: 5px;
    }

    .center {
        text-align: center;
    }
    .left {
        text-align: left;
    }
    .right {
        text-align: right;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    .mb-0{
    margin-bottom: 0px;
    margin-top: 0px;
    }
    td {
        padding: 2px 0;
    }
</style>
<div style="margin-top: 10px">
    <div class="center">
<img src="{{ public_path('images/icono.png') }}" style="width: 80px; height: 60px;" alt="Logo">        <br>
        {{-- <strong>{{ $sucursal->empresa->nombre }}</strong><br> --}}
            <p class="mb-0"><strong>{{ $empresa->nombre }}</strong></p>
        <strong>{{ $empresa->rfc }}</strong><br>
        {{ $empresa->calle ?? 'Dirección no disponible' }} #{{ $empresa->numero_exterior ?? 'S/N' }},Col. {{ $empresa->colonia ?? 'Colonia no disponible' }},CP:{{ $empresa->cp ?? 'CP no disponible' }},
        {{$empresa->ciudad??'Ciudad no disponible'}},
        {{$empresa->estado??'Estado no disponible'}}
        <br>
            ------------------------------ <br>

</div>
{{-- <div class="center">
    <strong>Sucursal: </strong> {{ $sucursal->domicilios->first()->calle ?? 'Dirección no disponible' }} #{{ $sucursal->domicilios->first()->numero_exterior ?? 'S/N' }},Col. {{ $sucursal->domicilios->first()->colonia ?? 'Colonia no disponible' }},CP:{{ $sucursal->domicilios->first()->cp ?? 'CP no disponible' }}<br>
    ------------------------------ <br>
</div> --}}
<div style="text-align:right;">
    <strong>Fecha: </strong> {{ $documento->updated_at }}<br>
</div>

<div class="" style="margin-left: 10px;">
    <span><strong>Serie: </strong>{{ $documento->serie }}   <strong>Folio: </strong>  {{ $documento->folio }}<br>
    </span>
    <strong>Cliente: </strong> {{ $documento->cliente->nombre ?? 'Nombre no disponible' }} <br>
    <strong>Cajero: </strong> {{  'Nombre no disponible' }} <br>
    <strong>Forma de pago: </strong> {{ $documento->forma_pago ?? 'No disponible' }}<br>
</div>
<div class="center">
    ------------------------------ <br>
</div>

    <table>
        <tr>
            <td><strong>Producto</strong></td>
            <td align="right"><strong>Cant.</strong></td>
            <td align="right"><strong>Importe</strong></td>
        </tr>
    </table>
<table>
@foreach($documento->detalles as $item)
    <tr>
        <td>{{ $item->producto->codigo_producto }} {{ $item->producto->nombre_producto }}</td>
        <td align="right">{{ $item->cantidad }}</td>
        <td align="right">${{ number_format($item->importe, 2) }}</td>
    </tr>
@endforeach
</table>
<div class="center">
    ------------------------------ <br>
</div>

<div class="right" style="margin-left: 10px; margin-top: 10px">
    <strong>SUBTOTAL: </strong>${{ number_format($documento->subtotal, 2) }}<br>
    <strong>IVA: </strong>${{ number_format($documento->impuestos, 2) }}<br>
    <strong>DESCUENTOS: </strong>${{ number_format($documento->descuentos, 2) }}<br>
    <strong>TOTAL: </strong>${{ number_format($documento->total, 2) }}<br>
</div>
<br>
<div style="text-align:center">
    <p class="mb-0">¡Gracias por su compra!</p>
    {{-- <p class="mb-0">ATENCIÓN A CLIENTE Y VENTAS POR TELEFONO:</p> --}}
    {{-- <p class="mb-0">{{$empresa->telefono  }}</p> --}}
    <p class="mb-0"> En caso de requerir factura:</p>
    <p class="">www.facturaejemplo.com/factura</p>
    <p class="mb-0">Código:<strong>{{ $documento->codigo_unico }}</strong> </p>
</div>
