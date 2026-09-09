@props([
    'name' => null,
    'options' => [],
    'selected' => null,
])

{{--
    Pilihan tunggal yang jumlahnya sedikit dan setara — periode, mode
    tampilan, saringan cepat.

    Pil aktifnya meluncur, bukan lompat. Sebelumnya ia dilepas di satu tempat
    lalu digambar ulang di tempat lain, dan yang terbaca bukan satu benda yang
    berpindah melainkan dua benda yang muncul bergantian. Pil-nya juga bisa
    diseret: getaran dipicu tepat saat segmen di bawahnya berganti, bukan saat
    jari dilepas — supaya jelas apa yang menyebabkannya.

    $options berbentuk ['nilai' => 'Label'].

    Tanpa `name` komponen ini murni tampilan (state di Alpine). Dengan `name`,
    nilai terpilih ikut terkirim sebagai input tersembunyi.
--}}

@php
    $selected ??= array_key_first($options);
    $values = array_keys($options);
    $startIndex = array_search((string) $selected, array_map('strval', $values), true) ?: 0;
@endphp

<div x-data="slidingIndicator({
        draggable: true,
        values: @js(array_map('strval', $values)),
        changeEvent: 'segmented-change',
     })"
     x-init="indicatorInit({{ (int) $startIndex }})"
     {{ $attributes->class('inline-flex w-fit rounded-md bg-fill-4 p-[3px]') }}
     role="radiogroup">

    @if ($name)
        <input type="hidden" name="{{ $name }}" :value="picked">
    @endif

    <div x-ref="track" class="relative flex gap-0.5">
        {{-- Indikator hidup di belakang label, bukan menjadi latar salah satu
             tombol: itu yang membuatnya satu benda yang bergerak. --}}
        <span x-ref="indicator"
              class="absolute inset-y-0 start-0 rounded-sm bg-surface-raised shadow-sm"
              aria-hidden="true"></span>

        @foreach ($options as $value => $label)
            <button type="button" role="radio" data-segment
                    :tabindex="index === {{ $loop->index }} ? 0 : -1"
                    :aria-checked="picked === @js((string) $value)"
                    x-on:click="select({{ $loop->index }})"
                    x-on:keydown.arrow-right.prevent="select((index + 1) % values.length); $el.parentElement.children[index + 1]?.focus()"
                    x-on:keydown.arrow-left.prevent="select((index - 1 + values.length) % values.length)"
                    x-on:keydown.home.prevent="select(0)"
                    x-on:keydown.end.prevent="select(values.length - 1)"
                    class="relative z-10 rounded-sm px-4 py-1.5 text-base transition-colors duration-[--dur-fast] outline-none focus-visible:shadow-[var(--focus-ring)]"
                    :class="picked === @js((string) $value) ? 'font-semibold text-ink' : 'text-ink-secondary hover:text-ink'">
                {{ $label }}
            </button>
        @endforeach
    </div>
</div>
