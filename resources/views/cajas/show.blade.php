@section('title', 'Detalles de una caja ')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Detalles de una caja
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-6xl mx-auto">

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl">

                <div class="p-6 border-b">
                    <h2 class="text-2xl font-bold dark:text-white">
                        Información de una caja
                    </h2>
                </div>
                <div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4 px-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">
                                Sucursal
                            </label>

                            <input type="text" value="{{ $caja->sucursal->nombre }}"
                                class="w-full rounded-lg bg-gray-100" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">
                                Usuario
                            </label>

                            <input type="text" value="{{ auth()->user()->name }}"
                                class="w-full rounded-lg bg-gray-100" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">
                                Fecha y hora
                            </label>

                            <input type="text" value="{{ now()->format('d/m/Y H:i') }}"
                                class="w-full rounded-lg bg-gray-100" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">
                                Monto inicial
                            </label>

                            <input type="number" step="0.01" min="" value="{{ $caja->monto_inicial }}"
                                name="monto_inicial" class="w-full rounded-lg" placeholder="0.00" required>
                        </div>
                    </div>
                    @if ($ventas->count() > 0)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-4 dark:text-white ml-4">
                                Resumen de Ventas por Forma de Pago
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 px-6 py-4">

                                @foreach ($ventas as $venta)
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 shadow-sm border px-4 border-gray-200 dark:border-gray-600">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-300">
                                                    Forma de pago
                                                </p>
                                                <h4 class="font-bold text-lg dark:text-white">
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
                                                            Tarjeta de credito
                                                        @break

                                                        @case('28')
                                                            Tarjeta de debito
                                                        @break

                                                        @default
                                                    @endswitch

                                                </h4>
                                            </div>

                                            <div class="text-right">
                                                <p class="text-sm text-gray-500 dark:text-gray-300">
                                                    Total
                                                </p>

                                                <h4 class="font-bold text-green-600 text-xl">
                                                    ${{ number_format($venta->total, 2) }}
                                                </h4>
                                            </div>

                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>
                    @endif
                    @if ($gastos->count() > 0)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-4 dark:text-white ml-4">
                                Resumen de movimientos de caja
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 px-6 py-4">

                                @foreach ($gastos as $gasto)
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 shadow-sm border px-4 border-gray-200 dark:border-gray-600">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-300">
                                                    {{ $gasto->descripcion }}
                                                </p>
                                                <h4 class="font-bold text-lg dark:text-white">
                                                    @switch($gasto->tipo)
                                                        @case('1')
                                                            Gasto
                                                        @break

                                                        @case('2')
                                                            Retiro
                                                        @break

                                                        @default
                                                    @endswitch

                                                </h4>
                                            </div>

                                            <div class="text-right">
                                                <p class="text-sm text-gray-500 dark:text-gray-300">
                                                    Total
                                                </p>

                                                <h4 class="font-bold text-red-600 text-xl">
                                                    ${{ number_format($gasto->total, 2) }}
                                                </h4>
                                            </div>

                                        </div>

                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endif
                    <div class="flex justify-end px-6 mt-4">
                        <div>
                            <h3 class="text-lg font-semibold mb-4 dark:text-white ml-4">
                                TOTAL VENDIDO: <span class="font-bold text-green-600 text-xl">+
                                    ${{ number_format($totalVentas, 2) }}</span>
                            </h3>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4 dark:text-white ml-4">
                                TOTAL MOVIMIENTOS: <span class="font-bold text-red-600 text-xl">-
                                    ${{ number_format($totalGastos, 2) }}</span>
                            </h3>
                        </div>
                    </div>
                    <div class="flex justify-between gap-3 p-6 ">
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 rounded-md border-red-100 font-medium flex text-white bg-red-600 hover:bg-red-600">
                            <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" /> Regresar
                        </a>
                        <a href="{{ route('cajas.pdf', $caja) }}" target="_blank"
                            class="px-4 py-2 bg-blue-600 text-white rounded flex items-center ml-6">
                            <x-heroicon-o-printer class="w-5 h-5 mr-2" /> Imprimir
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
