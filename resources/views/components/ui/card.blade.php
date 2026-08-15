@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
])

<div {{ $attributes->class('rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800') }}>
    @if ($title || $subtitle || isset($header))
        <div class="flex items-start justify-between gap-4 border-b border-gray-200 px-5 py-4 dark:border-gray-700">
            <div>
                @if ($title)
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>
                @endif

                @if ($subtitle)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
                @endif
            </div>

            @isset($actions)
                <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    <div @class(['p-5' => $padding])>
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">{{ $footer }}</div>
    @endisset
</div>
