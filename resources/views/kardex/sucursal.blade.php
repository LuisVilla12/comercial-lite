@section('title', 'Kardex Sucursal')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Kardex de producto por sucursal
        </h2>
    </x-slot>



    <div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">
        <h2 class="mb-4 font-semibold text-lg text-gray-800 ">
            Kardex de producto por sucursal
        </h2>
        <form method="POST" action="{{route('kardex.sucursal')}}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                 <div x-data="buscadorProductos()">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Producto
    </label>

    <div class="relative">
        <input
            type="text"
            x-model="search"
            @input.debounce.300ms="buscar"
            @focus="if(resultados.length) abierto = true"
            placeholder="Buscar por clave, código o nombre..."
            class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
            autocomplete="off"
            required
        >

        <input type="hidden" name="producto_id" :value="productoSeleccionado?.id">

        <div x-show="abierto"
             x-transition
             @click.outside="abierto = false"
             class="absolute z-50 mt-1 w-full bg-white border rounded-md shadow-lg max-h-64 overflow-y-auto">

            <template x-if="cargando">
                <div class="px-4 py-2 text-sm text-gray-500">
                    Buscando...
                </div>
            </template>

            <template x-if="!cargando && resultados.length === 0 && search.length >= 2">
                <div class="px-4 py-2 text-sm text-gray-500">
                    No se encontraron productos.
                </div>
            </template>

            <template x-for="producto in resultados" :key="producto.id">
                <button
                    type="button"
                    @click="seleccionar(producto)"
                    class="block w-full px-4 py-2 text-left hover:bg-indigo-50 border-b"
                >
                    <div class="font-medium" x-text="producto.nombre"></div>

                    <div class="text-xs text-gray-500">
                        Clave:
                        <span x-text="producto.clave"></span>
                        |
                        Código:
                        <span x-text="producto.codigo"></span>
                    </div>
                </button>
            </template>

        </div>
    </div>

    <template x-if="productoSeleccionado">
        <div class="mt-2 p-2 bg-green-50 rounded border text-sm">
            <strong>Producto seleccionado:</strong><br>

            <span x-text="productoSeleccionado.nombre"></span><br>

            <span class="text-gray-500">
                Clave: <span x-text="productoSeleccionado.clave"></span>
            </span>
        </div>
    </template>
</div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de movimiento
                    </label>
                    <select name="movimiento_id" id="movimiento_id"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="" selected disabled >Seleccione un tipo</option>
                        <option value="1" >Compras</option>
                        <option value="2" >Traslados</option>
                        <option value="3" >Remisiones/Facturas</option>
                        <option value="4" >Ajustes de almacen</option>
                        <option value="5" >Todos los movimientos</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Almacen:
                    </label>
                    <select name="almacen_id" id="almacen_id"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="" selected disabled >Seleccione un almacen</option>
                        @forelse ($almacenes as $almacen)
                            <option value="{{$almacen->id}}">{{$almacen->nombre}}</option>
                        @empty
                                                    <option value="1" >No hay almacenes registrados</option>
                        @endforelse
                    </select>
                </div>
                {{-- Fecha inicio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Desde
                    </label>
                    <input type="date" name="fecha_inicio"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                {{-- Fecha fin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Hasta
                    </label>
                    <input type="date" name="fecha_fin"
                        class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

            </div>


            <div class="mt-6 flex justify-between">
                <a href="{{ route('kardex.index') }}"
               class="px-4 py-2 rounded-md border-red-100 font-medium flex  text-white bg-red-600 hover:bg-red-600">
                <x-heroicon-o-arrow-long-left class="w-5 h-5 mr-2" />  Regresar
            </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white flex rounded-md hover:bg-indigo-700">
                     <x-heroicon-o-document class="w-5 h-5 mr-2" /> Generar
                </button>
            </div>
        </form>
    </div>

</x-app-layout>

<script>
function buscadorProductos() {
    return {
        search: '',
        resultados: [],
        abierto: false,
        cargando: false,
        productoSeleccionado: null,

        async buscar() {

            if (this.search.length < 2) {
                this.resultados = [];
                this.abierto = false;
                return;
            }

            this.cargando = true;

            try {

                const response = await fetch(
                    `/buscar/productos?q=${encodeURIComponent(this.search)}`
                );

                this.resultados = await response.json();

                this.abierto = true;

            } catch (error) {
                console.error(error);
            }

            this.cargando = false;
        },

        seleccionar(producto) {

            this.productoSeleccionado = producto;

            this.search =
                `${producto.clave} - ${producto.nombre}`;

            this.abierto = false;
        }
    }
}
</script>
