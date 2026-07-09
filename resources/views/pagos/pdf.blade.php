<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>
        REP
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

        h3 {
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
                <p class="text-center">Calle: {{ $empresa->calle ?? '' }} #{{ $empresa->numero_exterior ?? 'S/N' }},
                    COL. {{ $empresa->colonia ?? '' }}</p>
                <p class="text-center">CP: {{ $empresa->cp ?? '' }}
                    {{ $empresa->ciudad ?? ' ' }},{{ $empresa->estado ?? '' }}</p>
            </td>
            <td width="30%">

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
            <td> {{ $documento->cliente->regimen_fiscal }} </td>
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

    {{-- DATOS DEL PAGO --}}
    <p class="text-center"> <strong>DATOS DEL PAGO</strong></p>
    <table>
        <tr>
            <td>
                <strong>Fecha de pago: </strong>{{ \Carbon\Carbon::parse($documento->fecha)->format('d/m/Y') }}
            </td>
            <td>
<strong>Forma de pago: </strong>{{ $documento->forma_pago }}
            </td>
            <td>
                <p><strong>Moneda: </strong> MX Peso Mexicano </p>
            </td>
        </tr>
        <tr>
             <td>
                <strong>Monto:  $</strong>{{  $documento->monto}}
            </td>
            <td>
                <strong>Folio: </strong>{{ $documento->folio }}

            </td>
            <td>
                <strong>Tipo de cambio: </strong>1

            </td>
        </tr>
    </table>
    {{-- ================= DOCUMENTOS ================= --}}
    <br>
    <p class="text-center"> <strong>DOCUMENTOS PAGADOS</strong></p>
    <table>
        <thead>
            <tr>
                <th>UUID</th>
                <th>Serie</th>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Monto Pagado</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($documento->detalles as $d)
                <tr>
                    <td class="text-right">{{ $d->documento->uuid }}</td>
                    <td class="text-right">{{ $d->documento->serie }}</td>
                    <td>{{ $d->documento->folio }}</td>
                    <td>{{ $d->documento->fecha }}</td>
                    <td class="text-right">${{ number_format($d->documento->total, 2) }}</td>
                    <td class="text-right">${{ number_format($d->monto, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

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
                <p class=""> <span> <strong>Este documento es una representación impresa de un
                            CFDI</span></strong> </p>
                <p> <span><strong> Serie del Certificado del emisor:</strong></span>
                    {{ $datosXML['no_cert_emisor'] ?? '' }}</p>
                <p> <span><strong> Folio Fiscal:</strong></span> {{ $datosXML['uuid'] ?? '' }}</p>
                <p> <span><strong> No. de serie del Certificado del SAT: </strong></span>
                    {{ $datosXML['no_cert_sat'] ?? '' }}</p>
                <p> <span><strong> Fecha y hora de certificación: </strong></span>
                    {{ $datosXML['fecha_timbrado'] ?? '' }}</p>
            </td>
        </tr>
    </table>

    <br>
    <table class="max-w-100">
        <tr>
            <td class=" text-center"><strong> Sello digital del CFDI</strong></td>
        </tr>
        <tr>
            <td style="font-size:7px;">
                {!! nl2br(e(chunk_split($datosXML['sello_cfdi'] ?? '', 160, "\n"))) !!}
            </td>
        </tr>
        <tr>
            <td class="text-center"><strong> Sello del SAT</strong></td>
        </tr>
        <tr>
            <td style="font-size:7px;">
                {!! nl2br(e(chunk_split($datosXML['sello_sat'] ?? '', 160, "\n"))) !!}
            </td>

        </tr>
        <tr>
            <td class="text-center"><strong> Cadena original del complemento del certificación digital del SAT:</strong>
            </td>
        </tr>
        <tr>
            <td style="font-size: 7px;word-break: break-all; white-space: normal;">
                {{ $documento->cadena_original ?? '' }}
            </td>
        </tr>
    </table>


    <br>


</body>

</html>
