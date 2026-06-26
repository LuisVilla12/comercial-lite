@section('title', content: 'Detalles de los certificados')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Certificados almacenados
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <form id="formCertificados" method="POST" action="{{ route('certificados-empresa.store') }}"   enctype="multipart/form-data" class="">
        @csrf
<label class="block text-md font-medium text-gray-700 mb-1">
                Registrados: {{ $certificado->created_at->format('d/m/Y H:i') }} <span class="text-red-500">*</span>
            </label>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4">
        {{-- CERTIFICADO --}}
        <div class="md:col-span-2 lg:col-span-1 mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Certificado (.cer): <span class="text-red-500">*</span>
            </label>
        </div>
        {{-- LLAVE PRIVADA --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Llave privada (.key): <span class="text-red-500">*</span>
            </label>
        </div>
        {{-- <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Contraseña del CSD: <span class="text-red-500">*</span>
            </label>
        </div> --}}
    </div>
        {{-- Botones --}}
        <div class="flex justify-between items-center gap-4 mt-3">
            <a href="{{ route('configuracion-empresa.show') }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
            </a>
            <div x-data @keydown.window.prevent.f10="$refs.btnEntrada.click()">
                <button
                    x-ref="btnEntrada"
                    type="submit"
                    id="btnGuardar"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Actualizar [F10]
                </button>
            </div>
        </div>
    </form>
    </div>
</x-app-layout>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formCertificados');
    const btn  = document.getElementById('btnGuardar');
    if (!form || !btn) return;
    form.addEventListener('submit', function () {
        btn.disabled = true;
        btn.innerText = 'Guardando...';
    });
});
</script>
