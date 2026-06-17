@section('title', 'Kardex')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Kardex
        </h2>
    </x-slot>

<div class="px-4 py-4 mt-4 rounded-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">

        <x-dashboard-card
            href="{{ route('kardex.global') }}"
            title="Kardex global"
            desc="Generar kardex por todas las sucursales"
            bg="bg-orange-50 dark:bg-orange-900/20"
            iconBg="bg-orange-500"
            class="h-full"
        >
            <x-slot:icon>
                <x-heroicon-o-globe-americas class="w-10 h-10" />
            </x-slot:icon>
        </x-dashboard-card>

        <x-dashboard-card
            href="{{ route('kardex.sucursal') }}"
            title="Kardex por sucursal"
            desc="Generar kardex en una sucursal"
            bg="bg-red-50 dark:bg-red-900/20"
            iconBg="bg-red-500"
            class="h-full"
        >
            <x-slot:icon>
                <x-heroicon-o-building-storefront class="w-10 h-10" />
            </x-slot:icon>
        </x-dashboard-card>
    </div>


    </div>


</x-app-layout>
