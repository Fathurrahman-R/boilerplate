<x-layouts.guest-split heading="Buat akun baru"
                       description="Mulai kelola workspace tim Anda dalam beberapa menit.">
    <x-slot:aside>
        <x-auth.trust-panel
            quote="Penutupan buku yang dulu tiga hari sekarang selesai sebelum makan siang."
            name="Maya Wardhani" role="Finance Lead · Nusantara Logistik" initials="MW"
            :stats="[
                ['value' => '2.400+', 'label' => 'tim keuangan'],
                ['value' => 'Rp 4,1T', 'label' => 'tagihan diproses'],
                ['value' => '99,9%', 'label' => 'uptime'],
            ]" />
    </x-slot:aside>

    <x-auth.errors />

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <x-ui.input name="name" label="Nama lengkap" required autofocus autocomplete="name" />

        <x-ui.input name="email" type="email" label="Email kerja" placeholder="nama@perusahaan.com" required autocomplete="username" />

        <x-ui.input name="password" type="password" label="Kata sandi" placeholder="••••••••" required autocomplete="new-password"
                    hint="Minimal 8 karakter." />

        <x-ui.input name="password_confirmation" type="password" label="Ulangi kata sandi" placeholder="••••••••" required autocomplete="new-password" />

        <x-ui.button type="submit" block>Daftar</x-ui.button>

        <p class="text-sm text-ink-muted">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-link hover:underline">Masuk</a>
        </p>
    </form>
</x-layouts.guest-split>
