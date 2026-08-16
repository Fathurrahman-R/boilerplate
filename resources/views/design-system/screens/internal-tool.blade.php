@php
    // Pola "daftar + panel detail": tulang punggung hampir semua internal tool.
    $queue = [
        ['ref' => 'REQ-4821', 'title' => 'Pencairan dana operasional', 'owner' => 'Maya S.', 'amount' => 'Rp 18.400.000', 'status' => 'Menunggu', 'variant' => 'warning', 'active' => true],
        ['ref' => 'REQ-4820', 'title' => 'Perpanjangan lisensi CRM', 'owner' => 'Rangga A.', 'amount' => 'Rp 6.900.000', 'status' => 'Menunggu', 'variant' => 'warning', 'active' => false],
        ['ref' => 'REQ-4819', 'title' => 'Pengadaan laptop tim data', 'owner' => 'Dinda P.', 'amount' => 'Rp 42.100.000', 'status' => 'Ditinjau', 'variant' => 'info', 'active' => false],
        ['ref' => 'REQ-4818', 'title' => 'Sewa gudang Cakung', 'owner' => 'Bayu H.', 'amount' => 'Rp 27.500.000', 'status' => 'Disetujui', 'variant' => 'success', 'active' => false],
        ['ref' => 'REQ-4817', 'title' => 'Reimburse perjalanan Surabaya', 'owner' => 'Maya S.', 'amount' => 'Rp 3.240.000', 'status' => 'Ditolak', 'variant' => 'danger', 'active' => false],
        ['ref' => 'REQ-4816', 'title' => 'Iklan pameran September', 'owner' => 'Nadia K.', 'amount' => 'Rp 11.750.000', 'status' => 'Disetujui', 'variant' => 'success', 'active' => false],
    ];

    $timeline = [
        ['time' => '14:02', 'actor' => 'Maya S.', 'event' => 'Mengajukan permohonan'],
        ['time' => '14:18', 'actor' => 'Sistem', 'event' => 'Cocok dengan anggaran Operasional Q3'],
        ['time' => '15:40', 'actor' => 'Rangga A.', 'event' => 'Meneruskan ke Kepala Keuangan'],
    ];
@endphp

<x-docs.screen :screen="$screen" :meta="$meta" url="tools.contoh.id/persetujuan">
    <div class="flex min-h-[720px] flex-col">

        <div class="flex items-center gap-3 border-b border-line bg-surface-sunken px-5 py-3">
            <span class="flex size-6 items-center justify-center rounded-sm bg-accent font-display text-[13px] font-bold text-accent-on">O</span>
            <span class="font-display text-[14.5px] font-semibold tracking-tight text-ink">Operasi Internal</span>

            <div class="ms-4 flex items-center gap-1.5 text-[13px] text-ink-muted">
                <span>Keuangan</span>
                <x-ui.icon name="chevron-right" class="size-3.5" />
                <span class="font-medium text-ink">Antrean persetujuan</span>
            </div>

            <div class="flex-1"></div>

            <span class="flex h-8 items-center gap-2 rounded-md border border-line bg-surface-raised px-2.5 text-[12.5px] text-ink-muted shadow-well">
                <x-ui.icon name="search" class="size-3.5" />
                Cari
                <kbd class="rounded-[4px] border border-line px-1.5 font-mono text-[11px]">⌘K</kbd>
            </span>
        </div>

        <div class="flex min-h-0 flex-1 flex-col xl:flex-row">

            {{-- Daftar: padat, angka mono, tanpa kedalaman di barisnya --}}
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2.5 border-b border-line px-5 py-3">
                    <x-ui.segmented :options="['menunggu' => 'Menunggu 12', 'semua' => 'Semua 148', 'saya' => 'Milik saya 4']" selected="menunggu" />

                    <div class="flex-1"></div>

                    <x-ui.button type="button" variant="secondary" size="sm">
                        <x-ui.icon name="sliders-horizontal" class="size-4" />
                        Filter
                    </x-ui.button>
                    <x-ui.button type="button" variant="secondary" size="sm">
                        <x-ui.icon name="download" class="size-4" />
                        Ekspor
                    </x-ui.button>
                </div>

                <table class="w-full text-left text-[13.5px] text-ink-secondary">
                    <thead class="bg-surface-sunken">
                        <tr>
                            <th class="px-5 py-2.5 text-xs font-semibold tracking-[0.04em] text-ink-muted uppercase">Ref</th>
                            <th class="px-5 py-2.5 text-xs font-semibold tracking-[0.04em] text-ink-muted uppercase">Permohonan</th>
                            <th class="px-5 py-2.5 text-xs font-semibold tracking-[0.04em] text-ink-muted uppercase">Pengaju</th>
                            <th class="px-5 py-2.5 text-xs font-semibold tracking-[0.04em] text-ink-muted uppercase">Status</th>
                            <th class="px-5 py-2.5 text-right text-xs font-semibold tracking-[0.04em] text-ink-muted uppercase">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($queue as $item)
                            <tr @class([
                                'border-t border-line',
                                'bg-surface-inset' => $item['active'],
                            ])>
                                <td class="num px-5 py-2.5 text-[12.5px]">{{ $item['ref'] }}</td>
                                <td class="px-5 py-2.5 text-ink">{{ $item['title'] }}</td>
                                <td class="px-5 py-2.5">{{ $item['owner'] }}</td>
                                <td class="px-5 py-2.5">
                                    <x-ui.badge :variant="$item['variant']" pill dot>{{ $item['status'] }}</x-ui.badge>
                                </td>
                                <td class="num px-5 py-2.5 text-right text-[12.5px]">{{ $item['amount'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex items-center justify-between gap-3 border-t border-line px-5 py-3 text-[13px] text-ink-muted">
                    <span>Menampilkan 1–6 dari 148</span>
                    <div class="flex gap-1.5">
                        <span class="flex size-8 items-center justify-center rounded-sm border border-line bg-surface">
                            <x-ui.icon name="chevron-left" class="size-4" />
                        </span>
                        <span class="flex size-8 items-center justify-center rounded-sm bg-accent text-[13px] font-semibold text-accent-on">1</span>
                        <span class="flex size-8 items-center justify-center rounded-sm border border-line bg-surface">
                            <x-ui.icon name="chevron-right" class="size-4" />
                        </span>
                    </div>
                </div>
            </div>

            {{-- Panel detail: 380–420px, selalu permukaan cekung --}}
            <aside class="w-full shrink-0 border-t border-line bg-surface-sunken xl:w-[400px] xl:border-t-0 xl:border-s">
                <div class="flex items-start justify-between gap-3 border-b border-line px-5 py-4">
                    <div>
                        <span class="num text-[12.5px] text-ink-muted">REQ-4821</span>
                        <h2 class="mt-0.5 font-display text-[17px] font-semibold text-ink">Pencairan dana operasional</h2>
                    </div>
                    <x-ui.badge variant="warning" pill dot>Menunggu</x-ui.badge>
                </div>

                <div class="space-y-5 px-5 py-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="eyebrow">Nilai</span>
                            <div class="num mt-1 text-[15px] text-ink">Rp 18.400.000</div>
                        </div>
                        <div>
                            <span class="eyebrow">Anggaran</span>
                            <div class="mt-1 text-[13.5px] text-ink">Operasional Q3</div>
                        </div>
                        <div>
                            <span class="eyebrow">Pengaju</span>
                            <div class="mt-1 text-[13.5px] text-ink">Maya S.</div>
                        </div>
                        <div>
                            <span class="eyebrow">Diajukan</span>
                            <div class="num mt-1 text-[13.5px] text-ink">{{ now()->subDays(2)->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <div>
                        <span class="eyebrow">Keterangan</span>
                        <p class="mt-1.5 text-[13.5px] text-ink-secondary">
                            Pembayaran vendor kebersihan dan keamanan untuk tiga gudang, periode Juli–September.
                            Kontrak terlampir.
                        </p>
                    </div>

                    <div>
                        <span class="eyebrow">Lampiran</span>
                        <div class="mt-2 flex flex-col gap-1.5">
                            @foreach (['kontrak-vendor-2026.pdf', 'rincian-biaya.xlsx'] as $file)
                                <span class="flex items-center gap-2.5 rounded-md border border-line bg-surface-raised px-3 py-2 text-[13px] text-ink">
                                    <x-ui.icon name="paperclip" class="size-3.5 text-ink-muted" />
                                    {{ $file }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <span class="eyebrow">Riwayat</span>
                        <div class="mt-2.5 space-y-3">
                            @foreach ($timeline as $entry)
                                <div class="flex gap-3">
                                    <span class="num shrink-0 text-[12px] text-ink-muted">{{ $entry['time'] }}</span>
                                    <div class="min-w-0 text-[13px]">
                                        <span class="font-medium text-ink">{{ $entry['actor'] }}</span>
                                        <span class="text-ink-secondary"> — {{ $entry['event'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2.5 border-t border-line px-5 py-4">
                    <x-ui.button type="button" class="flex-1">Setujui</x-ui.button>
                    <x-ui.button type="button" variant="secondary">Minta revisi</x-ui.button>
                    <x-ui.button type="button" variant="ghost">Tolak</x-ui.button>
                </div>
            </aside>
        </div>
    </div>
</x-docs.screen>
