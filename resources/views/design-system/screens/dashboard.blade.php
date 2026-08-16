@php
    $nav = [
        ['label' => 'Ringkasan', 'icon' => 'layout-dashboard', 'active' => true],
        ['label' => 'Faktur', 'icon' => 'file-text', 'active' => false],
        ['label' => 'Klien', 'icon' => 'users', 'active' => false],
        ['label' => 'Pembayaran', 'icon' => 'credit-card', 'active' => false],
        ['label' => 'Laporan', 'icon' => 'chart-column', 'active' => false],
    ];

    $bars = [
        ['label' => 'Mar', 'height' => '38%', 'color' => 'bg-chart-1'],
        ['label' => 'Apr', 'height' => '52%', 'color' => 'bg-chart-1'],
        ['label' => 'Mei', 'height' => '46%', 'color' => 'bg-chart-1'],
        ['label' => 'Jun', 'height' => '71%', 'color' => 'bg-chart-1'],
        ['label' => 'Jul', 'height' => '64%', 'color' => 'bg-chart-1'],
        ['label' => 'Agu', 'height' => '92%', 'color' => 'bg-accent'],
    ];

    $rows = [
        ['id' => 'INV-2048', 'client' => 'PT Nusantara Jaya', 'status' => 'Lunas', 'variant' => 'success', 'amount' => 'Rp 24.850.000'],
        ['id' => 'INV-2047', 'client' => 'Sinar Abadi', 'status' => 'Menunggu', 'variant' => 'warning', 'amount' => 'Rp 8.120.000'],
        ['id' => 'INV-2046', 'client' => 'Delta Logistik', 'status' => 'Jatuh tempo', 'variant' => 'danger', 'amount' => 'Rp 3.400.000'],
        ['id' => 'INV-2045', 'client' => 'Aksara Media', 'status' => 'Lunas', 'variant' => 'success', 'amount' => 'Rp 12.700.000'],
        ['id' => 'INV-2044', 'client' => 'Kopi Rakyat', 'status' => 'Lunas', 'variant' => 'success', 'amount' => 'Rp 2.150.000'],
    ];
@endphp

<x-docs.screen :screen="$screen" :meta="$meta" url="app.contoh.id/ringkasan" texture backdrop="shell">
    <div class="flex min-h-[720px] gap-4 p-4">

        {{-- Sidebar kaca: panel terpisah, bukan kolom yang menempel di tepi. --}}
        <aside class="glass hidden w-56 shrink-0 flex-col gap-0.5 self-start rounded-xl p-3 lg:flex">
            <div class="mb-2 flex items-center gap-2.5 px-1.5 py-1">
                <span class="flex size-6 items-center justify-center rounded-sm bg-accent font-display text-[13px] font-bold text-accent-on">N</span>
                <span class="font-display text-[14.5px] font-semibold tracking-tight text-ink">Nusatagih</span>
            </div>

            <span class="eyebrow px-2.5 pb-1.5">Penagihan</span>

            @foreach ($nav as $item)
                <span @class([
                    'flex items-center gap-2.5 rounded-md px-2.5 py-2 text-sm',
                    'bg-surface-inset font-semibold text-ink' => $item['active'],
                    'text-ink-secondary' => ! $item['active'],
                ])>
                    <x-ui.icon :name="$item['icon']" class="size-[17px] shrink-0" />
                    {{ $item['label'] }}
                </span>
            @endforeach

            <div class="my-2 h-px bg-line"></div>

            <div class="flex items-center gap-2.5 px-2 py-1.5">
                <span class="flex size-[26px] items-center justify-center rounded-full border border-line bg-surface-inset text-[11px] font-semibold text-ink-secondary">RA</span>
                <div class="min-w-0">
                    <div class="truncate text-[13px] font-semibold text-ink">Rangga A.</div>
                    <div class="text-[11.5px] text-ink-muted">Keuangan</div>
                </div>
            </div>
        </aside>

        <div class="min-w-0 flex-1 space-y-4">
            {{-- Topbar kaca --}}
            <div class="glass flex items-center gap-3 rounded-xl px-4 py-2.5">
                <div class="flex items-center gap-1.5 text-[13px] text-ink-muted">
                    <span>Workspace</span>
                    <x-ui.icon name="chevron-right" class="size-3.5" />
                    <span class="font-medium text-ink">Ringkasan</span>
                </div>

                <div class="flex-1"></div>

                <x-ui.segmented :options="['30h' => '30 hari', '90h' => '90 hari', 'ytd' => 'Tahun ini']" selected="30h" />
            </div>

            {{-- Baris metrik: satu-satunya lapisan kaca di area konten --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <x-ui.stat label="MRR" value="Rp 412jt" delta="+12,4% vs bulan lalu" trend="up" />
                <x-ui.stat label="Faktur terbit" value="1.284" delta="+68" trend="up" />
                <x-ui.stat label="Retensi" value="94,2%" delta="Stabil" trend="flat" />
                <x-ui.stat label="Churn" value="1,8%" delta="+0,3%" trend="down" />
            </div>

            {{-- Mulai dari sini semuanya turun ke permukaan solid --}}
            <div class="grid gap-4 xl:grid-cols-[1.6fr_1fr]">
                <x-ui.card title="Volume penagihan" subtitle="Enam bulan terakhir">
                    <div class="flex h-40 items-end gap-3">
                        @foreach ($bars as $bar)
                            <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                                <div class="w-full rounded-t-[5px] rounded-b-[2px] {{ $bar['color'] }}" style="height:{{ $bar['height'] }}"></div>
                                <span class="font-mono text-[10.5px] text-ink-muted">{{ $bar['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card title="Perlu ditindaklanjuti">
                    <div class="space-y-3.5">
                        <div class="flex items-start gap-3">
                            <x-ui.icon name="triangle-alert" class="mt-0.5 size-4 shrink-0 text-warning" />
                            <div>
                                <div class="text-sm font-semibold text-ink">3 faktur lewat jatuh tempo</div>
                                <p class="text-[13px] text-ink-secondary">Total Rp 9.840.000 dari dua klien.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <x-ui.icon name="circle-alert" class="mt-0.5 size-4 shrink-0 text-danger" />
                            <div>
                                <div class="text-sm font-semibold text-ink">Kartu Sinar Abadi kedaluwarsa</div>
                                <p class="text-[13px] text-ink-secondary">Penagihan berikutnya akan gagal.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <x-ui.icon name="info" class="mt-0.5 size-4 shrink-0 text-info" />
                            <div>
                                <div class="text-sm font-semibold text-ink">12 faktur menunggu persetujuan</div>
                                <p class="text-[13px] text-ink-secondary">Rata-rata menunggu 2 hari.</p>
                            </div>
                        </div>
                    </div>

                    <x-slot:footer>
                        <x-ui.button type="button" variant="secondary" size="sm" block>Buka daftar tindakan</x-ui.button>
                    </x-slot:footer>
                </x-ui.card>
            </div>

            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="flex-1 font-display text-base font-semibold text-ink">Faktur terbaru</h2>

                    <div class="relative">
                        <x-ui.icon name="search" class="pointer-events-none absolute inset-y-0 start-3 my-auto size-4 text-ink-muted" />
                        <input type="search" placeholder="Cari faktur"
                               class="h-9 w-52 rounded-md border border-line bg-surface-sunken ps-9 pe-3 text-[13.5px] text-ink shadow-well outline-none placeholder:text-ink-muted focus:border-accent focus:ring-3 focus:ring-accent-soft">
                    </div>

                    <x-ui.button type="button" variant="secondary" size="sm">
                        <x-ui.icon name="sliders-horizontal" class="size-4" />
                        Filter
                    </x-ui.button>
                </div>

                <x-ui.table :headers="['Faktur', 'Klien', 'Status', 'Jatuh tempo', 'Nilai']">
                    @foreach ($rows as $row)
                        <x-ui.table.row>
                            <x-ui.table.cell class="num text-[12.5px]">{{ $row['id'] }}</x-ui.table.cell>
                            <x-ui.table.cell>{{ $row['client'] }}</x-ui.table.cell>
                            <x-ui.table.cell>
                                <x-ui.badge :variant="$row['variant']" pill dot>{{ $row['status'] }}</x-ui.badge>
                            </x-ui.table.cell>
                            <x-ui.table.cell class="num text-[12.5px] text-ink-muted">
                                {{ now()->addDays($loop->index * 4 - 6)->format('d/m/Y') }}
                            </x-ui.table.cell>
                            <x-ui.table.cell numeric>{{ $row['amount'] }}</x-ui.table.cell>
                        </x-ui.table.row>
                    @endforeach
                </x-ui.table>
            </div>
        </div>
    </div>
</x-docs.screen>
