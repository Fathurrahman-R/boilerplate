<x-layouts.guest heading="Buat akun baru">
    <x-auth.errors />

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <x-ui.input name="name" label="Nama lengkap" required autofocus autocomplete="name" />

        <x-ui.input name="email" type="email" label="Email" placeholder="nama@perusahaan.com" required autocomplete="username" />

        <x-ui.input name="password" type="password" label="Kata sandi" placeholder="••••••••" required autocomplete="new-password"
                    hint="Minimal 8 karakter." />

        <x-ui.input name="password_confirmation" type="password" label="Ulangi kata sandi" placeholder="••••••••" required autocomplete="new-password" />

        <x-ui.button type="submit" block>Daftar</x-ui.button>

        <p class="text-sm text-gray-500 dark:text-gray-400">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Masuk</a>
        </p>
    </form>
</x-layouts.guest>
