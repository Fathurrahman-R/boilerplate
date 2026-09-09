@php
    $nav = [
        'Fondasi' => [
            '#prinsip' => 'Prinsip',
            '#warna' => 'Warna',
            '#tipografi' => 'Tipografi',
            '#spacing' => 'Spacing & grid',
            '#permukaan' => 'Permukaan & kaca',
            '#material' => 'Material',
            '#motion' => 'Motion',
            '#gesture' => 'Gesture',
            '#ikon' => 'Ikon',
        ],
    ];

    $principles = [
        ['icon' => 'zap', 'title' => 'Merespons sebelum dilepas', 'body' => 'Umpan balik muncul saat jari turun, bukan saat dilepas. Begitu ada jeda, rasa langsungnya jatuh dan tidak bisa dikembalikan.'],
        ['icon' => 'undo-2', 'title' => 'Bisa disela kapan saja', 'body' => 'Apa pun yang bergerak boleh ditangkap dan dibalik di tengah jalan, berangkat dari posisi yang sedang terlihat — bukan dari awal.'],
        ['icon' => 'layers', 'title' => 'Material menandai hierarki', 'body' => 'Makin struktural sebuah wilayah, makin tebal materialnya. Translusensi menyampaikan lapisan, bukan menghias permukaan.'],
        ['icon' => 'contrast', 'title' => 'AA bukan target, tapi syarat', 'body' => 'Teks minimal 4.5:1, elemen UI 3:1 — di kedua mode, termasuk di atas material.'],
    ];

    $surfaces = [
        ['token' => 'surface', 'use' => 'Latar halaman', 'class' => 'bg-surface'],
        ['token' => 'surface-raised', 'use' => 'Card, panel, popover', 'class' => 'bg-surface-raised'],
        ['token' => 'surface-sunken', 'use' => 'Sidebar, header tabel, input', 'class' => 'bg-surface-sunken'],
        ['token' => 'surface-inset', 'use' => 'Hover, track, well', 'class' => 'bg-surface-inset'],
    ];

    $status = [
        ['token' => 'accent', 'class' => 'bg-accent'],
        ['token' => 'success', 'class' => 'bg-success'],
        ['token' => 'warning', 'class' => 'bg-warning'],
        ['token' => 'danger', 'class' => 'bg-danger'],
        ['token' => 'info', 'class' => 'bg-info'],
    ];

    $charts = ['chart-1', 'chart-2', 'chart-3', 'chart-4', 'chart-5', 'chart-6'];

    $type = [
        ['name' => '6xl / 56', 'track' => '-0,030em', 'lead' => '1,04', 'sample' => 'Pendapatan', 'class' => 'text-6xl font-semibold'],
        ['name' => '5xl / 44', 'track' => '-0,026em', 'lead' => '1,08', 'sample' => 'Pendapatan', 'class' => 'text-5xl font-semibold'],
        ['name' => '4xl / 34', 'track' => '-0,022em', 'lead' => '1,15', 'sample' => 'Judul halaman', 'class' => 'text-4xl font-semibold'],
        ['name' => '3xl / 28', 'track' => '-0,019em', 'lead' => '1,22', 'sample' => 'Ringkasan bulanan', 'class' => 'text-3xl font-semibold'],
        ['name' => '2xl / 24', 'track' => '-0,016em', 'lead' => '1,28', 'sample' => 'Transaksi terakhir', 'class' => 'text-2xl font-semibold'],
        ['name' => 'xl / 20', 'track' => '-0,012em', 'lead' => '1,35', 'sample' => 'Metode pembayaran', 'class' => 'text-xl font-semibold'],
        ['name' => 'lg / 17', 'track' => '-0,008em', 'lead' => '1,45', 'sample' => 'Judul kartu', 'class' => 'text-lg font-semibold'],
        ['name' => 'body / 15', 'track' => '-0,003em', 'lead' => '1,55', 'sample' => 'Ukuran default seluruh antarmuka. Panjang baris ideal 60–75 karakter.', 'class' => 'text-body text-ink-secondary'],
        ['name' => 'base / 14', 'track' => '0', 'lead' => '1,50', 'sample' => 'Label kontrol dan isi tabel', 'class' => 'text-base text-ink-secondary'],
        ['name' => 'sm / 13', 'track' => '+0,004em', 'lead' => '1,50', 'sample' => 'Helper text, caption di bawah chart', 'class' => 'text-sm text-ink-secondary'],
        ['name' => 'xs / 12', 'track' => '+0,008em', 'lead' => '1,45', 'sample' => 'Kepala tabel, keterangan', 'class' => 'text-xs text-ink-secondary'],
        ['name' => '2xs / 11', 'track' => '+0,012em', 'lead' => '1,45', 'sample' => 'Eyebrow, kbd, badge', 'class' => 'text-2xs text-ink-secondary'],
    ];

    $spacing = [
        ['name' => '1', 'px' => '4px', 'w' => 'w-1', 'use' => 'Jarak ikon ke teks'],
        ['name' => '2', 'px' => '8px', 'w' => 'w-2', 'use' => 'Antar-kontrol berdekatan'],
        ['name' => '3', 'px' => '12px', 'w' => 'w-3', 'use' => 'Padding dalam chip'],
        ['name' => '4', 'px' => '16px', 'w' => 'w-4', 'use' => 'Jarak antar-kartu'],
        ['name' => '5', 'px' => '20px', 'w' => 'w-5', 'use' => 'Padding kartu'],
        ['name' => '6', 'px' => '24px', 'w' => 'w-6', 'use' => 'Gutter grid docs'],
        ['name' => '9', 'px' => '38px', 'w' => 'w-9', 'use' => 'Tinggi kontrol default'],
        ['name' => '16', 'px' => '64px', 'w' => 'w-16', 'use' => 'Jarak antar-seksi'],
    ];

    // Spring tidak punya durasi; waktu diamnya muncul sendiri dari dua angka
    // yang bisa dibayangkan perancang: seberapa jauh ia melewati target, dan
    // seberapa cepat ia sampai.
    $motion = [
        ['name' => 'snap', 'spec' => 'damping 1,0 · response 0,25', 'use' => 'Tekanan, fokus, centang, rotasi chevron'],
        ['name' => 'move', 'spec' => 'damping 1,0 · response 0,40', 'use' => 'Indikator meluncur, judul menciut, tinggi accordion'],
        ['name' => 'sheet', 'spec' => 'damping 0,8 · response 0,30', 'use' => 'Drawer, sidebar, modal, command palette'],
        ['name' => 'throw', 'spec' => 'damping 0,75 · response 0,35', 'use' => 'Hanya setelah dilepas dengan kecepatan'],
        ['name' => 'pop', 'spec' => 'damping 0,8 · response 0,40', 'use' => 'Knob toggle, segmen menempel, momen berhasil'],
        ['name' => 'overlay', 'spec' => 'damping 1,0 · response 0,22', 'use' => 'Peredup, ramp blur, tooltip'],
    ];

    // Contoh kode wajib lahir di dalam blok @php. Blade mengompilasi tag <x-…>
    // di mana pun ia menemukannya — termasuk di dalam nilai atribut — dan
    // isinya akan ikut dirender, bukan ditampilkan sebagai teks. Blok @php
    // disimpan utuh sebelum tahap itu berjalan.
    $iconCode = <<<'BLADE'
    <x-ui.icon name="trash-2" class="size-4" />
    <x-ui.icon name="trash-2" class="size-5" />
    <x-ui.icon name="trash-2" class="size-6" />
    BLADE;

    $gestures = [
        ['title' => 'Umpan balik saat jari turun', 'body' => 'Bukan saat dilepas. Begitu ada jeda, rasa langsungnya jatuh dan tidak bisa diselamatkan lagi oleh apa pun di belakangnya.'],
        ['title' => 'Menempel di titik yang dipegang', 'body' => 'Elemen tidak melompat ke tengah jari. Jarak pegang dicatat saat pointer turun dan dipertahankan sepanjang seretan.'],
        ['title' => 'Kecepatan dari beberapa sampel', 'body' => 'Bukan dari selisih satu frame — satu frame terlalu berisik untuk dipakai melempar. Jendelanya sekitar 100 ms terakhir.'],
        ['title' => 'Arah dikunci setelah 10px', 'body' => 'Sebelum itu semua kemungkinan gerakan masih hidup berdampingan; yang kalah dibatalkan setelah maksudnya jelas, bukan ditebak sejak sentuhan pertama.'],
    ];

    $icons = ['house', 'users', 'shield-check', 'key', 'file-text', 'link', 'settings', 'search',
        'plus', 'pencil', 'trash-2', 'download', 'upload', 'check', 'x', 'circle-alert',
        'triangle-alert', 'info', 'chevron-down', 'chevron-right', 'sliders-horizontal', 'inbox',
        'trending-up', 'log-out'];
@endphp

<x-layouts.docs title="Fondasi" :nav="$nav">
    <div class="pb-4">
        <span class="eyebrow text-accent!">Design system</span>
        <h1 class="mt-3.5 max-w-[15ch] text-4xl leading-[1.05] font-semibold tracking-[-0.03em] text-ink sm:text-5xl">
            Sistem untuk produk bisnis yang padat informasi.
        </h1>
        <p class="mt-4 max-w-[62ch] text-lg text-ink-secondary">
            Fondasi visual untuk admin dashboard, SaaS, dan internal tools. Netral abu kebiruan, satu aksen biru,
            tipografi sistem, dan translusensi yang menyampaikan lapisan — bukan yang menghias permukaan.
        </p>

        <div class="mt-6 flex flex-wrap gap-2">
            @foreach (['Terang & gelap setara', 'WCAG AA ketat', 'Token semantik', 'Tanpa varian dark:'] as $chip)
                <span class="rounded-full border border-line bg-surface-raised px-3 py-1.5 font-mono text-xs text-ink-secondary">{{ $chip }}</span>
            @endforeach
        </div>
    </div>

    {{-- ──────────────────────────────────────────────────────── Prinsip --}}
    <x-docs.section id="prinsip" number="01" eyebrow="Prinsip">
        <div class="grid gap-px overflow-hidden rounded-lg border border-line bg-line sm:grid-cols-2">
            @foreach ($principles as $principle)
                <div class="bg-surface-raised p-6">
                    <x-ui.icon :name="$principle['icon']" class="size-5 text-accent" />
                    <h4 class="mt-3 text-lg font-semibold text-ink">{{ $principle['title'] }}</h4>
                    <p class="mt-2 text-sm text-ink-secondary">{{ $principle['body'] }}</p>
                </div>
            @endforeach
        </div>
    </x-docs.section>

    {{-- ────────────────────────────────────────────────────────── Warna --}}
    <x-docs.section id="warna" number="02" eyebrow="Warna"
                    title="Token semantik, bukan nama warna"
                    lead="Developer tidak perlu tahu #131923. Yang perlu diketahui: panel duduk di surface-raised. Setiap token punya pasangan di kedua mode, dan berganti sendiri saat data-theme berubah.">

        <h4 class="eyebrow mb-3">Permukaan</h4>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($surfaces as $surface)
                <x-docs.swatch :token="$surface['token']" :use="$surface['use']" :class="$surface['class']" />
            @endforeach
        </div>

        <h4 class="eyebrow mt-8 mb-3">Teks &amp; garis</h4>
        <div class="overflow-hidden rounded-md border border-line bg-surface-raised">
            <div class="flex items-center gap-4 border-b border-line px-4 py-3">
                <span class="w-40 shrink-0 font-mono text-sm">ink</span>
                <span class="flex-1 text-body text-ink">Judul, angka, label utama</span>
            </div>
            <div class="flex items-center gap-4 border-b border-line px-4 py-3">
                <span class="w-40 shrink-0 font-mono text-sm">ink-secondary</span>
                <span class="flex-1 text-body text-ink-secondary">Paragraf, deskripsi, isi tabel</span>
            </div>
            <div class="flex items-center gap-4 border-b border-line px-4 py-3">
                <span class="w-40 shrink-0 font-mono text-sm">ink-muted</span>
                <span class="flex-1 text-body text-ink-muted">Caption, timestamp, helper — bukan untuk teks penting</span>
            </div>
            <div class="flex items-center gap-4 border-b border-line px-4 py-3">
                <span class="w-40 shrink-0 font-mono text-sm">line</span>
                <span class="h-px flex-1 bg-line"></span>
                <span class="font-mono text-xs text-ink-muted">pemisah</span>
            </div>
            <div class="flex items-center gap-4 px-4 py-3">
                <span class="w-40 shrink-0 font-mono text-sm">line-strong</span>
                <span class="h-px flex-1 bg-line-strong"></span>
                <span class="font-mono text-xs text-ink-muted">input, fokus</span>
            </div>
        </div>

        <h4 class="eyebrow mt-8 mb-3">Aksen &amp; status</h4>
        <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ($status as $item)
                <x-docs.swatch :token="$item['token']" :class="$item['class']" />
            @endforeach
        </div>

        <p class="mt-3 text-base text-ink-muted">
            Warna status hanya dipakai untuk menyampaikan arti. Begitu dipakai sebagai hiasan, warnanya berhenti
            berbicara.
        </p>

        <h4 class="eyebrow mt-8 mb-3">Palet chart</h4>
        <div class="flex flex-wrap gap-3">
            @foreach ($charts as $chart)
                <div class="flex items-center gap-2 rounded-full border border-line bg-surface-raised py-1.5 pe-3.5 ps-1.5">
                    <span class="size-6 rounded-full bg-{{ $chart }}"></span>
                    <span class="font-mono text-xs text-ink-secondary">{{ $chart }}</span>
                </div>
            @endforeach
        </div>
    </x-docs.section>

    {{-- ────────────────────────────────────────────────────── Tipografi --}}
    <x-docs.section id="tipografi" number="03" eyebrow="Tipografi"
                    title="Sora untuk suara, Space Grotesk untuk kerja"
                    lead="Font sistem platform, tanpa satu pun font web. Font sistem sudah membawa optical sizing dan tabel tracking-nya sendiri; menggantinya berarti membuang semua itu lalu menirunya setengah jalan. Ukuran ditulis dalam rem supaya ikut setelan ukuran teks pengguna.">

        <div class="overflow-hidden rounded-lg border border-line bg-surface-raised">
            @foreach ($type as $row)
                <div class="flex flex-wrap items-baseline gap-x-5 gap-y-1 px-5 py-3.5 not-last:border-b not-last:border-line">
                    <span class="w-24 shrink-0 font-mono text-xs text-ink-muted">{{ $row['name'] }}</span>
                    <span class="num w-24 shrink-0 text-xs text-ink-quaternary">{{ $row['track'] }}</span>
                    <span class="num w-12 shrink-0 text-xs text-ink-quaternary">{{ $row['lead'] }}</span>
                    <span class="{{ $row['class'] }} max-w-[52ch]">{{ $row['sample'] }}</span>
                </div>
            @endforeach
        </div>

        <div class="mt-4 grid gap-3.5 sm:grid-cols-2">
            <div class="rounded-md border border-line bg-surface-raised p-4">
                <div class="mb-2.5 inline-flex items-center gap-1.5 text-xs font-semibold text-success">
                    <x-ui.icon name="check" class="size-3.5" />LAKUKAN
                </div>
                <p class="text-sm text-ink-secondary">
                    Angka di tabel dan metrik pakai mono dengan <code class="font-mono text-sm">tabular-nums</code>
                    supaya kolomnya lurus. Tersedia sebagai utility <code class="font-mono text-sm">num</code>.
                </p>
            </div>
            <div class="rounded-md border border-line bg-surface-raised p-4">
                <div class="mb-2.5 inline-flex items-center gap-1.5 text-xs font-semibold text-danger">
                    <x-ui.icon name="x" class="size-3.5" />HINDARI
                </div>
                <p class="text-sm text-ink-secondary">
                    Satu nilai tracking untuk semua ukuran. Teks besar butuh tracking negatif karena hurufnya
                    terbaca terlalu renggang saat membesar; teks kecil justru butuh sedikit positif agar terbaca.
                </p>
            </div>
        </div>
    </x-docs.section>

    {{-- ──────────────────────────────────────────────── Spacing & grid --}}
    <x-docs.section id="spacing" number="04" eyebrow="Spacing &amp; Grid"
                    title="Skala 4px, kepadatan sedang"
                    lead="Tinggi kontrol default 38px. Semua jarak kelipatan 4 — tidak ada 5px, 15px, atau 22px di mana pun.">

        <div class="flex flex-col gap-2.5 rounded-lg border border-line bg-surface-raised px-5 py-5">
            @foreach ($spacing as $step)
                <div class="flex items-center gap-4">
                    <span class="w-16 shrink-0 font-mono text-xs text-ink-muted">{{ $step['name'] }}</span>
                    <span class="w-12 shrink-0 font-mono text-xs">{{ $step['px'] }}</span>
                    <span class="h-3 {{ $step['w'] }} shrink-0 rounded-[3px] border border-accent bg-accent-soft"></span>
                    <span class="text-sm text-ink-muted">{{ $step['use'] }}</span>
                </div>
            @endforeach
        </div>

        <div class="mt-4 grid gap-3.5 sm:grid-cols-3">
            <div class="rounded-md border border-line bg-surface-raised p-4">
                <div class="eyebrow">Grid</div>
                <div class="mt-1.5 text-2xl font-semibold text-ink">12 kolom</div>
                <p class="mt-1.5 text-base text-ink-secondary">Gutter 24px untuk docs; shell aplikasi berpadding 16px tanpa batas lebar.</p>
            </div>
            <div class="rounded-md border border-line bg-surface-raised p-4">
                <div class="eyebrow">Breakpoint</div>
                <div class="mt-1.5 text-2xl font-semibold text-ink">640 · 1024 · 1440</div>
                <p class="mt-1.5 text-base text-ink-secondary">Sidebar runtuh jadi drawer di bawah 1024.</p>
            </div>
            <div class="rounded-md border border-line bg-surface-raised p-4">
                <div class="eyebrow">Target sentuh</div>
                <div class="mt-1.5 text-2xl font-semibold text-ink">44 × 44</div>
                <p class="mt-1.5 text-base text-ink-secondary">Minimum di mobile, walau visualnya lebih kecil.</p>
            </div>
        </div>
    </x-docs.section>

    {{-- ────────────────────────────────────────────── Permukaan & kaca --}}
    <x-docs.section id="permukaan" number="05" eyebrow="Permukaan, Radius &amp; Kaca"
                    title="Tiga tingkat elevasi, satu lapisan kaca"
                    lead="Radius lembut: 6px untuk elemen kecil, 8–10px untuk kontrol dan card, 14px untuk panel besar dan modal.">

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-md border border-line bg-surface-raised p-5 shadow-sm">
                <div class="font-mono text-xs text-ink-muted">shadow-sm</div>
                <div class="mt-1 font-semibold text-ink">Diam</div>
                <p class="mt-1 text-sm text-ink-secondary">Card di dalam halaman</p>
            </div>
            <div class="rounded-md border border-line bg-surface-raised p-5 shadow-md">
                <div class="font-mono text-xs text-ink-muted">shadow-md</div>
                <div class="mt-1 font-semibold text-ink">Mengambang</div>
                <p class="mt-1 text-sm text-ink-secondary">Dropdown, popover</p>
            </div>
            <div class="rounded-md border border-line bg-surface-raised p-5 shadow-lg">
                <div class="font-mono text-xs text-ink-muted">shadow-lg</div>
                <div class="mt-1 font-semibold text-ink">Terangkat</div>
                <p class="mt-1 text-sm text-ink-secondary">Modal, drawer</p>
            </div>
        </div>

        {{-- Material hanya terbaca sebagai material kalau ada sesuatu yang
             bergradasi di belakangnya untuk dibiaskan. --}}
        <div class="bg-shell relative mt-7 overflow-hidden rounded-xl border border-line p-9">

            <div class="relative grid gap-4 sm:grid-cols-3">
                <x-ui.stat label="MRR" value="Rp 412jt" delta="+12,4%" trend="up" />
                <x-ui.stat label="Retensi" value="94,2%" delta="Stabil" trend="flat" />
                <x-ui.stat label="Churn" value="1,8%" delta="+0,3%" trend="down" />
            </div>
        </div>

        <p class="measure mt-2.5 text-base text-ink-muted">
            Resepnya satu utility: <code class="font-mono text-sm">.material</code>, dengan tingkat dipilih lewat
            <code class="font-mono text-sm">data-mat</code>. Selalu di atas latar bergradasi — di atas warna rata,
            material cuma jadi kotak abu-abu.
        </p>

        <p class="mt-1.5 text-base text-ink-muted">
            Latar itu sendiri punya utility-nya: <code class="font-mono text-sm">.bg-shell</code> —
            semburat aksen di pojok kiri atas di atas permukaan rata, dipasang di
            <code class="font-mono text-sm">&lt;x-layouts.admin&gt;</code>, ditumpuk
            wash lembut yang sama dipakai halaman publik lewat <code class="font-mono text-sm">.bg-glow</code>.
        </p>
    </x-docs.section>

    {{-- ─────────────────────────────────────────────────────── Material --}}
    <x-docs.section id="material" number="06" eyebrow="Material &amp; Kedalaman"
                    title="Bobot material menandai hierarki"
                    lead="Lima tingkat, satu resep. Makin struktural sebuah wilayah, makin tebal materialnya — dan permukaan yang lebih besar memang terbaca lebih tebal. Tidak ada bevel, tidak ada emboss, tidak ada sumber cahaya yang dipalsukan.">

        <div class="bg-shell relative overflow-hidden rounded-xl border-[0.5px] border-line p-7">
            <div class="relative grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ([['ultrathin', 'Chip kecil yang mengambang'], ['thin', 'Dropdown, tooltip, popover, kartu metrik'], ['regular', 'Toolbar, toast'], ['thick', 'Modal, drawer, command palette'], ['chrome', 'Sidebar — wilayah struktural']] as $tier)
                    <div data-mat="{{ $tier[0] }}" class="material rounded-xl p-4">
                        <div class="font-mono text-xs text-ink-muted">{{ $tier[0] }}</div>
                        <p class="vibrant-secondary mt-1.5 text-sm">{{ $tier[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="measure mt-4 text-base text-ink-secondary">
            Tingkatnya dipilih lewat atribut <code class="font-mono text-sm">data-mat</code> di elemen yang memakai
            utility <code class="font-mono text-sm">.material</code>. Satu aturan menegakkan sisanya: material di
            dalam material otomatis jadi padat, karena menumpuk lapisan translusen terang di atas lapisan translusen
            terang membuat teks di atasnya berhenti terbaca.
        </p>

        <div class="mt-4 grid gap-3.5 sm:grid-cols-2">
            <div class="rounded-lg border-[0.5px] border-line bg-surface-raised p-4">
                <div class="mb-2.5 inline-flex items-center gap-1.5 text-xs font-semibold text-success">
                    <x-ui.icon name="check" class="size-3.5" />LAKUKAN
                </div>
                <ul class="flex flex-col gap-1.5 text-base text-ink-secondary">
                    <li>Redupkan dan dorong mundur latar untuk tugas yang memang memblokir.</li>
                    <li>Panel yang berjalan berdampingan cukup bergeser — tanpa peredup.</li>
                    <li>Pakai <code class="font-mono text-sm">.vibrant-secondary</code> untuk teks di atas material.</li>
                    <li>Ganti pembatas 1px di bawah chrome dengan tepi gulir yang memudar.</li>
                </ul>
            </div>
            <div class="rounded-lg border-[0.5px] border-line bg-surface-raised p-4">
                <div class="mb-2.5 inline-flex items-center gap-1.5 text-xs font-semibold text-danger">
                    <x-ui.icon name="x" class="size-3.5" />HINDARI
                </div>
                <ul class="flex flex-col gap-1.5 text-base text-ink-secondary">
                    <li>Material di atas material.</li>
                    <li>Tabel, form, dan teks panjang di atas lapisan translusen.</li>
                    <li><code class="font-mono text-sm">text-ink-muted</code> di dalam <code class="font-mono text-sm">[data-mat]</code>.</li>
                    <li>Kedalaman pada baris tabel — langsung menggagalkan AA.</li>
                </ul>
            </div>
        </div>
    </x-docs.section>

    {{-- ───────────────────────────────────────────────────────── Motion --}}
    <x-docs.section id="motion" number="07" eyebrow="Motion"
                    title="Bisa disela, bisa dibalik, mengikuti jari"
                    lead="Gerak di sini bukan animasi berdurasi tetap yang diputar sampai habis. Semuanya spring, dan spring bisa ditangkap di tengah terbang lalu diarahkan ulang dari posisi yang sedang terlihat — bukan dari awal.">

        <div class="overflow-hidden rounded-lg border-[0.5px] border-line bg-surface-raised">
            @foreach ($motion as $item)
                <div class="relative flex flex-wrap items-baseline gap-x-5 gap-y-1 px-4 py-3 not-first:before:absolute not-first:before:inset-x-4 not-first:before:top-0 not-first:before:h-px not-first:before:bg-line not-first:before:content-['']">
                    <span class="w-20 shrink-0 font-mono text-sm text-ink">{{ $item['name'] }}</span>
                    <span class="w-[190px] shrink-0 font-mono text-xs text-ink-muted">{{ $item['spec'] }}</span>
                    <span class="min-w-0 flex-1 text-base text-ink-secondary">{{ $item['use'] }}</span>
                </div>
            @endforeach
        </div>

        <p class="measure mt-4 text-base text-ink-secondary">
            Dua angkanya: <strong class="font-medium text-ink">damping</strong> menentukan seberapa jauh ia melewati
            target — 1,0 tidak memantul sama sekali — dan <strong class="font-medium text-ink">response</strong>
            seberapa cepat ia sampai, dalam detik. Bawaannya tidak memantul. Pantulan hanya dipakai kalau gerakannya
            memang didahului momentum: lemparan, sentakan, lepasan setelah diseret. Menu yang cuma muncul lalu
            memantul terasa salah; kartu yang dilempar lalu memantul terasa benar.
        </p>

        <div class="mt-4 grid gap-3.5 sm:grid-cols-2">
            <div class="rounded-lg border-[0.5px] border-line bg-surface-raised p-4">
                <div class="mb-2.5 inline-flex items-center gap-1.5 text-xs font-semibold text-success">
                    <x-ui.icon name="check" class="size-3.5" />LAKUKAN
                </div>
                <ul class="flex flex-col gap-1.5 text-base text-ink-secondary">
                    <li>Berangkat dari nilai yang sedang tampil, bukan dari nilai target.</li>
                    <li>Serahkan kecepatan jari saat dilepas ke spring yang menyusul.</li>
                    <li>Proyeksikan momentum untuk menentukan titik berhentinya.</li>
                    <li>Pisahkan sumbu X dan Y jadi dua spring sendiri-sendiri.</li>
                    <li>Putuskan komit dari tanda kecepatan, baru dari posisinya.</li>
                </ul>
            </div>
            <div class="rounded-lg border-[0.5px] border-line bg-surface-raised p-4">
                <div class="mb-2.5 inline-flex items-center gap-1.5 text-xs font-semibold text-danger">
                    <x-ui.icon name="x" class="size-3.5" />HINDARI
                </div>
                <ul class="flex flex-col gap-1.5 text-base text-ink-secondary">
                    <li>Transisi CSS berdurasi tetap pada apa pun yang bisa disentuh jari.</li>
                    <li>Mengunci input selama transisi berjalan.</li>
                    <li>Transisi yang cuma punya arah masuk.</li>
                    <li>Satu spring untuk jarak dua dimensi.</li>
                </ul>
            </div>
        </div>

        <h4 class="eyebrow mt-8 mb-3">Preferensi sistem</h4>

        <div class="overflow-hidden rounded-lg border-[0.5px] border-line bg-surface-raised">
            <div class="px-4 py-3">
                <code class="font-mono text-sm text-ink">prefers-reduced-motion</code>
                <p class="mt-1 text-base text-ink-secondary">
                    Yang hilang adalah <em>perpindahan posisi</em>, bukan umpan baliknya. Lapisan menyilang lewat
                    opacity alih-alih meluncur, tapi tombol tetap bereaksi saat ditekan, tuas tetap bisa diseret, dan
                    spinner tetap berputar — melambat, bukan berhenti. Mematikan semuanya justru membuat orang tidak
                    tahu apakah tekanannya terdaftar.
                </p>
            </div>
            <div class="relative px-4 py-3 before:absolute before:inset-x-4 before:top-0 before:h-px before:bg-line before:content-['']">
                <code class="font-mono text-sm text-ink">prefers-reduced-transparency</code>
                <p class="mt-1 text-base text-ink-secondary">
                    Semua tingkat material jadi permukaan padat dan blur-nya nol. Kedalamannya tetap disampaikan
                    bayangan dan urutan lapisan.
                </p>
            </div>
            <div class="relative px-4 py-3 before:absolute before:inset-x-4 before:top-0 before:h-px before:bg-line before:content-['']">
                <code class="font-mono text-sm text-ink">prefers-contrast: more</code>
                <p class="mt-1 text-base text-ink-secondary">
                    Separator jadi jauh lebih gelap, label lebih pekat, material padat, dan tiap kontrol mendapat
                    garis luar yang kontras.
                </p>
            </div>
        </div>
    </x-docs.section>

    {{-- ───────────────────────────────────────────────────────── Gesture --}}
    <x-docs.section id="gesture" number="08" eyebrow="Gesture"
                    title="Empat hal kecil yang harus benar semua"
                    lead="Yang membuat seretan terasa langsung bukan cuma elemennya ikut jari.">

        <div class="overflow-hidden rounded-lg border-[0.5px] border-line bg-surface-raised">
            @foreach ($gestures as $rule)
                <div class="relative px-4 py-3 not-first:before:absolute not-first:before:inset-x-4 not-first:before:top-0 not-first:before:h-px not-first:before:bg-line not-first:before:content-['']">
                    <div class="text-base font-medium text-ink">{{ $rule['title'] }}</div>
                    <p class="mt-1 text-base text-ink-secondary">{{ $rule['body'] }}</p>
                </div>
            @endforeach
        </div>

        <p class="measure mt-4 text-base text-ink-secondary">
            Perlawanan di luar batas naik bertahap, tidak berhenti mendadak: berhenti mendadak terbaca sebagai macet,
            sedangkan perlawanan yang terus bertambah terbaca sebagai masih merespons, tapi memang tidak ada lagi di
            sana. Titik tempelnya dipilih dari tempat gerakan itu <em>menuju</em> kalau dibiarkan, bukan dari tempat
            jari kebetulan berhenti — itulah yang membuat sentakan kecil terasa benar-benar melempar.
        </p>
    </x-docs.section>

    {{-- ─────────────────────────────────────────────────────────── Ikon --}}
    <x-docs.section id="ikon" number="09" eyebrow="Ikon"
                    title="Lucide, stroke 1.5, ukuran 16 / 20 / 24"
                    lead="Satu set saja, dirender sebagai SVG inline tanpa JavaScript. Ikon selalu berdampingan dengan teks, kecuali ikon-tombol yang punya tooltip. Warnanya mengikuti teks di sekitarnya, bukan aksen.">

        <div class="grid grid-cols-3 gap-0.5 rounded-lg border border-line bg-surface-raised p-2 sm:grid-cols-6 lg:grid-cols-8">
            @foreach ($icons as $icon)
                <div class="flex flex-col items-center gap-2 rounded-sm px-1.5 py-3.5 transition hover:bg-surface-inset">
                    <x-ui.icon :name="$icon" class="size-5 text-ink-secondary" />
                    <span class="max-w-full truncate font-mono text-2xs text-ink-muted">{{ $icon }}</span>
                </div>
            @endforeach
        </div>

        <x-docs.example class="mt-4" :code="$iconCode">
            <x-ui.icon name="trash-2" class="size-4" />
            <x-ui.icon name="trash-2" class="size-5" />
            <x-ui.icon name="trash-2" class="size-6" />
        </x-docs.example>
    </x-docs.section>
</x-layouts.docs>
