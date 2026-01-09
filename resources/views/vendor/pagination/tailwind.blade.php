@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mr-5">
        <ul class="inline-flex items-center space-x-1">

            {{-- Anterior --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        ‹
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="px-3 py-2 text-sm text-gray-600 bg-white border rounded-md hover:bg-gray-100 transition">
                        ‹
                    </a>
                </li>
            @endif

            {{-- Páginas --}}
            @foreach ($elements as $element)
                {{-- Separador --}}
                @if (is_string($element))
                    <li>
                        <span class="px-3 py-2 text-sm text-gray-500">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                {{-- Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="px-3 py-2 text-sm font-semibold text-white bg-blue-600 rounded-md shadow">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                   class="px-3 py-2 text-sm text-gray-700 bg-white border rounded-md hover:bg-blue-50 hover:text-blue-600 transition">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Siguiente --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="px-3 py-2 text-sm text-gray-600 bg-white border rounded-md hover:bg-gray-100 transition">
                        ›
                    </a>
                </li>
            @else
                <li>
                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        ›
                    </span>
                </li>
            @endif

        </ul>
    </nav>
@endif
