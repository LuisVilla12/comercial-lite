@section('title', content: 'Ver Movimiento')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Detalles del movimiento
        </h2>
    </x-slot>
<div class="max-w-5xl mx-auto mt-6 space-y-6">

    {{-- 🔷 Encabezado --}}
    <div class="bg-white shadow rounded-xl p-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Detalle de Auditoría
            </h2>
            <p class="text-sm text-gray-500">
                Registro del sistema
            </p>
        </div>

        {{-- Evento badge --}}
        <div>
            @php
                $colors = [
                    'created' => 'bg-green-100 text-green-700',
                    'updated' => 'bg-yellow-100 text-yellow-700',
                    'deleted' => 'bg-red-100 text-red-700'
                ];
            @endphp

            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $colors[$audit->event] ?? 'bg-gray-100 text-gray-700' }}">
                {{ ucfirst($audit->event) }}
            </span>
        </div>

    </div>

    {{-- 🔷 Información general --}}
    <div class="bg-white shadow rounded-xl p-6 grid md:grid-cols-2 gap-6">

        <div>
            <p class="text-sm text-gray-500">Usuario</p>
            <p class="font-medium text-gray-800">{{ $audit->user->name }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Fecha</p>
            <p class="font-medium text-gray-800">{{ $audit->created_at->format('d/m/Y H:i:s') }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Módulo</p>
            <p class="font-medium text-gray-800">{{ class_basename($audit->auditable_type) }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Evento</p>
            <p class="font-medium text-gray-800 capitalize">{{ $audit->event }}</p>
        </div>

    </div>

    {{-- Cambios --}}
    <div class="grid md:grid-cols-2 gap-6">

        {{-- Valores anteriores --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h3 class="text-md font-semibold text-gray-700 mb-4">
                 Valores anteriores
            </h3>

            <div class="bg-gray-50 rounded-lg p-4 text-sm overflow-auto max-h-80">
                <pre class="whitespace-pre-wrap text-gray-700">
{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                </pre>
            </div>
        </div>

        {{-- Valores nuevos --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h3 class="text-md font-semibold text-gray-700 mb-4">
                Valores nuevos
            </h3>

            <div class="bg-gray-50 rounded-lg p-4 text-sm overflow-auto max-h-80">
                <pre class="whitespace-pre-wrap text-gray-700">
{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                </pre>
            </div>
        </div>

    </div>

    <div class="flex justify-end">
        <a href="{{ route('auditoria.index') }}"
            class="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
            Regresar
        </a>
    </div>

</div>
</x-app-layout>
