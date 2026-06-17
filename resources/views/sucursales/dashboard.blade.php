@section('title', content: 'Sucursales')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Dashboard de Sucursal {{ $sucursal->nombre }}
        </h2>
    </x-slot>

    <div class="  px-4 py-4 mt-4 overflow-x-auto rounded-lg">
    @if(auth()->user()->isAdmin())

        <div class="grid md:grid-cols-2 bg-white dark:bg-gray-800 rounded-lg shadow mt-4 p-6">
        <div>
    <div class="flex justify-between items-center  mb-6">
        <h2 class="text-lg font-semibold text-center">
            Total de ventas
        </h2>
        <form method="GET">

            <select name="periodo"
                    onchange="this.form.submit()"
                    class="rounded border-gray-300">

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

    <canvas class="max-w-3xl " id="ventasChart"></canvas>
        </div>
    <div>
         <h2 class="text-center text-lg font-semibold my-6  md:mt-2">
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
    @endif
    </div>

</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const labels = @json($ventas->pluck('etiqueta'));
const data = @json($ventas->pluck('total'));

new Chart(
    document.getElementById('ventasChart'),
    {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ventas ($)',
                data: data,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    }
);

</script>
