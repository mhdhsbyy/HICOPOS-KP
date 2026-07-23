@if ($paginator->hasPages())
    <nav class="flex items-center justify-between">
        <p class="text-sm text-[#8b684b]">
            Menampilkan {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} dari {{ $paginator->total() }} kasir
        </p>

        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-sm font-bold rounded-lg border border-[#e2d3c2] text-[#d4c8b8] cursor-default">
                    &laquo;
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1.5 text-sm font-bold rounded-lg border border-[#e2d3c2] text-[#5f4a3b] hover:bg-[#f0e8e0] transition">
                    &laquo;
                </a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1.5 text-sm font-bold rounded-lg border border-[#e2d3c2] text-[#d4c8b8] cursor-default">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1.5 text-sm font-bold rounded-lg bg-[#764706] text-white border border-[#764706]">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1.5 text-sm font-bold rounded-lg border border-[#e2d3c2] text-[#5f4a3b] hover:bg-[#f0e8e0] transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1.5 text-sm font-bold rounded-lg border border-[#e2d3c2] text-[#5f4a3b] hover:bg-[#f0e8e0] transition">
                    &raquo;
                </a>
            @else
                <span class="px-3 py-1.5 text-sm font-bold rounded-lg border border-[#e2d3c2] text-[#d4c8b8] cursor-default">
                    &raquo;
                </span>
            @endif
        </div>
    </nav>
@endif
