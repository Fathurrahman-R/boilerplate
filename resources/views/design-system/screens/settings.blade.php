@php
    $sections = [
        ['label' => 'Umum', 'icon' => 'settings', 'active' => true],
        ['label' => 'Penagihan', 'icon' => 'credit-card', 'active' => false],
        ['label' => 'Anggota tim', 'icon' => 'users', 'active' => false],
        ['label' => 'Notifikasi', 'icon' => 'bell', 'active' => false],
        ['label' => 'Keamanan', 'icon' => 'shield-check', 'active' => false],
        ['label' => 'Integrasi', 'icon' => 'plug', 'active' => false],
    ];
@endphp

<x-docs.screen :screen="$screen" :meta="$meta" url="app.contoh.id/pengaturan" texture backdrop="shell">
    <div class="flex min-h-[720px] flex-col lg:flex-row">

        {{-- Navigasi pengaturan: daftar datar, bukan tab bertingkat --}}
        <aside class="w-full shrink-0 border-b border-line bg-surface-sunken p-3 lg:w-56 lg:border-e lg:border-b-0">
            <span class="eyebrow block px-2.5 pb-2">Pengaturan</span>

            <div class="flex gap-0.5 overflow-x-auto lg:flex-col lg:overflow-visible">
                @foreach ($sections as $section)
                    <span @class([
                        'flex shrink-0 items-center gap-2.5 rounded-md px-2.5 py-2 text-sm whitespace-nowrap',
                        'bg-surface-inset font-semibold text-ink' => $section['active'],
                        'text-ink-secondary' => ! $section['active'],
                    ])>
                        <x-ui.icon :name="$section['icon']" class="size-[17px] shrink-0" />
                        {{ $section['label'] }}
                    </span>
                @endforeach
            </div>
        </aside>

        <div class="min-w-0 flex-1 p-6">
            <div class="mx-auto max-w-[720px] space-y-6">
                <div>
                    <h1 class="font-display text-[26px] font-semibold text-ink">Umum</h1>
                    <p class="mt-1 text-sm text-ink-secondary">Identitas workspace dan bagaimana ia muncul di faktur.</p>
                </div>

                <x-ui.alert variant="info" title="Perubahan berlaku untuk seluruh tim">
                    Delapan anggota memakai workspace ini. Nama dan logo baru muncul di faktur berikutnya.
                </x-ui.alert>

                <x-ui.card title="Identitas">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-ui.input name="ds_ws_name" label="Nama perusahaan" value="PT Nusantara Jaya"
                                        hint="Muncul di faktur, email, dan halaman pembayaran." />
                        </div>

                        <x-ui.input name="ds_ws_npwp" label="NPWP" value="01.234.567.8-901.000" />

                        <x-ui.select name="ds_ws_currency" label="Mata uang"
                                     :options="['idr' => 'Rupiah (IDR)', 'usd' => 'Dolar AS (USD)', 'sgd' => 'Dolar Singapura (SGD)']"
                                     selected="idr" />

                        <div class="sm:col-span-2">
                            <x-ui.file-upload name="ds_ws_logo" label="Logo" accept="image/*"
                                              hint="PNG atau SVG, sisi terpanjang minimal 512px." />
                        </div>

                        <div class="sm:col-span-2">
                            <x-ui.textarea name="ds_ws_address" label="Alamat penagihan" rows="3"
                                           value="Jl. Melati Raya No. 18, Kebayoran Baru, Jakarta Selatan 12160" />
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card title="Perilaku penagihan">
                    <div class="space-y-5">
                        <x-ui.toggle name="ds_auto_invoice" label="Terbitkan faktur berulang otomatis" checked
                                     hint="Faktur terbit tiap tanggal 1 tanpa persetujuan tambahan." />

                        <x-ui.toggle name="ds_auto_remind" label="Kirim pengingat jatuh tempo" checked
                                     hint="Tiga hari sebelum, hari-H, lalu tiap minggu sampai lunas." />

                        <x-ui.toggle name="ds_sandbox" label="Mode sandbox"
                                     hint="Faktur tetap dibuat tapi tidak pernah terkirim ke klien." />

                        <div class="border-t border-line pt-5">
                            <x-ui.stepper name="ds_grace" label="Tenggang sebelum ditandai jatuh tempo"
                                          :value="7" :min="0" :max="60"
                                          hint="Dalam hari, dihitung sejak tanggal jatuh tempo." />
                        </div>

                        <div>
                            <span class="mb-1.5 block text-[13px] font-semibold text-ink">Nilai yang butuh persetujuan</span>
                            <x-ui.segmented :options="['5' => 'Di atas 5jt', '25' => 'Di atas 25jt', 'off' => 'Tidak pernah']" selected="25" />
                        </div>
                    </div>

                    <x-slot:footer>
                        <div class="flex flex-wrap items-center justify-end gap-2.5">
                            <x-ui.button type="button" variant="ghost">Batalkan perubahan</x-ui.button>
                            <x-ui.button type="button">Simpan perubahan</x-ui.button>
                        </div>
                    </x-slot:footer>
                </x-ui.card>

                {{-- Aksi berisiko dipisah ke kartunya sendiri, di paling bawah,
                     dan tidak pernah bersebelahan dengan tombol simpan. --}}
                <x-ui.card>
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h2 class="font-display text-base font-semibold text-ink">Tutup workspace</h2>
                            <p class="mt-1 max-w-[52ch] text-sm text-ink-secondary">
                                Faktur yang sudah terkirim tetap bisa dibuka klien selama 90 hari. Setelah itu seluruh
                                data dihapus dan tidak bisa dipulihkan.
                            </p>
                        </div>

                        <x-ui.button type="button" variant="danger" x-on:click="$dispatch('modal-open', 'ds-close-workspace')">
                            Tutup workspace
                        </x-ui.button>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>

    <x-ui.modal id="ds-close-workspace" title="Tutup workspace ini?" size="sm">
        <p>Delapan anggota kehilangan akses seketika. Data dihapus permanen setelah 90 hari.</p>

        <x-ui.input name="ds_confirm" label="Ketik nama workspace untuk menegaskan" placeholder="PT Nusantara Jaya" />

        <x-slot:footer>
            <x-ui.button type="button" variant="secondary" x-on:click="$dispatch('modal-close', 'ds-close-workspace')">Batal</x-ui.button>
            <x-ui.button type="button" variant="danger" x-on:click="$dispatch('modal-close', 'ds-close-workspace')">Tutup workspace</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</x-docs.screen>
