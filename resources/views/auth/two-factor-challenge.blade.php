<x-layouts.guest heading="Verifikasi dua langkah">
    <x-auth.errors />

    {{-- Dua form terpisah: kode dari aplikasi autentikator, atau kode pemulihan
         kalau perangkatnya hilang. Keduanya menuju route yang sama. --}}

    <div x-data="{ recovery: false }" class="space-y-4">
        <p class="text-sm text-ink-muted" x-show="! recovery">
            Masukkan kode 6 digit dari aplikasi autentikator Anda.
        </p>

        <p class="text-sm text-ink-muted" x-show="recovery" x-cloak>
            Masukkan salah satu kode pemulihan yang Anda simpan saat mengaktifkan verifikasi dua langkah.
        </p>

        <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
            @csrf

            <div x-show="! recovery">
                <x-ui.input name="code" label="Kode autentikasi" inputmode="numeric" autocomplete="one-time-code" autofocus />
            </div>

            <div x-show="recovery" x-cloak>
                <x-ui.input name="recovery_code" label="Kode pemulihan" autocomplete="one-time-code" />
            </div>

            <x-ui.button type="submit" block>Verifikasi</x-ui.button>
        </form>

        <button type="button" class="text-sm font-medium text-link hover:underline"
                x-on:click="recovery = ! recovery">
            <span x-show="! recovery">Gunakan kode pemulihan</span>
            <span x-show="recovery" x-cloak>Gunakan kode autentikator</span>
        </button>

        <div class="flex items-start gap-3 rounded-lg border border-line bg-surface-sunken p-4">
            <x-ui.icon name="shield-check" class="mt-0.5 size-4 shrink-0 text-ink-muted" />
            <p class="text-[13px] text-ink-secondary">
                Jangan bagikan kode ini ke siapa pun, termasuk yang mengaku dari tim kami. Kode kedaluwarsa
                dalam waktu singkat dan hanya berlaku sekali pakai.
            </p>
        </div>
    </div>
</x-layouts.guest>
