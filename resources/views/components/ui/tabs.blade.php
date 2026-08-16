@props([
    'id' => null,
    'tabs' => [],
])

{{--
    $tabs berbentuk ['nama' => 'Label']; isi tiap tab dikirim lewat slot
    bernama sama:

        <x-ui.tabs :tabs="['profil' => 'Profil', 'keamanan' => 'Keamanan']">
            <x-slot:profil>...</x-slot:profil>
            <x-slot:keamanan>...</x-slot:keamanan>
        </x-ui.tabs>
--}}

@php
    $id ??= 'tabs-'.Str::random(8);
    $first = array_key_first($tabs);
@endphp

<div x-data="{ tab: @js($first) }">
    <div class="border-b border-line">
        <ul class="-mb-px flex flex-wrap gap-1" role="tablist">
            @foreach ($tabs as $key => $label)
                <li role="presentation">
                    <button type="button" role="tab"
                            id="{{ $id }}-{{ $key }}-tab"
                            aria-controls="{{ $id }}-{{ $key }}"
                            :aria-selected="tab === @js($key)"
                            x-on:click="tab = @js($key)"
                            class="border-b-2 px-3 py-3.5 text-sm transition outline-none focus-visible:ring-3 focus-visible:ring-accent-soft"
                            :class="tab === @js($key)
                                ? 'border-accent font-semibold text-ink'
                                : 'border-transparent text-ink-secondary hover:text-ink'">
                        {{ $label }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    @foreach ($tabs as $key => $label)
        <div id="{{ $id }}-{{ $key }}" role="tabpanel" aria-labelledby="{{ $id }}-{{ $key }}-tab"
             x-show="tab === @js($key)" x-cloak class="py-5">
            {{ $$key ?? '' }}
        </div>
    @endforeach
</div>
