@section('title', 'Panel de control')
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Proveedores -->
    <a href="{{ route('proveedores.index') }}"
       class="flex items-center gap-4 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl shadow hover:shadow-md transition">
        <div class="p-3 bg-blue-500 text-white rounded-lg">
            <x-heroicon-o-truck class="w-9 h-9" />
        </div>
        <div>
            <h3 class="text-lg font-semibold">Proveedores</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Gestión de proveedores
            </p>
        </div>
    </a>

    <!-- Almacenes -->
    <a href="{{ route('almacenes.index') }}"
       class="flex items-center gap-4 p-6 bg-green-50 dark:bg-green-900/20 rounded-xl shadow hover:shadow-md transition">
        <div class="p-3 bg-green-500 text-white rounded-lg">
            <x-heroicon-o-home class="w-9 h-9" />
        </div>
        <div>
            <h3 class="text-lg font-semibold">Almacenes</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Control de almacenes
            </p>
        </div>
    </a>
<a href="{{ route(name: 'compras.index') }}"
       class="flex items-center gap-4 p-6 bg-green-50 dark:bg-green-900/20 rounded-xl shadow hover:shadow-md transition">
        <div class="p-3 bg-green-500 text-white rounded-lg">
            {{-- <x-heroicon-o-home class="w-9 h-9" /> --}}
            <x-heroicon-o-shopping-cart class="w-9 h-9" />

        </div>
        <div>
            <h3 class="text-lg font-semibold">Compras</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Control de compras
            </p>
        </div>
    </a>
    <!-- Productos -->
    <a href="{{ route('productos.index') }}"
       class="flex items-center gap-4 p-6 bg-purple-50 dark:bg-purple-900/20 rounded-xl shadow hover:shadow-md transition">
        <div class="p-3 bg-purple-500 text-white rounded-lg">
           <x-heroicon-o-archive-box class="w-9 h-9" />
        </div>
        <div>
            <h3 class="text-lg font-semibold">Productos</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Catálogo de productos
            </p>
        </div>
    </a>

    <!-- Clientes -->
    <a href="{{ route('clientes.index') }}"
       class="flex items-center gap-4 p-6 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl shadow hover:shadow-md transition">
        <div class="p-3 bg-yellow-500 text-white rounded-lg">
            <x-heroicon-o-user-group class="w-9 h-9" />
        </div>
        <div>
            <h3 class="text-lg font-semibold">Clientes</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Gestión de clientes
            </p>
        </div>
    </a>

    <!-- Clasificaciones -->
    <a href="{{ route('clasificaciones.index') }}"
       class="flex items-center gap-4 p-6 bg-pink-50 dark:bg-pink-900/20 rounded-xl shadow hover:shadow-md transition">
        <div class="p-3 bg-pink-500 text-white rounded-lg">
            <x-heroicon-o-tag class="w-9 h-9" />
        </div>
        <div>
            <h3 class="text-lg font-semibold">Clasificaciones</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Organización de productos
            </p>
        </div>
    </a>

    <!-- Usuarios -->
    <a href="{{ route('usuarios.index') }}"
       class="flex items-center gap-4 p-6 bg-red-50 dark:bg-red-900/20 rounded-xl shadow hover:shadow-md transition">
        <div class="p-3 bg-red-500 text-white rounded-lg">
            <x-heroicon-o-users class="w-9 h-9" />
        </div>
        <div>
            <h3 class="text-lg font-semibold">Usuarios</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Control de accesos
            </p>
        </div>
    </a>

    <!-- Cotización -->
    <a href="{{ route('cotizacion.create') }}"
       class="flex items-center gap-4 p-6 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl shadow hover:shadow-md transition">
        <div class="p-3 bg-indigo-500 text-white rounded-lg">
            <x-heroicon-o-document-text class="w-9 h-9" />
        </div>
        <div>
            <h3 class="text-lg font-semibold">Cotización</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Generar cotizaciones
            </p>
        </div>
    </a>

    <!-- Ventas -->
    <a href=""
       class="flex items-center gap-4 p-6 bg-teal-50 dark:bg-teal-900/20 rounded-xl shadow hover:shadow-md transition">
        <div class="p-3 bg-teal-500 text-white rounded-lg">
            <x-heroicon-o-currency-dollar class="w-9 h-9" />
        </div>
        <div>
            <h3 class="text-lg font-semibold">Contratos</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Contratos de rentas y ventas
            </p>
        </div>
    </a>

    <!-- Reportes -->
    <a href=""
       class="flex items-center gap-4 p-6 bg-gray-100 dark:bg-gray-700 rounded-xl shadow hover:shadow-md transition">
        <div class="p-3 bg-gray-600 text-white rounded-lg">
            <x-heroicon-o-chart-bar class="w-9 h-9" />
        </div>
        <div>
            <h3 class="text-lg font-semibold">Reportes</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Estadísticas y exportes
            </p>
        </div>
    </a>

</div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
