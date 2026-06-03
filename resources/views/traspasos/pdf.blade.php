<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>

        {{ $traspaso->serie }} {{ $traspaso->folio }}</title>


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

    {{-- ================= ENCABEZADO ================= --}}
    <table class="no-border">
        <tr>
            <img src="{{ public_path('images/logo.jpeg') }}" style="width: 200px; height: 60px;" alt="Logo">
            <td class="text-right bold">
                <strong>Folio: {{ $traspaso->folio }}</strong> <br>
            </td>
        </tr>
        <tr>
        </tr>
    </table>

    <br>

    {{-- ================= DATOS EMPRESA ================= --}}
    <table class="no-border">
        <tr>
            <td width="60%">
                {{ $empresa->domicilios->first()->calle ?? 'Dirección no disponible' }}
                #{{ $empresa->domicilios->first()->numero_exterior ?? 'S/N' }},<br>
                {{ $empresa->domicilios->first()->colonia ?? 'Colonia no disponible' }}
                {{ $empresa->domicilios->first()->ciudad ?? 'Ciudad no disponible' }},
                {{ $empresa->domicilios->first()->estado ?? 'Estado no disponible' }} <br>
                {{ $empresa->domicilios->first()->cp ?? 'CP no disponible' }},<br>
                Mexico <br>
            </td>
            <td width="40%">
                Fecha: {{ \Carbon\Carbon::parse($traspaso->fecha)->format('d/m/Y') }}<br>
                @if($traspaso->vigencia!= null)
                    Vigencia: {{ \Carbon\Carbon::parse($traspaso->vigencia)->format('d/m/Y') }}<br>
                @endif
                Realizado por: {{ $traspaso->usuario->name ?? 'Nombre no disponible' }}
            </td>
        </tr>
    </table>

    <br>

    {{-- ================= Datos del transpaso ================= --}}
    <table>
        <tr>
            <td class="bold">Almacén origen: {{ $traspaso->almacenOrigen->nombre ?? 'Nombre no disponible' }}</td>
            <td class="bold">Almacén destino: {{ $traspaso->almacenDestino->nombre ?? 'Nombre no disponible' }}</td>
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
            @foreach ($traspaso->detalles as $d)
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
            <td width="70%" class="no-border"></td>
            <td width="30%">
                <table>
                    <tr>
                        <td>Subtotal:</td>
                        {{-- ${{ number_format($traspaso->subtotal, 2) }} --}}
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        <td>Descuentos:</td>
                        <td class="text-right">$0.00</td>
                    </tr>
                    <tr>
                        <td>I.V.A (16%):</td>
                        {{-- ${{ number_format($traspaso->impuestos, 2) }}--}}
                        <td class="text-right"></td>
                    </tr>
                    <tr class="bold">
                        <td>Total:</td>
                        {{-- ${{ number_format($traspaso->total, 2) }} --}}
                        <td class="text-right"></td>
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
                {{-- {{ \App\Helpers\NumeroALetras::convertir($traspaso->total) }} --}}
            </td>
        </tr>
    </table>
    <br>
    {{-- ================= BANCOS ================= --}}
    <table class="no-border small">
        <tr>
            <td>
                <strong>DEPOSITOS Y TRANSFERENCIAS</strong><br>
                Banco: <strong> {{ $banco->nombre_banco }}</strong> <br>
                Cuenta: <strong>{{ $banco->cuenta_bancaria }} </strong><br>
                CLABE: <strong>{{ $banco->clabe }}</strong>
            </td>
        </tr>
    </table>

    <br>

    {{-- ================= CONTACTO ================= --}}
    <table class="no-border small">
        <tr>
            <td>
                <strong>DATOS DE CONTACTO:</strong><br>
                WhatsApp:<strong> {{ $banco->whatsapp }} </strong><br>
                Correo: <strong>{{ $banco->correo_electronico }} </strong>
            </td>
        </tr>
    </table>

    <br>

    <p class="small" style="text-align: center;">
        <strong>
            PRECIOS SUJETOS A CAMBIO SIN PREVIO AVISO. MATERIAL SUJETO A DISPONIBILIDAD. NO ES UN COMPROBANTE FISCAL
        </strong>
    </p>

</body>

</html>
