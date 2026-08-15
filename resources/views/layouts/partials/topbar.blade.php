<header class="sticky top-0 z-30 border-b border-gray-200 bg-white/80 backdrop-blur dark:border-gray-700 dark:bg-gray-800/80">
    <div class="flex items-center gap-3 px-4 py-3">
        <button type="button"
                data-drawer-target="sidebar"
                data-drawer-toggle="sidebar"
                aria-controls="sidebar"
                class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 sm:hidden">
            <span class="sr-only">Buka menu</span>
            <x-ui.icon name="menu" class="h-6 w-6" />
        </button>

        <div class="min-w-0 flex-1"></div>

        <button type="button" data-theme-toggle
                class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700"
                title="Ganti tema">
            <span class="sr-only">Ganti tema</span>
            <x-ui.icon name="sun" class="hidden h-5 w-5 dark:block" />
            <x-ui.icon name="moon" class="h-5 w-5 dark:hidden" />
        </button>

        <x-ui.dropdown id="user-menu" placement="bottom-end">
            <x-slot:trigger>
                <x-ui.avatar :user="auth()->user()" size="sm" />
            </x-slot:trigger>

            <li class="border-b border-gray-100 px-4 py-3 text-sm text-gray-900 dark:border-gray-600 dark:text-white">
                <div class="truncate font-medium">{{ auth()->user()->name }}</div>
                <div class="truncate text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</div>
            </li>

            <x-ui.dropdown-item :href="route('profile.edit')">
                <x-ui.icon name="user" class="h-4 w-4" />
                Profil saya
            </x-ui.dropdown-item>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-2 px-4 py-2 text-left text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-gray-600">
                        <x-ui.icon name="logout" class="h-4 w-4" />
                        Keluar
                    </button>
                </form>
            </li>
        </x-ui.dropdown>
    </div>
</header>
