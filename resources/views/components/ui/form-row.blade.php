@props([
    'label' => null,
    'for' => null,
    'hint' => null,
    'error' => null,
    'required' => false,
    // Kontrol yang memang butuh lebar penuh (textarea, editor, matriks).
    // Labelnya naik ke atas alih-alih berdiri di sebelahnya.
    'stacked' => false,
])

{{--
    Satu field di dalam grup daftar (x-ui.list).

    Label di kiri, kontrol di kanan, pemisah menjorok — bukan label di atas
    kotak di dalam kartu. Bedanya bukan selera: dengan label dan kontrol
    sebaris, satu grup berisi enam field tingginya separuh, dan mata bisa
    menyapu kolom label untuk mencari yang dicari tanpa membaca isinya.

    Di layar sempit susunannya luruh jadi label di atas, karena dua kolom di
    lebar 360px menyisakan ruang yang terlalu sempit untuk keduanya.

        x-ui.list berisi x-ui.form-row label="Nama" for="name",
          dan di dalamnya x-ui.input name="name" 
--}}

<div {{ $attributes->class([
    'relative px-4 py-2.5',
    'not-first:before:absolute not-first:before:inset-x-4 not-first:before:top-0',
    'not-first:before:h-px not-first:before:bg-line not-first:before:content-[\'\']',
]) }}>
    <div @class([
        'flex gap-1.5',
        'flex-col' => $stacked,
        'flex-col sm:flex-row sm:items-center sm:gap-4' => ! $stacked,
    ])>
        @if ($label)
            <label
                @if ($for) for="{{ $for }}" @endif
                @class([
                    'shrink-0 text-base text-ink',
                    'pt-1' => $stacked,
                    'sm:w-[168px] sm:py-2' => ! $stacked,
                ])>
                {{ $label }}

                @if ($required)
                    <span class="text-danger" aria-hidden="true">*</span>
                @endif
            </label>
        @endif

        <div class="min-w-0 flex-1">
            {{ $slot }}
        </div>
    </div>

    @if ($error || $hint)
        <p @class([
            'mt-1 text-sm',
            'text-danger' => (bool) $error,
            'text-ink-muted' => ! $error,
            'sm:ps-[184px]' => ! $stacked,
        ])>
            {{ $error ?: $hint }}
        </p>
    @endif
</div>
