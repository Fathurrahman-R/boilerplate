@php
    $hint ??= null;
@endphp

<x-layouts.base :title="$title">
    <div class="flex min-h-screen flex-col items-center justify-center gap-4 px-4 text-center">
        <p class="text-6xl font-bold text-line-strong">{{ $code }}</p>

        <h1 class="text-2xl font-semibold text-ink">{{ $title }}</h1>

        <p class="max-w-md text-ink-muted">{{ $message }}</p>

        @if ($hint)
            <p class="max-w-md text-sm text-ink-muted">{{ $hint }}</p>
        @endif

        <div class="mt-2 flex flex-wrap items-center justify-center gap-2">
            <x-ui.button :href="url()->previous()" variant="secondary">Kembali</x-ui.button>

            @auth
                <x-ui.button :href="route('dashboard')">Ke dashboard</x-ui.button>
            @else
                <x-ui.button :href="url('/')">Ke beranda</x-ui.button>
            @endauth
        </div>
    </div>
</x-layouts.base>
