<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Ver Empresa
        </h2>
    </x-slot>
        @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mt-4 rounded-md mb-4">{{ session('success') }}
        </p>
    @endif
    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-4">
        <div class=" ">
            <div class="md:grid  md:grid-cols-3 md:gap-4">
                @csrf
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Codigo: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="codigo" placeholder="Codigo" value="{{ $empresa->codigo }}" readonly
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">

                </div>
                {{-- Nombre --}}
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Nombre de la empresa: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" placeholder="Nombre de la empresa"
                        value="{{ $empresa->nombre }}" readonly
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                </div>
                {{-- RFC --}}
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        RFC <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="rfc" placeholder="RFC" value="{{ $empresa->rfc }}" readonly
                        class="p-2 w-full uppercase rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed">
                </div>
            </div>
        </div>

               {{-- Botones --}}
        <div class="md:col-span-full flex justify-end gap-3 mt-4">
            <a href="{{ route('empresas.index') }}"
                class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                Regresar
            </a>
        </div>
    </div>


</x-app-layout>
