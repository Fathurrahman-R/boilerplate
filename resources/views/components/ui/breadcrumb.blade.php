@props([
    'items' => [],
])

{{-- $items berbentuk ['Label' => url, 'Label terakhir' => null] --}}

<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center gap-1 text-sm md:gap-2">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-1.5 font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <svg class="h-3 w-3" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                </svg>
                Dashboard
            </a>
        </li>

        @foreach ($items as $label => $url)
            <li>
                <div class="flex items-center">
                    <svg class="mx-1 h-3 w-3 text-gray-400" aria-hidden="true" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>

                    @if ($url && ! $loop->last)
                        <a href="{{ $url }}" class="font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">{{ $label }}</a>
                    @else
                        <span class="font-medium text-gray-500 dark:text-gray-500" aria-current="page">{{ $label }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
