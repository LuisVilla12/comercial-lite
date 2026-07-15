<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>
        {{ match ($documento->documento_modelo_id) {
            1 => 'Cotización',
            2 => 'Factura',
            3 => 'Remisión',
        } }}
        {{ $documento->serie }} {{ $documento->folio }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.3;
        }
        p {
            margin: 0;
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
    {{-- ================= ENCABEZADO ================= --}}
      <table class="no-border">
        <tr>
            <td width="20%">
                <img src="{{ public_path('images/icono.png') }}" style="width: 120px; height: 100px;" alt="Logo">
            </td>
            <td width="50%" class="border">
                <p class="text-center"> <strong>{{ $empresa->nombre }}</strong></p>
                <p class="text-center">{{ $empresa->rfc }}</p>
                <p class="text-center">Calle: {{ $empresa->calle ?? '' }} #{{ $empresa->numero_exterior ?? 'S/N' }}, COL.   {{ $empresa->colonia ?? '' }}</p>
                <p class="text-center">CP: {{ $empresa->cp ?? '' }} {{ $empresa->ciudad ?? ' ' }},{{ $empresa->estado ?? '' }}</p>
            </td>
            <td width="30%">
                <p class="text-center"> <strong>FACTURA I-Ingreso</strong></p>
                <p><strong>Serie: </strong> {{ $documento->serie }}<strong> Folio: </strong>{{ $documento->folio }} </p>
                <p><strong>Fecha: </strong>{{ \Carbon\Carbon::parse($documento->fecha)->format('d/m/Y') }}</p>
                <p><strong>Metodo de pago: </strong> {{ $documento->metodo_pago }}</p>
                <p><strong>Forma de pago: </strong>{{ $documento->forma_pago }} </p>
                <p><strong>Uso de CFDI: </strong> {{ $documento->uso_cfdi }} </p>
                <p><strong>Moneda: </strong> MX Peso Mexicano </p>
            </td>
        </tr>
    </table>

    {{-- ================= CLIENTE ================= --}}
    <table>
        <tr>
            <td class="bold">RECEPTOR:</td>
            <td colspan="3">
                {{ $documento->cliente->rfc }} - {{ $documento->cliente->nombre }}
            </td>
            <td class="bold">REGIMEN:</td>
            <td > {{ $documento->cliente->regimen_fiscal }} </td>
        </tr>
        <tr>
            <td colspan="6">
                @foreach ($documento->cliente->domicilios as $dom)
                    <label class="block  text-md font-medium text-gray-700 my-2">
                        <strong>Dirección:</strong>
                        <span>{{ $dom->calle . ' #' . $dom->numero_exterior . ', Col. ' . $dom->colonia . ' CP: ' . $dom->cp . ', ' . $dom->ciudad . ', ' . $dom->estado }}</span>
                    </label>
                @endforeach
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
            @foreach ($documento->detalles as $d)
                <tr>
                    <td class="text-right">{{ number_format($d->cantidad, 2) }}</td>
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
            <td width="70%" class="no-border">
                                    <strong>Observaciones:</strong> <span>{{ $documento->observaciones }} </span>
            </td>
            <td width="30%">
                <table>
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-right">${{ number_format($documento->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Descuentos:</td>
                        <td class="text-right">${{ number_format($documento->descuentos, 2) }}</td>
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
      <table class="no-border">
        <tr>
            <td width="20%">
               <img src="data:image/png;base64,{{ $qr }}" width="100">
            </td>
            <td width="80%" class="border">
            <p class=""> <span> <strong>Este documento es una representación impresa de un CFDI</span></strong> </p>
            <p> <span><strong> Serie del Certificado del emisor:</strong></span> {{ $datosXML['no_cert_emisor']??'' }}</p>
            <p> <span><strong> Folio Fiscal:</strong></span>  {{ $datosXML['uuid'] ??'' }}</p>
            <p> <span><strong> No. de serie del Certificado del SAT: </strong></span> {{ $datosXML['no_cert_sat'] ??'' }}</p>
            <p> <span><strong>  Fecha y hora de certificación: </strong></span> {{ $datosXML['fecha_timbrado']??''  }}</p>
            </td>
        </tr>
    </table>

    <br>
    <table class="max-w-100">
        <tr><td class=" text-center"><strong> Sello digital del CFDI</strong></td></tr>
        <tr>
            <td style="font-size:7px;">
    {!! nl2br(e(chunk_split($datosXML['sello_cfdi']??'' , 160, "\n"))) !!}
</td>
        </tr>
        <tr><td class="text-center"><strong> Sello del SAT</strong></td></tr>
        <tr>
            <td style="font-size:7px;">
    {!! nl2br(e(chunk_split( $datosXML['sello_sat']??'' , 160, "\n"))) !!}
</td>

        </tr>
        <tr><td class="text-center"><strong> Cadena original del complemento del certificación digital del SAT:</strong></td></tr>
        <tr>
            <td style="font-size: 7px;">
                    {!! nl2br(e(chunk_split( $documento->cadena_original ??'' , 160, "\n"))) !!}
            </td>
        </tr>
    </table>


    <br>


</body>

</html>
