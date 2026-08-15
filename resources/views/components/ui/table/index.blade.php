@props([
    'headers' => [],
    'table' => null,
    'empty' => 'Belum ada data',
])

{{--
    Kerangka tabel.

    $headers berbentuk ['kolom_db' => 'Label'] atau ['Label'] untuk kolom yang
    tidak bisa diurutkan. Kalau $table (TableBuilder) diberikan, header kolom
    yang terdaftar sebagai sortable otomatis jadi tautan pengurutan.

    Kontainer punya overflow-x-auto sendiri supaya tabel lebar menggulir di
    dalam kartunya, bukan membuat seluruh halaman menggulir mendatar.
--}}

<div class="relative overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <table {{ $attributes->class('w-full text-left text-sm text-gray-500 dark:text-gray-400') }}>
        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                @isset($head)
                    {{ $head }}
                @else
                    @foreach ($headers as $column => $label)
                        @php($sortable = $table && is_string($column) && $table->isSortable($column))

                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                            @if ($sortable)
                                <a href="{{ $table->sortUrl($column) }}" class="inline-flex items-center gap-1 hover:text-gray-900 dark:hover:text-white">
                                    {{ $label }}

                                    @if ($table->sortColumn() === $column)
                                        <svg class="h-3 w-3 {{ $table->sortDirection() === 'desc' ? 'rotate-180' : '' }}"
                                             aria-hidden="true" fill="none" viewBox="0 0 10 6">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2" d="m1 5 4-4 4 4"/>
                                        </svg>
                                    @else
                                        <svg class="h-3 w-3 opacity-30" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 3l4 5H8l4-5zm0 18l-4-5h8l-4 5z"/>
                                        </svg>
                                    @endif
                                </a>
                            @else
                                {{ $label }}
                            @endif
                        </th>
                    @endforeach
                @endisset
            </tr>
        </thead>

        <tbody>
            {{ $slot }}
        </tbody>
    </table>

    @isset($emptyState)
        {{ $emptyState }}
    @endisset
</div>
