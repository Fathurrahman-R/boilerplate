<x-layouts.guest-split heading="Masuk ke workspace"
                       description="Pakai akun kerja Anda untuk melanjutkan.">
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

    @if (session('status'))
        <x-ui.alert variant="success">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-ui.input name="email" type="email" label="Email kerja" placeholder="nama@perusahaan.com" required autofocus autocomplete="username" />

        <x-ui.input name="password" type="password" label="Kata sandi" placeholder="••••••••" required autocomplete="current-password" />

        <div class="flex items-center justify-between gap-3">
            <x-ui.checkbox name="remember" label="Ingat saya" :checked="old('remember')" />

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-link hover:underline">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <x-ui.button type="submit" block>Masuk</x-ui.button>

        @if (Route::has('register'))
            <p class="text-sm text-ink-muted">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium text-link hover:underline">Daftar</a>
            </p>
        @endif
    </form>
</x-layouts.guest-split>
