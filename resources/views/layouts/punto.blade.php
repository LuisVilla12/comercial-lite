<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased ">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 pb-6">

        <div x-data="{ sidebar: true, inventario: false,catalogos:false,operaciones:false, administracion:false}" class="flex h-screen">
            <!-- Sidebar -->
            <aside :class="sidebar ? 'w-60' : 'w-20'"
                class="bg-slate-900 text-white transition-all duration-300 flex flex-col overflow-hidden">
                <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <span x-show="sidebar" x-transition class="font-bold text-lg">
                            CARDENAS
                        </span>
                    </div>
                    <button @click="sidebar=!sidebar" class="p-2 rounded-lg hover:bg-slate-800">
                        <x-heroicon-o-bars-3 class="w-5 h-5 mr-2" />
                    </button>

                </div>
                <!-- Menú -->
                <nav class="flex-1 p-3 space-y-2 overflow-y-auto">
                    <!-- Dashboard -->
                    <div class="flex items-center gap-3 rounded-lg px-3 py-3 hover:bg-slate-800">
                        <span x-show="sidebar" x-transition>
                            Sucursal:
                        </span>
                        <select class="w-full bg-slate-900 text-white ">
                            {{-- @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach --}}
                        </select>
                    </div>

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-3 hover:bg-slate-800">
                            <x-heroicon-o-home class="w-5 h-5" />
                        <span x-show="sidebar" x-transition>
                            Dashboard
                        </span>
                    </a>
                    <!-- Ventas -->
                    <div>
                        <a  class="flex items-center gap-3 rounded-lg px-3 py-3 hover:bg-slate-800">
                            <x-heroicon-o-document-text class="w-5 h-5" />
                            <span x-show="sidebar">
                                Cotizaciones
                            </span>
                        </a>
                        <a  class="flex items-center gap-3 rounded-lg px-3 py-3 hover:bg-slate-800">
                            <x-heroicon-o-document-currency-dollar class="w-5 h-5" />
                            <span x-show="sidebar">
                                Remisiones
                            </span>
                        </a>

                        <a  class="flex items-center gap-3 rounded-lg px-3 py-3 hover:bg-slate-800">
                            <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                            <span x-show="sidebar">
                                Facturas
                            </span>
                        </a>
                        <a  class="flex items-center gap-3 rounded-lg px-3 py-3 hover:bg-slate-800">
                            <x-heroicon-o-arrow-uturn-left class="w-5 h-5" />
                            <span x-show="sidebar">
                                Devoluciones
                            </span>
                        </a>
                        <a href="" class="flex items-center gap-3 rounded-lg px-3 py-3 hover:bg-slate-800">
                            <x-heroicon-o-globe-alt class="w-5 h-5" />
                            <span x-show="sidebar">
                                Factura global
                            </span>
                        </a>
                    </div>

                    <!-- Inventario -->
                    <div>
                        <button @click="inventario=!inventario"
                            class="w-full flex items-center justify-between rounded-lg px-3 py-3 hover:bg-slate-800">
                            <div class="flex items-center gap-3">
                                <x-heroicon-o-archive-box class="w-5 h-5" />
                                <span x-show="sidebar">
                                    Inventario
                                </span>
                            </div>

                            <i x-show="sidebar" class="fa-solid fa-chevron-down text-xs transition-transform"
                                :class="inventario ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="inventario && sidebar" x-transition class="ml-8 mt-1 space-y-1">

                            <a href="{{ route('existencias.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Existencias
                            </a>

                            <a href="{{ route('ajustes-almacen.index', $tipo = 1) }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Entradas
                            </a>

                            <a href="{{ route('ajustes-almacen.index', $tipo = 2) }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Salidas
                            </a>
                            <a href="{{ route('compras.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Compras
                            </a>

                            <a href="{{ route('traspasos.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Traspasos
                            </a>
                            <a href="{{ route('kardex.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Kardex
                            </a>
                             <a href="{{ route('almacenes.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Almacenes
                            </a>

                        </div>
                    </div>
                     <!-- Catalogos -->
                    <div>
                        <button @click="catalogos=!catalogos"
                            class="w-full flex items-center justify-between rounded-lg px-3 py-3 hover:bg-slate-800">

                            <div class="flex items-center gap-3">
                                <x-heroicon-o-book-open class="w-5 h-5" />
                                <span x-show="sidebar">
                                    Catalogos
                                </span>

                            </div>

                            <i x-show="sidebar" class="fa-solid fa-chevron-down text-xs transition-transform"
                                :class="catalogos ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="catalogos && sidebar" x-transition class="ml-8 mt-1 space-y-1">

                            <a href="{{route('productos.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Productos
                            </a>

                            <a href="{{route('clientes.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Clientes
                            </a>

                            <a href="{{  route('proveedores.index')  }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Proveedores
                            </a>

                            <a href="{{ route('clasificaciones.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Categorias
                            </a>

                        </div>

                    </div>
                    <!-- Operaciones -->
                    <div>
                        <button @click="operaciones=!operaciones"
                            class="w-full flex items-center justify-between rounded-lg px-3 py-3 hover:bg-slate-800">

                            <div class="flex items-center gap-3">
                                <x-heroicon-o-arrow-path class="w-5 h-5" />
                                <span x-show="sidebar">
                                    Operaciones
                                </span>

                            </div>

                            <i x-show="sidebar" class="fa-solid fa-chevron-down text-xs transition-transform"
                                :class="operaciones ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="operaciones && sidebar" x-transition class="ml-8 mt-1 space-y-1">
                            <a href="{{route('clientes.pendientes') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Clientes con saldo
                            </a>
                            <a href="{{route('puntos.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Monedero
                            </a>
                            <a href="{{  route('reportes.index')  }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Reportes
                            </a>
                            <a href="{{  route('facturas.online')  }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Factura en linea
                            </a>
                        </div>
                    </div>
                    <!-- Administrador -->
                    <div>
                        <button @click="administracion=!administracion"
                            class="w-full flex items-center justify-between rounded-lg px-3 py-3 hover:bg-slate-800">

                            <div class="flex items-center gap-3">
                                <x-heroicon-o-cog-8-tooth class="w-5 h-5" />
                                <span x-show="sidebar">
                                    Administracion
                                </span>

                            </div>

                            <i x-show="sidebar" class="fa-solid fa-chevron-down text-xs transition-transform"
                                :class="administracion ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="administracion && sidebar" x-transition class="ml-8 mt-1 space-y-1">
                            <a href="{{route('cajas.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Cajas
                            </a>
                            <a href="{{route('sucursales.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Sucursales
                            </a>
                            <a href="{{route('bancos.index') }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Datos bancarios
                            </a>
                            <a href="{{  route('agentes.index')  }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Agentes
                            </a>
                            <a href="{{  route('auditoria.index')  }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Bitacora
                            </a>
                            <a href="{{  route('configuracion-empresa.show')  }}"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-800">
                                Empresa
                            </a>
                        </div>
                    </div>
                </nav>
            </aside>

            <!-- Contenido -->
            <main class="flex-1 bg-slate-100">
                {{-- @include('layouts.navigation') --}}
                <!-- Navbar -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset
                <div class="mx-auto p-2 container">
                    {{ $slot }}

                </div>
            </main>

        </div>
        <!-- Page Content -->
        {{-- <main class="w-full">
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

            </main> --}}


    </div>
</body>

</html>
