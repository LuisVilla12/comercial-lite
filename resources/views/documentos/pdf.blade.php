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
                {{-- {{ match ($documento->documento_modelo_id) {
                    1 => 'Cotización ',
                    2 => 'Factura ',
                    3 => 'Remisión ',
                } }} --}}
                <strong>Folio: {{ $documento->folio }}</strong> <br>
                <strong>Estatus: Vigente</strong>
            </td>
        </tr>
        <tr>
            <td><strong>{{ $sucursal->empresa->rfc }}</strong></td>
        </tr>
    </table>

    <br>

    {{-- ================= DATOS EMPRESA ================= --}}
    <table class="no-border">
        <tr>
            <td width="60%">
                {{ $sucursal->empresa->domicilios->first()->calle ?? 'Dirección no disponible' }}
                #{{ $sucursal->empresa->domicilios->first()->numero_exterior ?? 'S/N' }},<br>
                {{ $sucursal->empresa->domicilios->first()->colonia ?? 'Colonia no disponible' }}
                {{ $sucursal->empresa->domicilios->first()->ciudad ?? 'Ciudad no disponible' }},
                {{ $sucursal->empresa->domicilios->first()->estado ?? 'Estado no disponible' }} <br>
                {{ $sucursal->empresa->domicilios->first()->cp ?? 'CP no disponible' }},<br>
                Mexico <br>
            </td>
            <td width="40%">
                Fecha: {{ $documento->fecha }}<br>
                Vigencia: <br>
                Vendedor: {{ $documento->usuario->name ?? 'Nombre no disponible' }}
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
                @foreach ($documento->cliente->domicilios as $dom)
                    <label class="block  text-md font-medium text-gray-700 my-2">
                        <strong>Dirección:</strong>
                        <span>{{ $dom->calle . ' #' . $dom->numero_interior . ', Col. ' . $dom->colonia . ' CP: ' . $dom->cp . ', ' . $dom->ciudad . ', ' . $dom->estado }}</span>
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
    <table>
        <tr>
            <td colspan="6">
                <label class="block  text-md font-medium text-gray-700 my-2">
                    <strong>Observaciones:</strong> <span>{{ $documento->observaciones }} </span>
                </label>
            </td>
        </tr>
    </table>
    {{-- ================= BANCOS ================= --}}
    <table class="no-border small">
        <tr>
            <td>
                <strong>DEPOSITOS Y TRANSFERENCIAS</strong><br>
                Banco: <br>
                Cuenta: <br>
                CLABE:
            </td>
        </tr>
    </table>

    <br>

    {{-- ================= CONTACTO ================= --}}
    <table class="no-border small">
        <tr>
            <td>
                <strong>DATOS DE CONTACTO:</strong><br>
                WhatsApp: <br>
                Correo: <br>
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
