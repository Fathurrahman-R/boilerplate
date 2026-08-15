@php($navigation = app(App\Support\Navigation\NavigationBuilder::class)->build())

<aside id="sidebar"
       class="fixed top-0 left-0 z-40 h-screen w-64 -translate-x-full border-r border-gray-200 bg-white transition-transform dark:border-gray-700 dark:bg-gray-800 sm:translate-x-0"
       aria-label="Menu utama">
    <div class="flex h-full flex-col overflow-y-auto px-3 py-4">
        <a href="{{ route('dashboard') }}" class="mb-6 flex items-center gap-2 px-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-700 text-sm font-bold text-white">
                {{ mb_substr(config('app.name'), 0, 1) }}
            </span>
            <span class="truncate text-lg font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
        </a>

        <ul class="space-y-1 font-medium">
            @foreach ($navigation as $index => $item)
                @if ($item['children'] === [])
                    <li>
                        <a href="{{ $item['url'] ?? '#' }}"
                           @class([
                               'flex items-center gap-3 rounded-lg px-3 py-2 text-sm',
                               'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' => $item['active'],
                               'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' => ! $item['active'],
                           ])>
                            @if ($item['icon'])
                                <x-ui.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                            @endif
                            <span class="truncate">{{ $item['label'] }}</span>
                        </a>
                    </li>
                @else
                    <li>
                        <button type="button"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                data-collapse-toggle="nav-group-{{ $index }}">
                            @if ($item['icon'])
                                <x-ui.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                            @endif
                            <span class="flex-1 truncate text-left">{{ $item['label'] }}</span>
                            <svg class="h-3 w-3 shrink-0" aria-hidden="true" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>

                        <ul id="nav-group-{{ $index }}" @class(['space-y-1 py-1', 'hidden' => ! $item['active']])>
                            @foreach ($item['children'] as $child)
                                <li>
                                    <a href="{{ $child['url'] ?? '#' }}"
                                       @class([
                                           'flex items-center gap-3 rounded-lg py-2 pl-11 pr-3 text-sm',
                                           'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' => $child['active'],
                                           'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' => ! $child['active'],
                                       ])>
                                        <span class="truncate">{{ $child['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        </ul>

        <div class="mt-auto border-t border-gray-200 pt-3 dark:border-gray-700">
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                <x-ui.avatar :user="auth()->user()" size="sm" />
                <span class="min-w-0 flex-1">
                    <span class="block truncate font-medium">{{ auth()->user()->name }}</span>
                    <span class="block truncate text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</span>
                </span>
            </a>
        </div>
    </div>
</aside>
