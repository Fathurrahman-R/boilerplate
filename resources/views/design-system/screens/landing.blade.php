@php
    $features = [
        ['icon' => 'repeat', 'title' => 'Faktur berulang', 'body' => 'Tetapkan sekali, terbit sendiri tiap periode. Nomor urut dan pajaknya ikut.'],
        ['icon' => 'bell-ring', 'title' => 'Pengingat bertingkat', 'body' => 'Tiga hari sebelum, hari-H, lalu tiap minggu — sampai dibayar atau Anda hentikan.'],
        ['icon' => 'arrow-left-right', 'title' => 'Rekonsiliasi otomatis', 'body' => 'Mutasi bank dicocokkan ke faktur. Yang tidak cocok dinaikkan untuk ditinjau.'],
        ['icon' => 'file-check', 'title' => 'Persetujuan berjenjang', 'body' => 'Faktur di atas nilai tertentu menunggu tanda tangan sebelum terkirim.'],
        ['icon' => 'qr-code', 'title' => 'QRIS dan transfer', 'body' => 'Klien membayar dari halaman faktur; statusnya berubah tanpa Anda sentuh.'],
        ['icon' => 'download', 'title' => 'Ekspor ke akuntansi', 'body' => 'CSV yang sudah berformat, atau dorong langsung lewat API.'],
    ];

    $plans = [
        ['name' => 'Rintisan', 'price' => 'Rp 149rb', 'note' => '/bulan', 'body' => 'Sampai 50 faktur per bulan.', 'items' => ['Faktur berulang', 'Pengingat otomatis', 'Satu rekening bank'], 'featured' => false],
        ['name' => 'Tumbuh', 'price' => 'Rp 449rb', 'note' => '/bulan', 'body' => 'Faktur tanpa batas, lima pengguna.', 'items' => ['Semua di Rintisan', 'Rekonsiliasi otomatis', 'Persetujuan berjenjang', 'Ekspor akuntansi'], 'featured' => true],
        ['name' => 'Perusahaan', 'price' => 'Hubungi kami', 'note' => '', 'body' => 'Untuk tim keuangan dengan aturan sendiri.', 'items' => ['Semua di Tumbuh', 'SSO', 'Jejak audit', 'Pendampingan migrasi'], 'featured' => false],
    ];
@endphp

<x-docs.screen :screen="$screen" :meta="$meta" url="nusatagih.id">
    {{-- Hero berdiri di atas latar bertekstur; itulah syarat kaca terbaca. --}}
    <div class="bg-glow relative pb-16">
        <div class="bg-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-[1000px] px-7 pt-5">
            <nav class="glass flex h-14 items-center gap-5 rounded-full ps-5 pe-2.5">
                <span class="flex shrink-0 items-center gap-2.5">
                    <span class="flex size-[22px] items-center justify-center rounded-sm bg-accent font-display text-xs font-bold text-accent-on">N</span>
                    <span class="font-display text-[15px] font-semibold tracking-tight text-ink">Nusatagih</span>
                </span>

                <div class="hidden flex-1 gap-1 md:flex">
                    @foreach (['Produk', 'Harga', 'Dokumentasi', 'Pelanggan'] as $link)
                        <span class="rounded-full px-3 py-1.5 text-[13.5px] text-ink-secondary">{{ $link }}</span>
                    @endforeach
                </div>

                <span class="ms-auto px-2 text-[13.5px] text-ink-secondary md:ms-0">Masuk</span>
                <span class="flex h-9 items-center rounded-full bg-[image:var(--mat-accent)] px-[17px] text-[13.5px] font-semibold text-accent-on shadow-[var(--bevel),var(--lift)]">
                    Coba gratis
                </span>
            </nav>

            <div class="mx-auto max-w-[720px] pt-[68px] text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-line bg-surface-raised px-3.5 py-1.5 text-[12.5px] text-ink-secondary">
                    <span class="size-1.5 rounded-full bg-success"></span>
                    Penagihan otomatis kini mendukung QRIS
                </span>

                <h1 class="mt-5 font-display text-[38px] leading-[1.05] font-semibold tracking-[-0.035em] text-ink sm:text-[50px]">
                    Penagihan yang berhenti memakan hari Senin Anda.
                </h1>

                <p class="mx-auto mt-4 max-w-[54ch] text-[17px] text-ink-secondary">
                    Faktur, pengingat, dan rekonsiliasi berjalan sendiri. Tim keuangan tinggal menyetujui — bukan
                    menyalin nomor dari satu tab ke tab lain.
                </p>

                <div class="mt-7 flex flex-wrap justify-center gap-3">
                    <span class="flex h-12 items-center gap-2 rounded-md bg-[image:var(--mat-accent)] px-6 text-[15px] font-semibold text-accent-on shadow-md">
                        Mulai 14 hari gratis
                        <x-ui.icon name="arrow-up-right" class="size-[17px]" />
                    </span>
                    <span class="flex h-12 items-center gap-2 rounded-md border border-line-strong bg-[image:var(--mat-raised)] px-5 text-[15px] font-medium text-ink shadow-[var(--bevel),var(--lift)]">
                        <x-ui.icon name="play" class="size-4" />
                        Lihat demo
                    </span>
                </div>

                <p class="mt-3.5 text-[12.5px] text-ink-muted">Tanpa kartu kredit · Migrasi data dibantu</p>
            </div>

            {{-- Bingkai pratinjau: kaca membungkus permukaan solid --}}
            <div class="glass mt-12 rounded-xl p-3.5">
                <div class="overflow-hidden rounded-lg border border-line bg-surface-raised">
                    <div class="grid gap-px bg-line sm:grid-cols-3">
                        <div class="bg-surface-raised p-5">
                            <span class="eyebrow">Terkirim</span>
                            <div class="mt-1.5 font-display text-[26px] font-semibold tabular-nums text-ink">1.284</div>
                            <div class="mt-1 flex items-center gap-1.5 text-[13px] text-success">
                                <x-ui.icon name="trending-up" class="size-3.5" />+68 bulan ini
                            </div>
                        </div>
                        <div class="bg-surface-raised p-5">
                            <span class="eyebrow">Dibayar tepat waktu</span>
                            <div class="mt-1.5 font-display text-[26px] font-semibold tabular-nums text-ink">92,6%</div>
                            <div class="mt-1 text-[13px] text-ink-muted">Sebelumnya 71,4%</div>
                        </div>
                        <div class="bg-surface-raised p-5">
                            <span class="eyebrow">Jam kerja hemat</span>
                            <div class="mt-1.5 font-display text-[26px] font-semibold tabular-nums text-ink">31/bln</div>
                            <div class="mt-1 text-[13px] text-ink-muted">Rata-rata pelanggan</div>
                        </div>
                    </div>

                    <div class="border-t border-line p-5">
                        <x-ui.table :headers="['Faktur', 'Klien', 'Status', 'Nilai']">
                            <x-ui.table.row>
                                <x-ui.table.cell class="num text-[12.5px]">INV-2048</x-ui.table.cell>
                                <x-ui.table.cell>PT Nusantara Jaya</x-ui.table.cell>
                                <x-ui.table.cell><x-ui.badge variant="success" pill dot>Lunas</x-ui.badge></x-ui.table.cell>
                                <x-ui.table.cell numeric>Rp 24.850.000</x-ui.table.cell>
                            </x-ui.table.row>
                            <x-ui.table.row>
                                <x-ui.table.cell class="num text-[12.5px]">INV-2047</x-ui.table.cell>
                                <x-ui.table.cell>Sinar Abadi</x-ui.table.cell>
                                <x-ui.table.cell><x-ui.badge variant="warning" pill dot>Menunggu</x-ui.badge></x-ui.table.cell>
                                <x-ui.table.cell numeric>Rp 8.120.000</x-ui.table.cell>
                            </x-ui.table.row>
                        </x-ui.table>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-9 opacity-70">
                @foreach (['Nusantara', 'Sinar Abadi', 'Delta', 'Aksara', 'Kopi Rakyat'] as $logo)
                    <span class="font-display text-[17px] font-semibold text-ink-muted">{{ $logo }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="border-t border-line bg-surface-raised px-7 py-16">
        <div class="mx-auto max-w-[1000px]">
            <h2 class="max-w-[20ch] font-display text-[30px] leading-tight font-semibold tracking-[-0.03em] text-ink">
                Semua yang biasanya dikerjakan manual, dikerjakan sistem.
            </h2>

            <div class="mt-9 grid gap-px overflow-hidden rounded-lg border border-line bg-line sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($features as $feature)
                    <div class="bg-surface-raised p-6">
                        <x-ui.icon :name="$feature['icon']" class="size-5 text-accent" />
                        <h3 class="mt-3.5 font-display text-[17px] font-semibold text-ink">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink-secondary">{{ $feature['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="border-t border-line px-7 py-16">
        <div class="mx-auto max-w-[1000px]">
            <h2 class="font-display text-[30px] leading-tight font-semibold tracking-[-0.03em] text-ink">Harga</h2>
            <p class="mt-2.5 max-w-[54ch] text-[15px] text-ink-secondary">Bayar per bulan, berhenti kapan saja.</p>

            <div class="mt-9 grid gap-4 lg:grid-cols-3">
                @foreach ($plans as $plan)
                    <div @class([
                        'flex flex-col rounded-lg border bg-surface-raised p-6',
                        'border-accent shadow-md' => $plan['featured'],
                        'border-line shadow-sm' => ! $plan['featured'],
                    ])>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-display text-[17px] font-semibold text-ink">{{ $plan['name'] }}</h3>
                            @if ($plan['featured'])
                                <x-ui.badge variant="primary" pill>Paling sering dipilih</x-ui.badge>
                            @endif
                        </div>

                        <p class="mt-1.5 text-sm text-ink-secondary">{{ $plan['body'] }}</p>

                        <div class="mt-5 flex items-baseline gap-1.5">
                            <span class="font-display text-[30px] font-semibold tabular-nums text-ink">{{ $plan['price'] }}</span>
                            <span class="text-[13px] text-ink-muted">{{ $plan['note'] }}</span>
                        </div>

                        <ul class="mt-5 flex-1 space-y-2.5">
                            @foreach ($plan['items'] as $item)
                                <li class="flex items-start gap-2.5 text-sm text-ink-secondary">
                                    <x-ui.icon name="check" class="mt-0.5 size-4 shrink-0 text-success" />
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>

                        <x-ui.button type="button" :variant="$plan['featured'] ? 'primary' : 'secondary'" block class="mt-6">
                            Pilih {{ $plan['name'] }}
                        </x-ui.button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="border-t border-line bg-surface-raised px-7 py-16 text-center">
        <h2 class="mx-auto max-w-[24ch] font-display text-[28px] leading-tight font-semibold tracking-[-0.03em] text-ink">
            Coba dulu dengan sepuluh faktur bulan depan.
        </h2>
        <div class="mt-6">
            <x-ui.button type="button" size="lg">Mulai 14 hari gratis</x-ui.button>
        </div>
    </div>
</x-docs.screen>
