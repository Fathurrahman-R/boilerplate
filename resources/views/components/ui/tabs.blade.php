@props([
    'id' => null,
    'tabs' => [],
])

{{--
    Tab Flowbite. $tabs berbentuk ['nama' => 'Label']; isi tiap tab dikirim
    lewat slot bernama sama:

        <x-ui.tabs :tabs="['profil' => 'Profil', 'keamanan' => 'Keamanan']">
            <x-slot:profil>...</x-slot:profil>
            <x-slot:keamanan>...</x-slot:keamanan>
        </x-ui.tabs>
--}}

@php
    $id ??= 'tabs-'.uniqid();
@endphp

<div>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <ul class="-mb-px flex flex-wrap text-sm font-medium" id="{{ $id }}" data-tabs-toggle="#{{ $id }}-content" role="tablist">
            @foreach ($tabs as $key => $label)
                <li role="presentation">
                    <button type="button" role="tab"
                            class="inline-block rounded-t-lg border-b-2 p-4 hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300"
                            id="{{ $id }}-{{ $key }}-tab"
                            data-tabs-target="#{{ $id }}-{{ $key }}"
                            aria-controls="{{ $id }}-{{ $key }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $label }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <div id="{{ $id }}-content">
        @foreach ($tabs as $key => $label)
            <div class="hidden py-4" id="{{ $id }}-{{ $key }}" role="tabpanel" aria-labelledby="{{ $id }}-{{ $key }}-tab">
                {{ $$key ?? '' }}
            </div>
        @endforeach
    </div>
</div>
