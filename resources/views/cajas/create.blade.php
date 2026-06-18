@section('title', 'Registar una caja ')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Crear una caja
        </h2>
    </x-slot>
    <div class="py-8">
    <div class="max-w-2xl mx-auto">

        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl">

            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold dark:text-white">
                    Apertura de Caja
                </h2>

                <p class="text-sm text-gray-500 mt-1 dark:text-white">
                    Registre el monto inicial con el que comenzará el turno.
                </p>
            </div>

            <form action="{{ route('cajas.store',$sucursal) }}" method="POST">
                @csrf

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4 px-6">

                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white">
                            Sucursal
                        </label>

                        <input
                            type="text"
                            value="{{ $sucursal->nombre }}"
                            class="w-full rounded-lg bg-gray-100"
                            readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white">
                            Usuario
                        </label>

                        <input
                            type="text"
                            value="{{ auth()->user()->name }}"
                            class="w-full rounded-lg bg-gray-100"
                            readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white">
                            Fecha y hora
                        </label>

                        <input
                            type="text"
                            value="{{ now()->format('d/m/Y H:i') }}"
                            class="w-full rounded-lg bg-gray-100"
                            readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white">
                            Monto inicial
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="monto_inicial"
                            class="w-full rounded-lg"
                            placeholder="0.00"
                            required>
                    </div>

                </div>

                <div class="flex justify-between gap-3 p-6 ">

                    <a href="{{ url()->previous() }}"
                       class="px-4 py-2 rounded-md border-red-100 font-medium flex text-white bg-red-600 hover:bg-red-600">
                        <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" /> Cancelar
                    </a>

                    <button
                        type="submit"
                        class="px-6 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

                        Abrir Caja

                    </button>

                </div>

            </form>

        </div>

    </div>
</div>
</x-app-layout>
