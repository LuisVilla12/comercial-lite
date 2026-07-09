@section('title', 'Panel de control')
<x-app-layout>
    <h3 class="text-xl font-semibold text-gray-900 mt-6 dark:text-white mb-4">
        Empresa: {{ $empresa->nombre }}
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        {{-- ================= CATÁLOGOS ================= --}}
        <div class="col-span-full">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-2">
                📦 Catálogos
            </h2>
        </div>

        <x-dashboard-card href="{{ route('proveedores.index') }}" bg="bg-blue-50 dark:bg-blue-900/20" title="Proveedores"
            desc="Gestión de proveedores" iconBg="bg-blue-500">
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

        @hasanyrole('Administrador|Vendedor')
        <div class="col-span-full mt-6">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-2">
                🔄 Operaciones
            </h2>
        </div>
        @endhasanyrole



        @if ($cajaAbierta and auth()->user()->isVendedor())
            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 shadow md:col-span-2">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-green-600">
                            Caja Abierta
                        </h3>
                        <p class="dark:text-white">
                            Apertura:
                            {{ $cajaAbierta->fecha_apertura->format('d/m/Y H:i') }}
                        </p>

                        <p class="dark:text-white">
                            Fondo inicial:
                            ${{ number_format($cajaAbierta->monto_inicial, 2) }}
                        </p>
                    </div>
                    <a href="{{ route('cajas.edit', $cajaAbierta) }}"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg">
                        Cerrar Caja
                    </a>
                </div>
            </div>
            <x-dashboard-card href="{{ route('gastos.index') }}" bg="bg-green-50 dark:bg-green-900/20"
                title="Movimientos caja" desc="Administrar los movimientos" iconBg="bg-green-500">
                <x-slot:icon>
                    <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
        @elseif(auth()->user()->isVendedor())
            @foreach ($sucursales as $sucursal)
                <x-dashboard-card href="{{ route('cajas.create', $sucursal) }}" bg="bg-teal-50 dark:bg-teal-900/20"
                    title="Abrir caja" desc="" iconBg="bg-teal-500">
                    <x-slot:icon>
                        <x-heroicon-o-building-storefront class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
            @endforeach
        @endif
        @hasanyrole('Administrador|Vendedor')
            <x-dashboard-card href="{{ route('puntos.index') }}" bg="bg-yellow-50 dark:bg-yellow-900/20" title="Monedero"
                desc="Monedero digital" iconBg="bg-yellow-500">
                <x-slot:icon>
                    <x-heroicon-o-gift class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card href="{{ route('reportes.select') }}" bg="bg-orange-50 dark:bg-orange-900/20"
                title="Reportes" desc="Generar reportes" iconBg="bg-orange-500">
                <x-slot:icon>
                    <x-heroicon-o-chart-bar class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
        @endhasanyrole



        @hasanyrole('Administrador|Inventarios')
               {{-- ================= INVENTARIOS ================= --}}
        <div class="col-span-full mt-6">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-2">
                🔄 Inventarios
            </h2>
        </div>

            <x-dashboard-card href="{{ route('almacenes.index') }}" bg="bg-emerald-50 dark:bg-emerald-900/20"
                title="Almacenes" desc="Control de almacenes" iconBg="bg-emerald-500">
                <x-slot:icon>
                    <x-heroicon-o-building-storefront class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
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
            {{-- TIPO 1--ENTRADA --}}
            <x-dashboard-card href="{{ route('ajustes-almacen.index', $tipo = 1) }}" bg="bg-blue-50 dark:bg-blue-900/20"
                title="Entrada a almacén" desc="Entradas" iconBg="bg-blue-500">
                <x-slot:icon>
                    <x-heroicon-o-arrow-up-tray class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
            {{-- TIPO 2 SALIDA --}}
            <x-dashboard-card href="{{ route('ajustes-almacen.index', $tipo = 2) }}" bg="bg-blue-50 dark:bg-blue-900/20"
                title="Salida a almacén" desc="Salidas" iconBg="bg-blue-500">
                <x-slot:icon>
                    <x-heroicon-o-arrow-down-tray class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
            {{-- KARDEX --}}
            <x-dashboard-card href="{{ route('kardex.index') }}" bg="bg-blue-50 dark:bg-blue-900/20" title="Kardex"
                desc="Movimientos de cada producto" iconBg="bg-blue-500">
                <x-slot:icon>
                    <x-heroicon-o-archive-box class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
            {{-- ALERTAS STOCK --}}
            <x-dashboard-card href="{{ route('existencias.validacion') }}" bg="bg-blue-50 dark:bg-blue-900/20"
                title="Pedidos" desc="Validacion de minimos y maximos" iconBg="bg-blue-500">
                <x-slot:icon>
                    <x-heroicon-o-archive-box class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
        @endhasanyrole
        <x-dashboard-card href="{{ route('existencias.index') }}" bg="bg-emerald-50 dark:bg-emerald-900/20"
            title="Existencias" desc="Existencias de productos" iconBg="bg-emerald-500">
            <x-slot:icon>
                <x-heroicon-o-archive-box class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>

        @hasanyrole('Administrador|Vendedor')
            {{-- ================= VENTAS ================= --}}
            <div class="col-span-full mt-6">
                <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-2">
                    💼 Ventas
                </h2>
            </div>
        @endhasanyrole

        @role('Administrador')
            @forelse ($sucursales as $sucursal)
                <x-dashboard-card href="{{ route('sucursales.conceptos', $sucursal) }}" title="{{ $sucursal->nombre }}"
                    desc="" bg="bg-orange-50 dark:bg-orange-900/20" iconBg="bg-blue-500">
                    <x-slot:icon>
                        <x-heroicon-o-building-storefront class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
            @empty
                <p class="dark:text-white">No hay ninguna sucursal registrada</p>
            @endforelse
        @endrole
        @role('Vendedor')
            @foreach ($sucursales as $sucursal)
                <x-dashboard-card href="{{ route('cotizaciones.index', $sucursal) }}"
                    title="Cotizaciones {{ $sucursal->nombre }}" desc="Genera cotizaciones" bg="bg-orange-50 dark:bg-orange-900/20"
                    iconBg="bg-orange-500">
                    <x-slot:icon>
                        <x-heroicon-o-document-currency-dollar class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
            @endforeach
            @foreach ($sucursales as $sucursal)
                <x-dashboard-card href="{{ route('remisiones.index', $sucursal) }}"
                    bg="bg-indigo-50 dark:bg-indigo-900/20" title="Remisiones {{ $sucursal->nombre }}"
                    desc="Generar remisiones" iconBg="bg-indigo-500">
                    <x-slot:icon>
                        <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
            @endforeach
            @foreach ($sucursales as $sucursal)
                <x-dashboard-card href="{{ route('facturas.index', $sucursal) }}" bg="bg-blue-50 dark:bg-blue-900/20"
                    title="Facturas {{ $sucursal->nombre }}" desc="Generar facturas" iconBg="bg-blue-500">
                    <x-slot:icon>
                        <x-heroicon-o-document-text class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
            @endforeach
            @foreach ($sucursales as $sucursal)
                <x-dashboard-card href="{{ route('devoluciones.index', $sucursal) }}" bg="bg-red-50 dark:bg-red-900/20"
                    title="Devoluciones {{ $sucursal->nombre }}" desc="Devoluciones de productos" iconBg="bg-red-500">
                    <x-slot:icon>
                        <x-heroicon-o-arrow-uturn-left class="w-6 h-6" />
                    </x-slot:icon>
                </x-dashboard-card>
            @endforeach
        @endrole
        @hasanyrole('Administrador|Vendedor')
        <x-dashboard-card href="{{ route('pagos.index') }}" bg="bg-red-50 dark:bg-red-900/20"
            title="Recibo electronico de pagos" desc="" iconBg="bg-red-500">
            <x-slot:icon>
                <x-heroicon-o-banknotes class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>
        <x-dashboard-card href="{{ route('facturas.online') }}" bg="bg-green-50 dark:bg-green-900/20"
            title="Factura en linea" desc="" iconBg="bg-green-500">
            <x-slot:icon>
                <x-heroicon-o-globe-alt class="w-6 h-6" />
            </x-slot:icon>
        </x-dashboard-card>
        @endhasanyrole
        @role('Administrador')
            {{-- ================= Administracion ================= --}}
            <div class="col-span-full mt-6">
                <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-2">
                    ⚙️ Administración
                </h2>
            </div>
            <x-dashboard-card href="{{ route('configuracion-empresa.dashboard') }}" bg="bg-teal-50 dark:bg-teal-900/20"
                title="Dashboard General " desc="" iconBg="bg-teal-500">
                <x-slot:icon>
                    <x-heroicon-o-building-storefront class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card href="{{ route('cajas.index') }}" bg="bg-teal-50 dark:bg-teal-900/20" title="Cajas"
                desc="Listado de cajas" iconBg="bg-teal-500">
                <x-slot:icon>
                    <x-heroicon-o-currency-dollar class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card href="{{ route('sucursales.index') }}" bg="bg-teal-50 dark:bg-teal-900/20"
                title="Sucursales" desc="Administrar sucursales" iconBg="bg-teal-500">
                <x-slot:icon>
                    <x-heroicon-o-building-storefront class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card href="{{ route('auditoria.index') }}" bg="bg-teal-50 dark:bg-teal-900/20" title="Bitacora"
                desc="Bitacora del sistema" iconBg="bg-teal-500">
                <x-slot:icon>
                    <x-heroicon-o-document-text class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card href="{{ route('bancos.index') }}" bg="bg-teal-50 dark:bg-teal-900/20"
                title="Datos Bancarios" desc="Administrar datos bancarios" iconBg="bg-teal-500">
                <x-slot:icon>
                    <x-heroicon-o-banknotes class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
            {{-- <x-dashboard-card href="{{ route('metodos.index') }}" bg="bg-teal-50 dark:bg-teal-900/20"
                title="Metodos de pago" desc="Administrar tus metodos de pago" iconBg="bg-teal-500">
                <x-slot:icon>
                    <x-heroicon-o-banknotes class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card> --}}
            <x-dashboard-card href="{{ route('agentes.index') }}" bg="bg-teal-50 dark:bg-teal-900/20" title="Agentes"
                desc="Administrar agentes" iconBg="bg-teal-500">
                <x-slot:icon>
                    <x-heroicon-o-user class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card href="{{ route('configuracion-empresa.show') }}" bg="bg-teal-50 dark:bg-teal-900/20"
                title="Empresa" desc="Empresa" iconBg="bg-teal-500">
                <x-slot:icon>
                    <x-heroicon-o-building-storefront class="w-6 h-6" />
                </x-slot:icon>
            </x-dashboard-card>
        @endrole
    </div>
</x-app-layout>
