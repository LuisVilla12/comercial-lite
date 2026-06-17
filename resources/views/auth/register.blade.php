@section('title', content: 'Registrar usuario')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar Usuario
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <!-- Name -->
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-md font-medium text-gray-700 mb-1" for="name">
                        Nombre: <span class="text-red-500">*</span>
                    </label>
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <label class="block text-md font-medium text-gray-700 mb-1" for="username">
                        Nombre de usuario: <span class="text-red-500">*</span>
                    </label>
                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                        :value="old('username')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>
                <!-- Email Address -->
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1" for="email">
                        Correo electronico: <span class="text-red-500">*</span>
                    </label>
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Tipo:<span class="text-red-500">*</span>
                    </label>
                    <select name="tipo" id="tipo"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="" disabled selected>Seleccione una opcion</option>
                        <option value="1">Administrador</option>
                        <option value="2">Vendedor</option>
                        <option value="3">Compras</option>
                        <option value="4">Almacén</option>
                        <option value="5">Supervisor</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Empresa:<span class="text-red-500">*</span>
                    </label>
                    <select name="empresa_id" id="empresa_id"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="" disabled selected>Seleccione una opcion</option>
                        @foreach ($empresas as $empresa)
                            <option value="{{ $empresa->id }}">{{ $empresa->nombre }} </option>
                        @endforeach
                    </select>

                    @error('empresa_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Sucursal:<span class="text-red-500">*</span>
                    </label>
                    <select name="sucursal_id" id="sucursal_id"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="" disabled selected>Seleccione una opcion</option>

                    </select>

                    @error('sucursal_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <!-- Password -->
                <div class="mt-4">
                    <label class="block text-md font-medium text-gray-700 mb-1" for="password">
                        Contraseña: <span class="text-red-500">*</span>
                    </label>
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label class="block text-md font-medium text-gray-700 mb-1" for="password_confirmation">
                        Confirmar Contraseña: <span class="text-red-500">*</span>

                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>


            <div class="flex items-center justify-end mt-4 gap-4">
                <a href="{{ route('usuarios.index')  }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
            </a>

                <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Registar
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
<scrip>
    <script>
document.getElementById('empresa_id').addEventListener('change', async function() {

    const empresaId = this.value;
    const sucursalSelect = document.getElementById('sucursal_id');

    sucursalSelect.innerHTML =
        '<option value="">Cargando sucursales...</option>';

    const response = await fetch(`/empresas/${empresaId}/sucursales`);
    const sucursales = await response.json();

    sucursalSelect.innerHTML =
        '<option value="">Seleccione una sucursal</option>';

    sucursales.forEach(sucursal => {

        sucursalSelect.innerHTML += `
            <option value="${sucursal.id}">
                ${sucursal.nombre}
            </option>
        `;
    });

});
</script>
</scrip>
