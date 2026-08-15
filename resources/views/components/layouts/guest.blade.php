@props([
    'title' => null,
    'heading' => null,
    'description' => null,
])

<x-layouts.base :title="$title ?? $heading">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">
        <a href="{{ url('/') }}" class="mb-6 flex items-center gap-2 text-xl font-semibold text-gray-900 dark:text-white">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-700 text-white">
                {{ mb_substr(config('app.name'), 0, 1) }}
            </span>
            {{ config('app.name') }}
        </a>

        <div class="w-full max-w-md rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-8">
            @if ($heading)
                <h1 class="text-xl font-bold text-gray-900 dark:text-white md:text-2xl">{{ $heading }}</h1>
            @endif

            @if ($description)
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
            @endif

            <div class="mt-6 space-y-4">
                {{ $slot }}
            </div>
        </div>

        <button type="button" data-theme-toggle
                class="mt-6 rounded-lg p-2 text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700">
            <span class="sr-only">Ganti tema</span>
            <x-ui.icon name="sun" class="hidden h-5 w-5 dark:block" />
            <x-ui.icon name="moon" class="h-5 w-5 dark:hidden" />
        </button>
    </div>
</x-layouts.base>
