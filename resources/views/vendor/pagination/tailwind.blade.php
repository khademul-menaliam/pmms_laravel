@if ($paginator->hasPages())
    <nav class="section-row" role="navigation" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="btn btn-soft">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-soft">Previous</a>
        @endif

        <div class="section-row">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="btn btn-soft">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="btn btn-primary">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="btn btn-soft">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-soft">Next</a>
        @else
            <span class="btn btn-soft">Next</span>
        @endif
    </nav>
@endif
