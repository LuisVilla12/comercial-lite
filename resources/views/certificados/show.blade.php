@section('title', content: 'Detalles de los certificados')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Certificados almacenados
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <form id="formCertificados" method="POST" action="{{ route('certificados-empresa.store') }}"
            enctype="multipart/form-data" class="">
            @csrf
            <label class="block text-md font-medium text-gray-700 mb-1">
                Registrados: {{ $certificado->created_at->format('d/m/Y H:i') }} <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4 mt-4">
                {{-- CERTIFICADO --}}
                <div class="md:col-span-2 lg:col-span-1 mb-2">
                    <label class="flex text-md font-medium text-gray-700 mb-1">
                        Certificado (.cer): <span class="text-red-500 mr-2">*</span><a
                            href="{{ basename($certificado->cer_path) }}"> <x-heroicon-o-folder-minus class="w-6 h-6" />
                        </a>
                    </label>
                </div>

                {{-- LLAVE PRIVADA --}}
                <div class="mb-2">
                    <label class="flex text-md font-medium text-gray-700 mb-1">
                        Llave privada (.key): <span class="text-red-500 mr-2">*</span> <a
                            href="{{ basename($certificado->cer_path) }}"> <x-heroicon-o-folder-minus class="w-6 h-6" />
                        </a>
                    </label>

                </div>

            </div>
            {{-- Botones --}}
            <div class="flex justify-between items-center gap-4 mt-3">
                <a href="{{ route('configuracion-empresa.show') }}"
                    class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                    <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" /> Regresar
                </a>
                <form action="{{ route('certificados-empresa.destroy',  $certificado) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-1  px-4 py-2 rounded-md border-red-100 font-medium   text-white bg-green-600 hover:bg-green-600"
                        onclick="return confirm('¿Estás seguro de que deseas eliminar los certificados?')">
                        <x-heroicon-o-trash class="w-4 h-4" />
                        <span class="hidden sm:inline">Eliminar</span>
                    </button>
                </form>
            </div>
        </form>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formCertificados');
        const btn = document.getElementById('btnGuardar');
        if (!form || !btn) return;
        form.addEventListener('submit', function() {
            btn.disabled = true;
            btn.innerText = 'Guardando...';
        });
    });
</script>
