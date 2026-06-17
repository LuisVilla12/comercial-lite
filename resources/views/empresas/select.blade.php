<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Catálogo de empresas
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

        {{-- ENCABEZADO --}}
        <div class="mb-6">
            <p class="text-2xl text-gray-600 dark:text-white">
                Selecciona una empresa para ingresar
            </p>
        </div>

        {{-- MENSAJE DE ÉXITO --}}
        @if (session('success'))
            <p x-data="{ show: true }"
               x-show="show"
               x-transition
               x-init="setTimeout(() => show = false, 4000)"
               class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6">
                {{ session('success') }}
            </p>
        @endif

        {{-- EMPRESAS --}}
        <div class="mb-10">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                Empresas
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                @forelse ($empresas as $empresa)
                    <form method="POST" action="{{ route('empresas.select') }}">
                        @csrf

                        <input type="hidden"
                               name="empresa_id"
                               value="{{ $empresa->id }}">

                        <button type="submit"
                            class="w-full text-left bg-white dark:bg-gray-800
                                   border border-gray-200 dark:border-gray-700
                                   rounded-xl shadow-sm hover:shadow-lg
                                   transition duration-200 p-5">

                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $empresa->nombre }}
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        RFC: {{ $empresa->rfc }}
                                    </p>
                                </div>

                                <div class="text-blue-600 font-semibold">
                                    Entrar →
                                </div>
                            </div>

                        </button>
                    </form>

                @empty
                    <div class="col-span-3 text-center py-8 text-gray-500 dark:text-gray-400">
                        No tienes empresas asignadas.
                    </div>
                @endforelse

            </div>
        </div>
        @if (auth()->user()->isAdmin() )
            {{-- ADMINISTRACIÓN --}}
        <div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                Panel de Administración
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- INDEX EMPRESAS --}}
                <a href="{{ route('empresas.index') }}"
                    class="flex flex-col items-center justify-center
                           bg-white dark:bg-gray-800
                           border-2 border-dashed border-gray-300 dark:border-gray-600
                           rounded-xl p-8
                           hover:bg-gray-50 dark:hover:bg-gray-700
                           transition duration-200">
                    <x-heroicon-o-circle-stack class="w-8 h-8 dark:text-white" />
                    <span class="mt-3 text-md text-center font-semibold text-gray-700 dark:text-white">
                        Empresas
                    </span>

                    <span class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-center">
                        Administra las empresas registradas.
                    </span>
                </a>
                {{-- CREAR EMPRESA --}}
                <a href="{{ route('empresas.create') }}"
                    class="flex flex-col items-center justify-center
                           bg-white dark:bg-gray-800
                           border-2 border-dashed border-gray-300 dark:border-gray-600
                           rounded-xl p-8
                           hover:bg-gray-50 dark:hover:bg-gray-700
                           transition duration-200">

                    <x-heroicon-o-building-storefront class="w-8 h-8 dark:text-white" />
                    <span class="mt-3 text-md text-center font-semibold text-gray-700 dark:text-white">
                        Crear empresa
                    </span>

                    <span class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-center">
                        Registrar una nueva empresa en el sistema.
                    </span>
                </a>

                {{-- Administrar USUARIOs --}}
                <a href="{{ route('usuarios.index') }}"
                    class="flex flex-col items-center justify-center
                           bg-white dark:bg-gray-800
                           border-2 border-dashed border-gray-300 dark:border-gray-600
                           rounded-xl p-8
                           hover:bg-gray-50 dark:hover:bg-gray-700
                           transition duration-200">
                    <x-heroicon-o-users class="w-8 h-8 dark:text-white" />
                    <span class="mt-3 text-md text-center font-semibold text-gray-700 dark:text-white">
                    Usuarios
                    </span>

                    <span class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-center">
                        Administra todos los usuarios registrados.
                    </span>
                </a>

                {{-- CREAR USUARIO --}}
                <a href="{{ route('register') }}"
                    class="flex flex-col items-center justify-center
                           bg-white dark:bg-gray-800
                           border-2 border-dashed border-gray-300 dark:border-gray-600
                           rounded-xl p-8
                           hover:bg-gray-50 dark:hover:bg-gray-700
                           transition duration-200">
                    <x-heroicon-o-user-plus class="w-8 h-8 dark:text-white" />
                    <span class="mt-3 text-md font-semibold text-gray-700 dark:text-white">
                        Crear usuario
                    </span>

                    <span class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-center">
                        Registrar un nuevo usuario.
                    </span>
                </a>
                {{-- SESIONES ACTIVAS --}}
                <a href="{{ route('sesiones.index') }}"
                    class="flex flex-col items-center justify-center
                           bg-white dark:bg-gray-800
                           border-2 border-dashed border-gray-300 dark:border-gray-600
                           rounded-xl p-8
                           hover:bg-gray-50 dark:hover:bg-gray-700
                           transition duration-200">
                    <x-heroicon-o-user-minus class="w-8 h-8 dark:text-white" />
                    <span class="mt-3 text-md font-semibold text-gray-700 dark:text-white">
                        Sesiones Activas
                    </span>

                    <span class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-center">
                        Administra las sesiones
                    </span>
                </a>

            </div>
        </div>

        @endif

    </div>
</div>
</x-app-layout>
