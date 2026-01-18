@section('title', 'Panel de control')
<x-app-layout>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        {{-- ================= CATÁLOGOS ================= --}}
        <div class="col-span-full">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-2">
                📦 Catálogos
            </h2>
        </div>

        <x-dashboard-card href="{{ route('proveedores.index') }}" bg="bg-blue-50 dark:bg-blue-900/20"
            title="Proveedores" desc="Gestión de proveedores" iconBg="bg-blue-500">
            <x-slot:icon>
                <x-heroicon-o-truck class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>

        <x-dashboard-card href="{{ route('clientes.index') }}" bg="bg-yellow-50 dark:bg-yellow-900/20" title="Clientes"
            desc="Gestión de clientes" iconBg="bg-yellow-500">
            <x-slot:icon>
                <x-heroicon-o-user-group class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>

        <x-dashboard-card href="{{ route('productos.index') }}" bg="bg-purple-50 dark:bg-purple-900/20"
            title="Productos" desc="Catálogo de productos" iconBg="bg-purple-500">
            <x-slot:icon>
                <x-heroicon-o-archive-box class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>

        <x-dashboard-card href="{{ route('clasificaciones.index') }}" bg="bg-pink-50 dark:bg-pink-900/20"
            title="Clasificaciones" desc="Organización de productos" iconBg="bg-pink-500">
            <x-slot:icon>
                <x-heroicon-o-tag class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>

        {{-- ================= OPERACIONES ================= --}}
        <div class="col-span-full mt-6">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-2">
                🔄 Operaciones
            </h2>
        </div>

        <x-dashboard-card href="{{ route('compras.index') }}" bg="bg-emerald-50 dark:bg-emerald-900/20" title="Compras"
            desc="Control de compras" iconBg="bg-emerald-500">
            <x-slot:icon>
                <x-heroicon-o-shopping-cart class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>

        <x-dashboard-card href="{{ route('traspasos.index') }}" bg="bg-emerald-50 dark:bg-emerald-900/20"
            title="Traspasos" desc="Movimientos entre almacenes" iconBg="bg-emerald-500">
            <x-slot:icon>
                <x-heroicon-o-arrows-right-left class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>

        {{-- ================= VENTAS ================= --}}
        <div class="col-span-full mt-6">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-2">
                💼 Ventas
            </h2>
        </div>

        <x-dashboard-card href="{{ route('cotizaciones.index') }}" bg="bg-indigo-50 dark:bg-indigo-900/20"
            title="Cotizaciones" desc="Generar cotizaciones" iconBg="bg-indigo-500">
            <x-slot:icon>
                <x-heroicon-o-document-currency-dollar class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>

        <x-dashboard-card href="{{ route('remisiones.index') }}" bg="bg-teal-50 dark:bg-teal-900/20" title="Remisiones"
            desc="Generar remisiones" iconBg="bg-teal-500">
            <x-slot:icon>
                <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>

        <x-dashboard-card href="{{ route('facturas.index') }}" bg="bg-teal-50 dark:bg-teal-900/20" title="Facturas"
            desc="Generar facturas" iconBg="bg-teal-500">
            <x-slot:icon>
                <x-heroicon-o-document-text class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>
        <x-dashboard-card href="" bg="bg-teal-50 dark:bg-teal-900/20" title="Reportes" desc="Generar reportes"
            iconBg="bg-teal-500">
            <x-slot:icon>
                <x-heroicon-o-chart-bar class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>
        @if(auth()->user()->tipo==2)
        {{-- ================= Administracion ================= --}}
        <div class="col-span-full mt-6">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-2">
                ⚙️ Administración
            </h2>
        </div>

        <x-dashboard-card href="{{ route('usuarios.index') }}" bg="bg-teal-50 dark:bg-teal-900/20" title="Usuarios"
            desc="Administrar usuarios" iconBg="bg-teal-500">
            <x-slot:icon>
                <x-heroicon-o-users class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>
        @endif
    </div>

</x-app-layout>
