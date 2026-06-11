<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Selecciona una empresa
                </h2>
                <p class="text-gray-600 dark:text-white">
                    Elige la empresa con la que deseas trabajar
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- LISTA DE EMPRESAS --}}
                @forelse ($empresas as $empresa)

                    <form method="POST" action="{{route('empresas.select')}}">
                        @csrf
                        <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

                        <button type="submit"
                            class="w-full text-left bg-white border rounded-xl shadow hover:shadow-md transition p-5">

                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $empresa->nombre }}
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        ID: {{ $empresa->id }}
                                    </p>
                                </div>

                                <div class="text-blue-600 font-bold">
                                    Entrar →
                                </div>
                            </div>

                        </button>
                    </form>

                @empty

                    <div class="col-span-3 text-center text-gray-500 dark:text-white">
                        No tienes empresas asignadas
                    </div>

                @endforelse

                {{-- CREAR EMPRESA --}}
                <a href="{{ route('empresas.create') }}"
                   class="flex items-center justify-center border-2 border-dashed rounded-xl p-6 text-gray-600 hover:bg-gray-50 transition dark:text-white">

                    + Crear nueva empresa
                </a>

            </div>
        </div>
    </div>
</x-app-layout>