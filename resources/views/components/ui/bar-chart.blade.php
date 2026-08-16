@props([
    // Batang tunggal : [['label' => 'Jan', 'value' => 42], …]
    // Batang bertumpuk: [['label' => 'Jan', 'values' => [12, 30]], …]
    'series' => [],
    // Skala sumbu-Y. Kosong berarti ApexCharts memilih sendiri dari data.
    'max' => null,
    'height' => 184,
    // Warna per segmen, dari bawah ke atas — nama token di app.css (chart-1..6).
    'tones' => ['chart-1', 'chart-2'],
])

@php
    $categories = array_map(fn (array $point) => $point['label'] ?? '', $series);
    $stacked = collect($series)->contains(fn (array $point) => isset($point['values']));

    if ($stacked) {
        $segments = max([0, ...array_map(fn (array $point) => count($point['values'] ?? []), $series)]);

        $data = collect(range(0, $segments - 1))->map(fn (int $i) => [
            'name' => 'Seri '.($i + 1),
            'data' => array_map(fn (array $point) => $point['values'][$i] ?? 0, $series),
        ])->all();
    } else {
        $data = [[
            'name' => 'Nilai',
            'data' => array_map(fn (array $point) => $point['value'] ?? 0, $series),
        ]];
    }
@endphp

{{--
    Dirender ApexCharts (lihat Alpine.data('apexBarChart', …) di resources/js/app.js).
    Blade hanya membentuk ulang $series jadi categories/data ApexCharts dan
    menitipkan nama token warnanya — pembacaan nilai CSS-nya sendiri terjadi
    di JS, saat mount dan tiap kali tema berganti.
--}}

<div x-data="apexBarChart({
        categories: @js($categories),
        data: @js($data),
        tones: @js($tones),
        stacked: @js($stacked),
        max: @js($max),
     })"
     x-init="mount($el)"
     {{ $attributes->class('w-full') }}
     style="height: {{ $height }}px"></div>
