@section('title', content: 'Registar certificados')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar certificados
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <form id="formCertificados" method="POST" action="{{ route('certificados-empresa.store') }}"   enctype="multipart/form-data" class="">
        @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4">
        {{-- CERTIFICADO --}}
 <div class="md:col-span-2 lg:col-span-1 mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Certificado (.cer): <span class="text-red-500">*</span>
            </label>
            <input type="file" name="cer" accept=".cer" required
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('cer')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

        </div>
        {{-- LLAVE PRIVADA --}}
        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Llave privada (.key): <span class="text-red-500">*</span>
            </label>
            <input type="file" name="key" accept=".key" required
                class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
             @error('key')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-2">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Contraseña del CSD: <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password" required
                   class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('password')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
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
                    Guardar [F10]
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
