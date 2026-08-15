<x-layouts.guest heading="Verifikasi dua langkah">
    <x-auth.errors />

    {{-- Dua form terpisah: kode dari aplikasi autentikator, atau kode pemulihan
         kalau perangkatnya hilang. Keduanya menuju route yang sama. --}}

    <div x-data="{ recovery: false }" class="space-y-4">
        <p class="text-sm text-gray-500 dark:text-gray-400" x-show="! recovery">
            Masukkan kode 6 digit dari aplikasi autentikator Anda.
        </p>

        <p class="text-sm text-gray-500 dark:text-gray-400" x-show="recovery" x-cloak>
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

        <button type="button" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500"
                x-on:click="recovery = ! recovery">
            <span x-show="! recovery">Gunakan kode pemulihan</span>
            <span x-show="recovery" x-cloak>Gunakan kode autentikator</span>
        </button>
    </div>
</x-layouts.guest>
