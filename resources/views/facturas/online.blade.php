<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Facturar en linea</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <header class="bg-white flex justify-between items-center p-4 shadow-md">
        <div class="mx-auto container w-10/12">
            <p class="flex items-center gap-1 font-semibold text-lg"><x-heroicon-o-document-magnifying-glass
                    class="w-5 h-5 mr-2 text-blue-500" /> Facturación en linea</p>
        </div>
        <div>
            <p class="flex items-center gap-1"><x-heroicon-o-question-mark-circle class="w-5 h-5 mr-2 text-blue-500" />
                ¿Necesitas ayuda?</p>
        </div>
    </header>
    <main class="mt-4  mx-auto container p-4 w-10/12">
        <h1 class="text-3xl font-bold text-center text-gray-800 ">Factura tu compra!</h1>
        <div class="grid md:grid-cols-2 gap-2 lg:gap-4">
            <div class="mt-2 mx-auto p-4 lg:p-6 ">
                <form class="p-6 bg-white shadow-md rounded-lg" method="GET" action="{{ route('facturas.online') }}">
                    @csrf
                    <p class="flex items-center gap-1"><x-heroicon-o-ticket class="w-5 h-5 mr-2 text-blue-500" />Datos
                        de la compra</p>
                    <div class="grid md:grid-cols-3 gap-2 mt-2">
                        <div>
                            <label for="serie" class="block text-sm font-medium text-gray-700">Serie de la
                                compra:</label>
                            <input type="text" name="serie" id="serie"  value="{{ request('serie') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm"
                                step="0.01" min="0" required>
                        </div>
                        <div>
                            <label for="folio" class="block text-sm font-medium text-gray-700">Folio de
                                compra:</label>
                            <input type="number" name="folio" id="folio"  value="{{ request('folio') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="codigo_unico" class="block text-sm font-medium text-gray-700">Codigo unico:</label>
                            <input type="text" name="codigo_unico" id="codigo_unico"  value="{{ request('codigo_unico') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                        </div>
                    </div>
                    <div class="mt-2 flex justify-end">
                        <div>
                            <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer"><x-heroicon-o-magnifying-glass
                                    class="w-5 h-5 mr-2 text-white-500" /> Buscar</button>
                        </div>
                    </div>
                </form>
                <form class="px-6 mt-2 py-2 bg-white shadow-md rounded-lg" method="POST"
                    action="{{ route('facturas.online.store') }}">
                    @csrf
                    <p class="flex items-center gap-1 mt-4"><x-heroicon-o-user-circle
                            class="w-5 h-5 mr-2 text-blue-500" />Datos del cliente</p>
                    <div class="grid md:grid-cols-2 gap-2 mt-4">
                        <div>
                            <label for="razon_social" class="block text-sm font-medium text-gray-700">Razón
                                social:</label>
                            <input type="text" name="razon_social" id="razon_social" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                        </div>
                        <div>
                            <label for="rfc" class="block text-sm font-medium text-gray-700">RFC:</label>
                            <input type="text" name="rfc" id="rfc" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                        </div>
                        <div>
                            <label for="regimen_fiscal" class="block text-sm font-medium text-gray-700">Régimen
                                fiscal:</label>
                            <select name="regimen_fiscal" id="regimen_fiscal" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                                <option value="601">601 - General de Ley Personas Morales</option>
                                <option value="603">603 - Personas Morales con Fines no Lucrativos</option>
                                <option value="605">605 - Sueldos y Salarios e Ingresos Asimilados a Salarios
                                </option>
                                <option value="606">606 - Arrendamiento</option>
                                <option value="607">607 - Régimen de Enajenación o Adquisición de Bienes</option>
                                <option value="608">608 - Demás ingresos</option>
                                <option value="609">609 - Consolidación</option>
                                <option value="610">610 - Residentes en el Extranjero sin Establecimiento Permanente
                                    en México</option>
                                <option value="611">611 - Ingresos por Dividendos (socios y accionistas)</option>
                                <option value="612">612 - Personas Físicas con Actividades Empresariales y
                                    Profesionales</option>
                                <option value="614">614 - Ingresos por intereses</option>
                                <option value="615">615 - Régimen de los ingresos por obtención de premios</option>
                                <option value="616">616 - Sin obligaciones fiscales</option>
                                <option value="620">620 - Sociedades Cooperativas de Producción que optan por diferir
                                    sus ingresos</option>
                                <option value="621">621 - Incorporación Fiscal</option>
                                <option value="622">622 - Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras
                                </option>
                                <option value="623">623 - Opcional para Grupos de Sociedades</option>
                                <option value="624">624 - Coordinados</option>
                                <option value="625">625 - Régimen de las Actividades Empresariales con Ingresos a
                                    través de Plataformas Tecnológicas</option>
                                <option value="626">626 - Régimen Simplificado de Confianza</option>
                                <option value="628">628 - Hidrocarburos</option>
                                <option value="629">629 - De los Regímenes Fiscales Preferentes y de las Empresas
                                    Multinacionales</option>
                                <option value="630">630 - Enajenación de acciones en bolsa de valores</option>
                            </select>
                        </div>
                        <div>
                            <label for="usos_cfdi" class="block text-sm font-medium text-gray-700">Uso de CFDI:</label>
                            <select name="usos_cfdi" id="usos_cfdi" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                                <option value="">Seleccionar...</option>
                                <option value="G01">G01 - Gastos en general</option>
                                <option value="G02">G02 - Adquisición de mercancias</option>
                                <option value="G03">G03 - Gastos en actividades empresariales</option>
                                <option value="I01">I01 - Ingresos por servicios prestados</option>
                                <option value="I02">I02 - Ingresos por ventas de mercancías</option>
                            </select>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo
                                electrónico:</label>
                            <input type="email" name="email" id="email" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                        </div>
                        <div>
                            <label for="cp" class="block text-sm font-medium text-gray-700">Código
                                postal:</label>
                            <input type="text" name="cp" id="cp" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                        </div>
                        @if($documento!=null)
                        <input hidden name="documento_id" value="{{ $documento->id }}">
                        @endif
                    </div>
                    <div class="mt-6 mb-2">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Generar
                            factura</button>
                    </div>
                </form>
            </div>
            <div class="mt-2 mx-auto p-4 lg:p-6 w-full">
                <div class="py-6 pl-6 pr-4 bg-white shadow-md rounded-lg">
                    <p class="flex items-center gap-1"><x-heroicon-o-ticket class="w-5 h-5 mr-2 text-blue-500" />Detalles del documento:</p>
                    @if($documento!=null)
                    <div class="grid md:grid-cols-2 gap-2 mt-2">
                        <div>
                            <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha:</label>
                            <input type="date"  id="fecha" value="{{ $documento->fecha }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                        </div>
                        <div>
                            <label for="fecha" class="block text-sm font-medium text-gray-700">Serie y folio:</label>
                            <input type="text"  id="serie_folio" value="{{ $documento->serie }}-{{ $documento->folio }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="text" class="block text-sm font-medium text-gray-700">Cliente:</label>
                            <input type="text" name="cliente" id="cliente" value="{{ $documento->cliente->nombre }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm"
                                step="0.01" min="0">
                        </div>
                        <p class="flex items-center gap-1 md:col-span-2"><x-heroicon-o-archive-box-x-mark class="w-5 h-5 mr-2 text-blue-500" />Productos:</p>
                        <table class="w-full md:col-span-2 border bg-white shadow rounded">
                            <thead>
                                <tr>
                                    <th class="p-0.5 text-small text-gray-700">Producto</th>
                                    <th class="p-0.5 text-small text-gray-700">Cant.</th>
                                    <th class="p-0.5 text-small text-gray-700">Precio</th>
                                    <th class="p-0.5 text-small text-gray-700">Desc.</th>
                                    <th class="p-0.5 text-small text-gray-700">Total</th>
                                </tr>
                            </thead>
                            <tbody class="w-full">
                                @foreach ($documento->detalles as $detalle)
                                    <tr class="border-t"></tr>
                                        <td class="p-0.5 text-small text-gray-700">{{ $detalle->producto->nombre_producto }}</td>
                                        <td class="p-0.5 text-small text-gray-700 text-center">{{ $detalle->cantidad }}</td>
                                        <td class="p-0.5 text-small text-gray-700">${{ number_format($detalle->costo_unitario, 2) }}</td>
                                        <td class="p-0.5 text-small text-gray-700 text-center">{{ number_format($detalle->descuento, 0) }}%</td>
                                        <td class="p-0.5 text-small text-gray-700">${{ number_format($detalle->importe, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 mt-2 gap-4">
                        <div class="mt-4">
                            <label for="subtotal" class="block text-sm font-medium text-gray-700">Subtotal:</label>
                            <input type="number"  id="subtotal" value="{{ $documento->subtotal }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                        </div>
                        <div class="mt-4">
                            <label for="impuestos" class="block text-sm font-medium text-gray-700">Impuestos:</label>
                            <input type="number"  id="impuestos" value="{{ $documento->impuestos }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                        </div>
                        <div class="mt-4">
                            <label for="descuentos" class="block text-sm font-medium text-gray-700">Descuentos:</label>
                            <input type="number"  id="descuentos" value="{{ $documento->descuentos }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                        </div>
                    </div>
                    <div class="mt-2 flex justify-end">
                        <label for="total" class=" text-xl font-bold uppercase text-gray-700">Total: ${{ number_format($documento->total, 2) }}</label>
                    </div>


                    @else
                    <p class="text-red-500 mt-2">No se encontró ningún documento con la serie y folio proporcionados.</p>
                    @endif
                </div>
            </div>
        </div>
    </main>

</body>

</html>
