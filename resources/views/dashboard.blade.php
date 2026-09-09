<x-layouts.admin heading="Dashboard" description="Ringkasan singkat isi aplikasi.">
    <div class="flex flex-col gap-4 pb-10">

        @if ($unmappedCount > 0)
            <x-ui.alert variant="warning" title="Ada resource key yang belum dipetakan">
                {{ $unmappedCount }} key belum menunjuk permission mana pun, jadi aksesnya tertutup untuk semua orang
                kecuali super admin.
                <a href="{{ route('admin.mappings.index', ['status' => 'unmapped']) }}" class="font-medium text-link underline-offset-2 hover:underline">
                    Lihat daftarnya
                </a>
            </x-ui.alert>
        @endif

        {{--
            Metrik pertama dibuat lebih besar dari sisanya. Empat kartu
            seukuran berjajar tidak menyampaikan apa pun tentang mana yang
            lebih penting, dan kalau semuanya sama penting, tidak ada yang
            penting.
        --}}
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $index => $stat)
                <x-ui.stat :label="$stat['label']"
                           :value="number_format($stat['value'], 0, ',', '.')"
                           :icon="$stat['icon']"
                           @class(['sm:col-span-2 xl:col-span-2' => $index === 0]) />
            @endforeach
        </div>

        <div class="grid gap-3 lg:grid-cols-[minmax(0,1.7fr)_minmax(0,1fr)]">
            <x-ui.card title="Pengguna baru" subtitle="6 bulan terakhir">
                <x-ui.bar-chart :series="$signups" :tones="['chart-1']" :height="200" />
            </x-ui.card>

            {{-- Aktivitas adalah daftar, jadi bentuknya daftar: satu kolom
                 baris bertumpuk dengan pemisah menjorok, bukan kartu berisi
                 daftar di dalam kartu. --}}
            <x-ui.list title="Aktivitas terbaru">
                @forelse ($activity as $item)
                    <x-ui.list-row :label="$item['text']" :sublabel="$item['time'] ?? null">
                        <x-slot:leading>
                            <span @class([
                                'mt-1 size-2 shrink-0 rounded-full',
                                'bg-accent' => $loop->first,
                                'bg-fill-1' => ! $loop->first,
                            ])></span>
                        </x-slot:leading>
                    </x-ui.list-row>
                @empty
                    <x-ui.list-row>
                        <span class="text-base text-ink-muted">Belum ada aktivitas.</span>
                    </x-ui.list-row>
                @endforelse
            </x-ui.list>
        </div>

        {{--
            Panduan awal hanya muncul selama masih ada yang perlu disiapkan.
            Petunjuk yang menetap setelah tugasnya selesai berhenti dibaca dan
            berubah jadi hiasan yang memakan ruang di layar terpenting.
        --}}
        @if ($unmappedCount > 0)
            <x-ui.list title="Mulai dari mana"
                       hint="Urutannya berarti: resource dulu, baru pemetaannya, baru pembagiannya ke role."
                       class="measure">
                <x-ui.list-row label="Buat resource untuk tiap modul"
                               sublabel="Sistem otomatis membuatkan permission untuk tiap aksi yang dicentang."
                               :href="route('admin.resources.create')">
                    <x-slot:leading>
                        <span class="num flex size-6 shrink-0 items-center justify-center rounded-full bg-fill-3 text-xs text-ink-secondary">01</span>
                    </x-slot:leading>
                </x-ui.list-row>

                <x-ui.list-row label="Arahkan tiap key ke permission-nya"
                               sublabel="Bisa diganti dari panel, tanpa menyentuh kode."
                               :href="route('admin.mappings.index')">
                    <x-slot:leading>
                        <span class="num flex size-6 shrink-0 items-center justify-center rounded-full bg-fill-3 text-xs text-ink-secondary">02</span>
                    </x-slot:leading>
                </x-ui.list-row>

                <x-ui.list-row label="Bagikan permission ke role"
                               sublabel="Lalu tugaskan role itu ke pengguna."
                               :href="route('admin.roles.index')">
                    <x-slot:leading>
                        <span class="num flex size-6 shrink-0 items-center justify-center rounded-full bg-fill-3 text-xs text-ink-secondary">03</span>
                    </x-slot:leading>
                </x-ui.list-row>
            </x-ui.list>
        @endif
    </div>
</x-layouts.admin>
