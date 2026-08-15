<x-layouts.base>
    <div class="flex min-h-screen flex-col items-center justify-center gap-6 px-4 text-center">
        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-700 text-2xl font-bold text-white">
            {{ mb_substr(config('app.name'), 0, 1) }}
        </span>

        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</h1>
            <p class="mt-2 max-w-md text-gray-500 dark:text-gray-400">
                Boilerplate Laravel dengan autentikasi, RBAC berbasis resource key, dan komponen UI Flowbite.
            </p>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            @auth
                <x-ui.button :href="route('dashboard')">Buka dashboard</x-ui.button>
            @else
                <x-ui.button :href="route('login')">Masuk</x-ui.button>

                @if (Route::has('register'))
                    <x-ui.button :href="route('register')" variant="secondary">Daftar</x-ui.button>
                @endif
            @endauth
        </div>
    </div>
</x-layouts.base>
