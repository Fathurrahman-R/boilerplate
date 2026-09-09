{{-- Versi tanpa nomor halaman, untuk daftar yang tidak dihitung totalnya. --}}

@php
    $tombol = 'inline-flex h-[30px] items-center gap-1.5 rounded-sm border-[0.5px] border-line bg-fill-3 px-3 text-sm text-ink-secondary shadow-sm transition hover:bg-fill-2 ';
    $mati = 'inline-flex h-[30px] cursor-not-allowed items-center gap-1.5 rounded-sm border border-line bg-surface-sunken px-3 text-sm text-ink-muted ';
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center gap-1.5">
        @if ($paginator->onFirstPage())
            <span class="{{ $mati }}" aria-disabled="true">
                <x-ui.icon name="chevron-left" class="size-4" />
                {{ __('pagination.previous') }}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $tombol }}">
                <x-ui.icon name="chevron-left" class="size-4" />
                {{ __('pagination.previous') }}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $tombol }}">
                {{ __('pagination.next') }}
                <x-ui.icon name="chevron-right" class="size-4" />
            </a>
        @else
            <span class="{{ $mati }}" aria-disabled="true">
                {{ __('pagination.next') }}
                <x-ui.icon name="chevron-right" class="size-4" />
            </span>
        @endif
    </nav>
@endif
