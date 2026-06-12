@section('title', content: 'Editar usuario' )

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Editar Usuario
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <form method="POST" action="{{ route('usuarios.update',$usuario) }}">
            @csrf
                    @method('PUT')
            <!-- Name -->
            <div class="grid md:grid-cols-3 gap-2">
                <div>
                    <label class="block text-md font-medium text-gray-700 mb-1" for="name">
                        Nombre: <span class="text-red-500">*</span>
                    </label>
                    <x-text-input id="name" class="block mt-1 w-full " type="text" name="name"
                        :value="$usuario->name" autocomplete="name" />
                </div>
                <div>
                    <label class="block text-md font-medium text-gray-700 mb-1" for="username">
                        Nombre de usuario: <span class="text-red-500">*</span>
                    </label>
                    <x-text-input id="username" class="block mt-1 w-full " type="text" name="username"
                        :value="$usuario->username"  required autofocus autocomplete="name" />
                </div>
                <!-- Email Address -->
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1" for="email">
                        Correo electronico: <span class="text-red-500">*</span>
                    </label>
                    <x-text-input id="email" class="block mt-1 w-full " type="email" name="email"
                        :value="$usuario->email" required autocomplete="username" />
                </div>
                <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Tipo:<span class="text-red-500">*</span>
                    </label>
                    <select name="tipo" id="tipo"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="1" @selected($usuario->tipo == 1)>Administrador</option>
                        <option value="2"@selected($usuario->tipo == 2)>Vendedor</option>
                        <option value="3"@selected($usuario->tipo == 3)>Compras</option>
                        <option value="4"@selected($usuario->tipo == 4)>Almacén</option>
                        <option value="5"@selected($usuario->tipo == 5)>Supervisor</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                 <div class="">
                    <label class="block text-md font-medium text-gray-700 mb-1">
                        Empresa asignada:<span class="text-red-500">*</span>
                    </label>
                    <select name="empresa_id" id="empresa_id"
                        class="p-2 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @foreach ($empresas as $empresa)

                        <option value="{{ $empresa->id }}" @selected($usuario->empresa_id == $empresa->id)>{{ $empresa->nombre }}</option>
                        @endforeach
                    </select>
                    @error('empresa_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            <div class="flex items-center justify-end mt-4 gap-4">
                <a href="{{ route('usuarios.index') }}"
                    class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                    Regresar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
