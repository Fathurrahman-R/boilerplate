<x-docs.screen :screen="$screen" :meta="$meta" url="app.contoh.id/masuk" texture>
    {{-- Split: form solid di kiri, panel kaca di kanan — komponen sungguhan
         yang dipakai `auth/login.blade.php` dan `auth/register.blade.php`,
         bukan tiruan. Panel kanan hanya untuk layar auth utama; flow pendek
         (2FA, reset kata sandi) tetap kartu tunggal supaya tidak ada
         gangguan di tengah langkah keamanan. --}}
    <div class="grid grid-cols-1 lg:min-h-[600px] lg:grid-cols-2">
        <div class="flex flex-col justify-center bg-surface-raised px-6 py-12 sm:px-12 lg:py-14">
            <div class="mx-auto w-full max-w-[360px]">
                <div class="mb-9 flex items-center gap-2.5">
                    <span class="flex size-[26px] items-center justify-center rounded-sm bg-accent font-display text-sm font-bold text-accent-on">N</span>
                    <span class="font-display text-base font-semibold tracking-tight text-ink">Nusatagih</span>
                </div>

                <h1 class="font-display text-[28px] font-semibold text-ink">Masuk ke workspace</h1>
                <p class="mt-2 text-sm text-ink-secondary">Pakai akun kerja Anda untuk melanjutkan.</p>

                <div class="mt-7 space-y-4">
                    <x-ui.input name="ds_auth_email" type="email" label="Email kerja" value="finance@nusantara.co.id" required />

                    <div>
                        <div class="mb-1.5 flex items-baseline justify-between gap-3">
                            <span class="text-[13px] font-semibold text-ink">Kata sandi</span>
                            <span class="text-[13px] text-link">Lupa kata sandi?</span>
                        </div>
                        <input type="password" value="tidakbenar" aria-label="Kata sandi"
                               class="block h-control w-full rounded-md border border-danger bg-surface-sunken px-3 text-sm text-ink shadow-well outline-none">
                        {{-- Error menyebutkan cara membetulkannya, bukan sekadar
                             mengatakan bahwa ada yang salah. --}}
                        <p class="mt-1.5 flex items-center gap-1.5 text-[12.5px] text-danger">
                            <x-ui.icon name="circle-alert" class="size-3.5 shrink-0" />
                            Kata sandi tidak cocok. Tersisa 4 percobaan sebelum akun dikunci 15 menit.
                        </p>
                    </div>

                    <x-ui.checkbox name="ds_auth_remember" label="Ingat perangkat ini selama 30 hari" checked />

                    <x-ui.button type="button" block size="lg">Masuk</x-ui.button>
                </div>

                <p class="mt-6 text-sm text-ink-muted">
                    Belum punya akun?
                    <span class="font-medium text-link">Daftar</span>
                </p>
            </div>
        </div>

        <div class="relative hidden flex-col justify-end overflow-hidden p-9 lg:flex"
             style="background-image: radial-gradient(90% 90% at 70% 10%, var(--accent-soft) 0%, transparent 55%), var(--mat-base)">
            <div class="bg-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>

            <x-auth.trust-panel
                quote="Penutupan buku yang dulu tiga hari sekarang selesai sebelum makan siang."
                name="Maya Wardhani" role="Finance Lead · Nusantara Logistik" initials="MW"
                :stats="[
                    ['value' => '2.400+', 'label' => 'tim keuangan'],
                    ['value' => 'Rp 4,1T', 'label' => 'tagihan diproses'],
                    ['value' => '99,9%', 'label' => 'uptime'],
                ]" />
        </div>
    </div>

    <div class="border-t border-line bg-surface-raised px-9 py-5">
        <div class="flex items-start gap-3">
            <x-ui.icon name="shield-check" class="mt-0.5 size-4 shrink-0 text-ink-muted" />
            <p class="max-w-[70ch] text-[13px] text-ink-secondary">
                Verifikasi dua langkah aktif untuk workspace ini. Setelah kata sandi benar, pengguna diminta kode
                enam digit dari aplikasi autentikator — komponen <code class="font-mono text-[12.5px]">x-layouts.guest</code>
                yang sama dipakai untuk layar itu.
            </p>
        </div>
    </div>
</x-docs.screen>
