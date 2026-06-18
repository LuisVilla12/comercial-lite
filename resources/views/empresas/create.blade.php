@section('title', content: 'Registrar empresa')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar Empresa
        </h2>
    </x-slot>

    <div class="mb-2 max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <form method="POST" action="{{ route('empresas.store') }}" class="">
            @csrf
            <h3 class="text-lg font-semibold mb-4">Datos generales de la empresa</h3>
            <div class="md:grid  md:grid-cols-3 md:gap-4">
                <div class="mb-2 ">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Codigo: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="codigo" placeholder="Codigo" value="{{ old('codigo') }}"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('codigo')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Nombre --}}
                <div class="mb-2">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Nombre de la empresa: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" placeholder="Nombre de la empresa" value="{{ old('nombre') }}"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('nombre')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- RFC --}}
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        RFC <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="rfc" placeholder="RFC" value="{{ old(key: 'rfc') }}"
                        class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('rfc')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Botones --}}
            <div class="mb-2 md:col-span-full flex justify-between gap-3 mt-4">
                <a href="{{ route('empresas.index') }}"
                    class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                                    <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar

                </a>

                <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Guardar empresa
                </button>

            </div>
        </form>
    </div>


</x-app-layout>
