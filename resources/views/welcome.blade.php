@php
    $registrationEnabled = Route::has('register');
    $docsEnabled = (bool) config('design-system.enabled');

    $features = [
        [
            'icon' => 'key',
            'title' => 'Resource key, bukan nama permission',
            'body' => 'Kode memakai <code>laporan.export</code>. Permission di baliknya ditentukan lewat tabel pemetaan di database — bisa diganti dari panel tanpa deploy.',
        ],
        [
            'icon' => 'shield-check',
            'title' => 'Tertutup kalau ragu',
            'body' => 'Key yang tidak dikenal atau belum dipetakan selalu ditolak, dan dicatat di log. Tidak ada celah yang terbuka diam-diam karena salah ketik.',
        ],
        [
            'icon' => 'lock',
            'title' => 'Auth lengkap sejak menit pertama',
            'body' => 'Login, registrasi, reset kata sandi, verifikasi email, konfirmasi kata sandi, dan dua langkah — seluruh tampilannya milik Anda, bukan milik paket.',
        ],
        [
            'icon' => 'table-2',
            'title' => 'Tabel yang sudah tahu tugasnya',
            'body' => 'Pencarian, pengurutan berdaftar-putih, filter, paginasi, dan ekspor CSV dalam satu pembangun query. Kolom di luar daftar diabaikan, bukan diteruskan ke query.',
        ],
        [
            'icon' => 'swatch-book',
            'title' => 'Satu sistem visual',
            'body' => 'Token semantik untuk terang dan gelap, komponen Blade yang membaca <code>$errors</code> sendiri, dan dokumentasi hidup di <code>/design-system</code>.',
        ],
        [
            'icon' => 'terminal',
            'title' => 'Perintah untuk mengaudit',
            'body' => '<code>resource:doctor</code> menunjukkan key tanpa permission, permission tanpa key, dan permission yang tidak dipakai role mana pun.',
        ],
    ];

    $plans = [
        [
            'name' => 'Sendiri',
            'price' => 'Rp 0',
            'note' => 'Selamanya',
            'body' => 'Untuk satu project dan satu orang.',
            'items' => ['Seluruh modul admin', 'RBAC resource key', 'Pustaka komponen', 'Dokumentasi design system'],
            'featured' => false,
        ],
        [
            'name' => 'Tim',
            'price' => 'Rp 490rb',
            'note' => 'Sekali bayar',
            'body' => 'Untuk agensi yang memulai project baru tiap bulan.',
            'items' => ['Semua di paket Sendiri', 'Project tanpa batas', 'Modul contoh tambahan', 'Prioritas pertanyaan'],
            'featured' => true,
        ],
        [
            'name' => 'Perusahaan',
            'price' => 'Hubungi kami',
            'note' => 'Per kesepakatan',
            'body' => 'Untuk tim internal dengan aturan sendiri.',
            'items' => ['Semua di paket Tim', 'Penyesuaian tema', 'Pendampingan migrasi', 'Peninjauan arsitektur'],
            'featured' => false,
        ],
    ];
@endphp

{{-- Tanpa `title`: layout dasar sudah memakai nama aplikasi apa adanya, dan
     halaman depan tidak perlu awalan tambahan. --}}
<x-layouts.base>

    {{-- ───────────────────────────────────────────────────────────── Hero --}}
    <header class="bg-glow relative">
        <div class="mx-auto max-w-[1120px] px-6 pt-5">
            {{-- Nav berbentuk pil yang mengambang: satu-satunya lapisan kaca di
                 bagian atas halaman. --}}
            <nav class="glass flex h-14 items-center gap-5 rounded-full ps-5 pe-2.5">
                <a href="{{ url('/') }}" class="flex shrink-0 items-center gap-2.5">
                    <span class="flex size-[22px] items-center justify-center rounded-sm bg-accent font-display text-xs font-bold text-accent-on">
                        {{ mb_substr(config('app.name'), 0, 1) }}
                    </span>
                    <span class="font-display text-[15px] font-semibold tracking-tight text-ink">{{ config('app.name') }}</span>
                </a>

                <div class="hidden flex-1 gap-1 md:flex">
                    <a href="#fitur" class="rounded-full px-3 py-1.5 text-[13.5px] text-ink-secondary transition hover:bg-surface-inset hover:text-ink">Fitur</a>
                    <a href="#harga" class="rounded-full px-3 py-1.5 text-[13.5px] text-ink-secondary transition hover:bg-surface-inset hover:text-ink">Harga</a>
                    @if ($docsEnabled)
                        <a href="{{ route('design-system.foundation') }}" class="rounded-full px-3 py-1.5 text-[13.5px] text-ink-secondary transition hover:bg-surface-inset hover:text-ink">Design system</a>
                    @endif
                </div>

                <div class="ms-auto flex items-center gap-2 md:ms-0">
                    <button type="button" data-theme-toggle
                            class="inline-flex size-9 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-inset hover:text-ink focus-visible:outline-none focus-visible:ring-3 focus-visible:ring-accent-soft">
                        <span class="sr-only">Ganti tema</span>
                        <x-ui.icon name="sun-moon" class="size-[18px]" />
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="flex h-9 items-center rounded-full bg-[image:var(--mat-accent)] px-4 text-[13.5px] font-semibold text-accent-on shadow-[var(--bevel),var(--lift)] transition hover:brightness-95">
                            Buka dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-2 text-[13.5px] text-ink-secondary transition hover:text-ink">Masuk</a>

                        @if ($registrationEnabled)
                            <a href="{{ route('register') }}"
                               class="flex h-9 items-center rounded-full bg-[image:var(--mat-accent)] px-4 text-[13.5px] font-semibold text-accent-on shadow-[var(--bevel),var(--lift)] transition hover:brightness-95">
                                Mulai
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>

            <div class="mx-auto max-w-[760px] pt-20 text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-line bg-surface-raised px-3.5 py-1.5 text-[12.5px] text-ink-secondary">
                    <span class="size-1.5 rounded-full bg-success"></span>
                    Laravel {{ Illuminate\Foundation\Application::VERSION }} · PHP {{ PHP_MAJOR_VERSION }}.{{ PHP_MINOR_VERSION }}
                </span>

                <h1 class="mt-6 font-display text-[40px] leading-[1.05] font-semibold tracking-[-0.035em] text-ink sm:text-[56px]">
                    Hak akses yang berubah lewat panel, bukan lewat deploy.
                </h1>

                <p class="mx-auto mt-5 max-w-[56ch] text-[17px] text-ink-secondary sm:text-[18px]">
                    Boilerplate Laravel dengan autentikasi lengkap dan RBAC yang kodenya tidak pernah menyebut nama
                    permission. Kode memakai resource key; permission di baliknya diatur dari database.
                </p>

                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="{{ $registrationEnabled ? route('register') : route('login') }}"
                       class="flex h-12 items-center gap-2 rounded-md bg-[image:var(--mat-accent)] px-6 text-[15px] font-semibold text-accent-on shadow-md transition hover:brightness-95">
                        {{ $registrationEnabled ? 'Buat akun' : 'Masuk ke panel' }}
                        <x-ui.icon name="arrow-up-right" class="size-[17px]" />
                    </a>

                    @if ($docsEnabled)
                        <a href="{{ route('design-system.screen', 'dashboard') }}"
                           class="flex h-12 items-center gap-2 rounded-md border border-line-strong bg-[image:var(--mat-raised)] px-5 text-[15px] font-medium text-ink shadow-[var(--bevel),var(--lift)] transition hover:brightness-95">
                            <x-ui.icon name="play" class="size-4" />
                            Lihat layar contoh
                        </a>
                    @endif
                </div>

                <p class="mt-4 text-[12.5px] text-ink-muted">Tanpa pustaka UI pihak ketiga · Tanpa font dari CDN · Terang dan gelap setara</p>
            </div>

            {{-- Bingkai pratinjau: kaca membungkus permukaan solid, bukan
                 sebaliknya. --}}
            <div class="glass mt-14 rounded-xl p-3.5">
                <div class="overflow-hidden rounded-lg border border-line bg-surface-raised">
                    <div class="flex h-9 items-center gap-2 border-b border-line bg-surface-sunken px-3.5">
                        <span class="size-[9px] rounded-full bg-line-strong"></span>
                        <span class="size-[9px] rounded-full bg-line-strong"></span>
                        <span class="size-[9px] rounded-full bg-line-strong"></span>
                        <span class="ms-3 font-mono text-[11.5px] text-ink-muted">{{ parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost' }}/admin/resources</span>
                    </div>

                    <div class="grid gap-px bg-line sm:grid-cols-3">
                        <div class="bg-surface-raised p-5">
                            <span class="eyebrow">Resource key</span>
                            <div class="mt-3 flex flex-col items-start gap-1.5">
                                <code class="rounded-sm bg-code px-2 py-1 font-mono text-xs text-code-ink">laporan.view</code>
                                <code class="rounded-sm bg-code px-2 py-1 font-mono text-xs text-code-ink">laporan.export</code>
                                <code class="rounded-sm bg-code px-2 py-1 font-mono text-xs text-code-ink">laporan.approve</code>
                            </div>
                        </div>

                        <div class="bg-surface-raised p-5">
                            <span class="eyebrow">Pemetaan</span>
                            <p class="mt-3 text-[13.5px] text-ink-secondary">
                                Tiap key menunjuk tepat satu permission. Arahkan ulang dari panel dan hasilnya berlaku
                                seketika, tanpa satu baris kode pun berubah.
                            </p>
                        </div>

                        <div class="bg-surface-raised p-5">
                            <span class="eyebrow">Permission</span>
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                <x-ui.badge variant="primary" pill>akses-laporan</x-ui.badge>
                                <x-ui.badge variant="neutral" pill>content-manage</x-ui.badge>
                                <x-ui.badge variant="danger" pill dot>belum dipetakan</x-ui.badge>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ──────────────────────────────────────────────────────────── Fitur --}}
    <section id="fitur" class="mt-24 border-t border-line bg-surface-raised px-6 py-20">
        <div class="mx-auto max-w-[1120px]">
            <h2 class="max-w-[20ch] font-display text-[30px] leading-tight font-semibold tracking-[-0.03em] text-ink sm:text-[36px]">
                Yang biasanya dibangun ulang tiap project, sudah selesai di sini.
            </h2>

            {{-- Grid hairline: garisnya adalah latar yang tersisa di antara sel,
                 bukan border yang digambar di tiap kartu. --}}
            <div class="mt-10 grid gap-px overflow-hidden rounded-lg border border-line bg-line sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($features as $feature)
                    <div class="bg-surface-raised p-6">
                        <x-ui.icon :name="$feature['icon']" class="size-5 text-accent" />
                        <h3 class="mt-3.5 font-display text-[17px] font-semibold text-ink">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink-secondary [&_code]:rounded-sm [&_code]:bg-code [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:font-mono [&_code]:text-xs [&_code]:text-code-ink">
                            {!! $feature['body'] !!}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ──────────────────────────────────────────────────────────── Harga --}}
    <section id="harga" class="border-t border-line px-6 py-20">
        <div class="mx-auto max-w-[1120px]">
            <h2 class="font-display text-[30px] leading-tight font-semibold tracking-[-0.03em] text-ink sm:text-[36px]">Harga</h2>
            <p class="mt-3 max-w-[56ch] text-[15px] text-ink-secondary">
                Sekali bayar, tanpa langganan. Angka di bawah ini isi contoh — ganti dengan harga Anda sendiri.
            </p>

            <div class="mt-10 grid gap-4 lg:grid-cols-3">
                @foreach ($plans as $plan)
                    <div @class([
                        'flex flex-col rounded-lg border bg-surface-raised p-6',
                        'border-accent shadow-md' => $plan['featured'],
                        'border-line shadow-sm' => ! $plan['featured'],
                    ])>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-display text-[17px] font-semibold text-ink">{{ $plan['name'] }}</h3>
                            @if ($plan['featured'])
                                <x-ui.badge variant="primary" pill>Paling sering dipakai</x-ui.badge>
                            @endif
                        </div>

                        <p class="mt-1.5 text-sm text-ink-secondary">{{ $plan['body'] }}</p>

                        <div class="mt-5 flex items-baseline gap-2">
                            <span class="font-display text-[32px] font-semibold tabular-nums text-ink">{{ $plan['price'] }}</span>
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

                        <x-ui.button :href="$registrationEnabled ? route('register') : route('login')"
                                     :variant="$plan['featured'] ? 'primary' : 'secondary'"
                                     block class="mt-6">
                            Mulai dengan {{ $plan['name'] }}
                        </x-ui.button>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ────────────────────────────────────────────────────────── Penutup --}}
    <section class="border-t border-line bg-surface-raised px-6 py-20">
        <div class="mx-auto max-w-[1120px] text-center">
            <h2 class="mx-auto max-w-[24ch] font-display text-[28px] leading-tight font-semibold tracking-[-0.03em] text-ink sm:text-[34px]">
                Mulai project berikutnya dari menit ke-lima belas, bukan dari nol.
            </h2>

            <div class="mt-7 flex flex-wrap justify-center gap-3">
                <x-ui.button :href="$registrationEnabled ? route('register') : route('login')" size="lg">
                    {{ $registrationEnabled ? 'Buat akun' : 'Masuk ke panel' }}
                </x-ui.button>

                @if ($docsEnabled)
                    <x-ui.button :href="route('design-system.foundation')" variant="secondary" size="lg">
                        Baca design system
                    </x-ui.button>
                @endif
            </div>
        </div>
    </section>

    <footer class="border-t border-line px-6 py-8">
        <div class="mx-auto flex max-w-[1120px] flex-wrap items-center justify-between gap-4 text-[13px] text-ink-muted">
            <span>{{ config('app.name') }} · boilerplate Laravel</span>
            <span class="font-mono text-xs">auth · rbac resource key · design system</span>
        </div>
    </footer>
</x-layouts.base>
