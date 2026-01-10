<x-guest-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Registrar Usuario
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Name -->
        <div>
             <label class="block text-md font-medium text-gray-700 mb-1" for="name">
                Nombre: <span class="text-red-500">*</span>
            </label>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="grid md:grid-cols-2 gap-4">
      <!-- Email Address -->
        <div class="mt-4">
        <label class="block text-md font-medium text-gray-700 mb-1" for="email">
                Correo electronico: <span class="text-red-500">*</span>
            </label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
         <div class="mt-4">
            <label class="block text-md font-medium text-gray-700 mb-1">
                Tipo:<span class="text-red-500">*</span>
            </label>
            <select name="tipo" id="tipo"
                    class="p-3 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="" disabled selected>Seleccione una opcion</option>
                <option value="1">Administrador</option>
                <option value="2">Operador</option>
            </select>
            @error('tipo')
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
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
        <label class="block text-md font-medium text-gray-700 mb-1" for="password_confirmation">
                Confirmar Contraseña: <span class="text-red-500">*</span>

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        </div>


        <div class="flex items-center justify-end mt-4 gap-4">
            <a href="{{ route('usuarios.index') }}"
               class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                Cancelar
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                Registar Usuario
            </button>
        </div>
    </form>
    </div>
</x-guest-layout>
