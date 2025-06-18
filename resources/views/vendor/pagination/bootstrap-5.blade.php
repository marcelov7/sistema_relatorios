@if ($paginator->hasPages())
    <nav aria-label="Navegação da paginação">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <!-- Informações dos resultados -->
            <div class="mb-2 mb-sm-0">
                <p class="small text-muted mb-0">
                    Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} 
                    de {{ $paginator->total() }} resultados
                </p>
            </div>

            <!-- Links de paginação -->
            <div>
                <ul class="pagination pagination-sm mb-0">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link">
                                <i class="bi bi-chevron-left"></i>
                                <span class="d-none d-sm-inline ms-1">Anterior</span>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                                <i class="bi bi-chevron-left"></i>
                                <span class="d-none d-sm-inline ms-1">Anterior</span>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled d-none d-sm-block" aria-disabled="true">
                                <span class="page-link">{{ $element }}</span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active d-none d-sm-block" aria-current="page">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item d-none d-sm-block">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                                <span class="d-none d-sm-inline me-1">Próximo</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link">
                                <span class="d-none d-sm-inline me-1">Próximo</span>
                                <i class="bi bi-chevron-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
