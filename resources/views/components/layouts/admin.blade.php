@props([
    'title' => null,
    'heading' => null,
    'description' => null,
    'breadcrumb' => [],
])

<x-layouts.base :title="$title ?? $heading">
    @include('layouts.partials.sidebar')

    <div class="sm:ml-64">
        @include('layouts.partials.topbar')

        <main class="p-4 sm:p-6">
            @if ($breadcrumb !== [])
                <div class="mb-4">
                    <x-ui.breadcrumb :items="$breadcrumb" />
                </div>
            @endif

            @if ($heading || isset($actions))
                <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
                    <div>
                        @if ($heading)
                            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $heading }}</h1>
                        @endif

                        @if ($description)
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
                        @endif
                    </div>

                    @isset($actions)
                        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
                    @endisset
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</x-layouts.base>
