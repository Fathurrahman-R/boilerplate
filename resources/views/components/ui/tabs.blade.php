@props([
    'id' => null,
    'tabs' => [],
])

{{--
    $tabs berbentuk ['nama' => 'Label']; isi tiap tab dikirim lewat slot
    bernama sama.

    Garis penanda tab aktif meluncur di antara tab, bukan berpindah dengan
    dilepas di satu tempat lalu digambar ulang di tempat lain. Panel yang
    masuk juga bergeser dari arah asal perpindahan — gerakan antaranya
    menunjuk ke arah tujuannya, bukan sekadar berganti isi.
--}}

@php
    $id ??= 'tabs-'.Str::random(8);
    $keys = array_keys($tabs);
@endphp

<div x-data="slidingIndicator({ values: @js(array_map('strval', $keys)) })"
     x-init="indicatorInit(0)"
     x-id="['{{ $id }}']">

    <div class="border-b-[0.5px] border-line">
        <ul x-ref="track" class="relative -mb-px flex flex-wrap" role="tablist">
            <span x-ref="indicator"
                  class="absolute bottom-0 start-0 h-[2px] rounded-full bg-accent"
                  aria-hidden="true"></span>

            @foreach ($tabs as $key => $label)
                <li role="presentation">
                    <button type="button" role="tab" data-segment
                            id="{{ $id }}-{{ $key }}-tab"
                            aria-controls="{{ $id }}-{{ $key }}"
                            :tabindex="index === {{ $loop->index }} ? 0 : -1"
                            :aria-selected="picked === @js((string) $key)"
                            x-on:click="select({{ $loop->index }})"
                            x-on:keydown.arrow-right.prevent="select((index + 1) % values.length); items[index].focus()"
                            x-on:keydown.arrow-left.prevent="select((index - 1 + values.length) % values.length); items[index].focus()"
                            x-on:keydown.home.prevent="select(0); items[0].focus()"
                            x-on:keydown.end.prevent="select(values.length - 1); items[index].focus()"
                            class="px-3.5 py-3 text-base transition-colors duration-[--dur-fast] outline-none focus-visible:shadow-[var(--focus-ring)]"
                            :class="picked === @js((string) $key)
                                ? 'font-semibold text-ink'
                                : 'text-ink-secondary hover:text-ink'">
                        {{ $label }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    @foreach ($tabs as $key => $label)
        <div id="{{ $id }}-{{ $key }}" role="tabpanel" tabindex="0"
             aria-labelledby="{{ $id }}-{{ $key }}-tab"
             x-show="picked === @js((string) $key)" x-cloak
             x-spring="{ from: { opacity: 0, y: 4 }, to: { opacity: 1, y: 0 }, token: 'move', exitToken: 'overlay' }"
             class="py-5 outline-none">
            {{ $$key ?? '' }}
        </div>
    @endforeach
</div>
