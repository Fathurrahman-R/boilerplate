@props([
    'id' => null,
    'number' => null,
    'eyebrow' => null,
    'title' => null,
    'lead' => null,
])

<section @if ($id) id="{{ $id }}" @endif {{ $attributes->class('scroll-mt-24 pt-16 first:pt-0') }}>
    @if ($number || $eyebrow)
        <h2 class="eyebrow">
            @if ($number){{ $number }} — @endif{{ $eyebrow }}
        </h2>
    @endif

    @if ($title)
        <h3 class="mt-3 font-display text-[26px] leading-tight font-semibold tracking-[-0.02em] text-ink sm:text-[30px]">
            {{ $title }}
        </h3>
    @endif

    @if ($lead)
        <p class="mt-2.5 max-w-[64ch] text-[15px] text-ink-secondary">{{ $lead }}</p>
    @endif

    <div @class(['mt-6' => $title || $lead || $eyebrow])>
        {{ $slot }}
    </div>
</section>
