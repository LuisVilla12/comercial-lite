
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Dashboard de la empresa {{ $empresa->nombre }}
        </h2>
    </x-slot>
@if(auth()->user()->isAdmin())
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 mt-4 ">Estadisticas de las ventas</h2>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 my-6">

    <div class="bg-green-50 rounded-xl p-4 shadow">
        <p class="text-sm text-gray-500">Ventas</p>
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
    <div class="grid">
        <div >
            <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    Ventas
                </h2>
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
    </div>
</div>
<div class=" mt-4">
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 ">Productos mas vendidos</h2>
<div class="grid md:grid-cols-2">
    <div>
<div style="width: 80%; margin: auto;">
    <canvas id="graficaProductos"></canvas>
</div>

    </div>
    <div>

    </div>
</div>
</div>
@endif
</x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

// GRAFICA  PRODUCTOS
  // Obtener el contexto del canvas
    const ctx = document.getElementById('graficaProductos').getContext('2d');

    // Inicializar Chart.js
    const miGrafica = new Chart(ctx, {
        type: 'bar', // Puedes cambiarlo a 'line', 'pie' o 'doughnut'
        data: {
            // Pasamos los datos de Laravel convertidos a JSON
            labels: {!! json_encode($labelsProductos) !!}, 
            datasets: [{
                label: 'Total Cantidad Vendida',
                data: {!! json_encode($dataProductos) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Color de las barras
                borderColor: 'rgba(54, 162, 235, 1)',      // Color del borde
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true // Forzar a que el eje Y empiece en 0
                }
            }
        }
    });
</script>
