<a href="{{ $href }}"
   class="flex items-center gap-4 p-5 rounded-xl shadow hover:shadow-md transition {{ $bg }} dark:bg-opacity-20">

    {{-- ICONO --}}
    <div class="p-2 rounded-lg text-white {{ $iconBg ?? 'bg-gray-500' }}">
        {{ $icon }}
    </div>

    {{-- TEXTO --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
            {{ $title }}
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ $desc }}
        </p>
    </div>
</a>
