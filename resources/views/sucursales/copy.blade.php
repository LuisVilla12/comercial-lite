@section('title', content: 'Sucursal ' . $sucursal->nombre . ' - Dashboard')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Dashboard de Sucursal {{ $sucursal->nombre }}
        </h2>
    </x-slot>
@if(auth()->user()->isAdmin())
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-6">

    <div class="bg-green-50 rounded-xl p-4 shadow">
        <p class="text-sm text-gray-500">Ventas </p>
        <p class="text-2xl font-bold text-green-600">
            ${{ number_format($ventasTotal, 2) }}
        </p>
    </div>

    <div class="bg-purple-50 rounded-xl p-4 shadow">
        <p class="text-sm text-gray-500">Ticket Promedio</p>
        <p class="text-2xl font-bold text-purple-600">
            ${{ number_format($ticketPromedio, 2) }}
        </p>
    </div>
    <div class="bg-orange-50 rounded-xl p-4 shadow">
        <p class="text-sm text-gray-500">Total de documentos:</p>
        <p class="text-2xl font-bold text-orange-600">
           {{ $totalDocumentos }}
        </p>
    </div>

</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="grid md:grid-cols-2">
        <div >
            <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    Ventas
                </h2>
                <p class="text-sm text-gray-500">
                    Ventas registradas por hora
                </p>
            </div>

            <form method="GET">
                <select name="periodo"
                    onchange="this.form.submit()"
                    class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="dia" {{ $periodo == 'dia' ? 'selected' : '' }}>
                        Hoy
                    </option>
                    <option value="semana" {{ $periodo == 'semana' ? 'selected' : '' }}>
                        Semana
                    </option>
                    <option value="mes" {{ $periodo == 'mes' ? 'selected' : '' }}>
                        Mes
                    </option>
                </select>
            </form>
            </div>

            <div class="h-96">
            <canvas id="ventasChart"></canvas>
            </div>
        </div>
<div>
         <h2 class="text-center text-lg font-semibold my-6 mt-4 dark:text-white">
            Principales conceptos
        </h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 max-w-6xl mx-auto px-6">
    <x-dashboard-card href="{{ route('cotizaciones.index', $sucursal) }}"
                    title="Cotizaciones {{ $sucursal->nombre }}" desc="Generar cotizaciones"
                    bg="bg-orange-50 dark:bg-orange-900/20" iconBg="bg-orange-500">
                    <x-slot:icon>
                        <x-heroicon-o-document-currency-dollar class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
                <x-dashboard-card href="{{ route('remisiones.index', $sucursal) }}"
                    bg="bg-indigo-50 dark:bg-indigo-900/20" title="Remisiones {{ $sucursal->nombre }}"
                    desc="Generar remisiones" iconBg="bg-indigo-500">
                    <x-slot:icon>
                        <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
                  <x-dashboard-card href="{{ route('facturas.index', $sucursal) }}" bg="bg-blue-50 dark:bg-blue-900/20"
                    title="Facturas {{ $sucursal->nombre }}" desc="Generar facturas" iconBg="bg-blue-500">
                    <x-slot:icon>
                        <x-heroicon-o-document-text class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
                <x-dashboard-card href="{{ route('devoluciones.index', $sucursal) }}" bg="bg-red-50 dark:bg-red-900/20"
                    title="Devoluciones {{ $sucursal->nombre }}" desc="Devoluciones de productos"
                    iconBg="bg-red-500">
                    <x-slot:icon>
                        <x-heroicon-o-arrow-uturn-left class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
    </div>

</div>


    </div>
</div>

@else
<div>
         <h2 class="text-center text-lg font-semibold my-6 mt-4 dark:text-white">
            Principales conceptos
        </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-6xl mx-auto px-6">
    <x-dashboard-card href="{{ route('cotizaciones.index', $sucursal) }}"
                    title="Cotizaciones {{ $sucursal->nombre }}" desc="Generar cotizaciones"
                    bg="bg-orange-50 dark:bg-orange-900/20" iconBg="bg-orange-500">
                    <x-slot:icon>
                        <x-heroicon-o-document-currency-dollar class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
                <x-dashboard-card href="{{ route('remisiones.index', $sucursal) }}"
                    bg="bg-indigo-50 dark:bg-indigo-900/20" title="Remisiones {{ $sucursal->nombre }}"
                    desc="Generar remisiones" iconBg="bg-indigo-500">
                    <x-slot:icon>
                        <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
                  <x-dashboard-card href="{{ route('facturas.index', $sucursal) }}" bg="bg-blue-50 dark:bg-blue-900/20"
                    title="Facturas {{ $sucursal->nombre }}" desc="Generar facturas" iconBg="bg-blue-500">
                    <x-slot:icon>
                        <x-heroicon-o-document-text class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
                <x-dashboard-card href="{{ route('devoluciones.index', $sucursal) }}" bg="bg-red-50 dark:bg-red-900/20"
                    title="Devoluciones {{ $sucursal->nombre }}" desc="Devoluciones de productos"
                    iconBg="bg-red-500">
                    <x-slot:icon>
                        <x-heroicon-o-arrow-uturn-left class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
    </div>

</div>
@endif
</x-app-layout>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const labels = @json($ventas->pluck('etiqueta'));
    const data = @json($ventas->pluck('total'));

    const ctx = document.getElementById('ventasChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 400);

    gradient.addColorStop(0, 'rgba(249,115,22,0.40)');
    gradient.addColorStop(1, 'rgba(249,115,22,0.02)');

    new Chart(ctx, {
        type: 'line',

        data: {
            labels: labels,

            datasets: [{
                label: 'Ventas',

                data: data,

                borderColor: '#f97316',
                backgroundColor: gradient,

                borderWidth: 3,

                fill: true,

                tension: 0.4,

                pointRadius: 5,
                pointHoverRadius: 8,

                pointBackgroundColor: '#f97316',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },

        options: {

            responsive: true,
            maintainAspectRatio: false,

            interaction: {
                intersect: false,
                mode: 'index'
            },

            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            },

            plugins: {

                legend: {
                    display: false
                },

                tooltip: {
                    backgroundColor: '#111827',
                    padding: 12,

                    callbacks: {
                        label: function(context) {
                            return ' $' + Number(context.raw).toLocaleString('es-MX', {
                                minimumFractionDigits: 2
                            });
                        }
                    }
                }

            },

            scales: {

                x: {
                    grid: {
                        display: false
                    }
                },

                y: {

                    beginAtZero: true,

                    grid: {
                        color: 'rgba(156,163,175,0.15)'
                    },

                    ticks: {
                        callback: function(value) {
                            return '$' + Number(value).toLocaleString('es-MX');
                        }
                    }

                }

            }

        }
    });

});
</script>
