<x-layouts.guest heading="Masuk ke akun Anda">
    <x-auth.errors />

    @if (session('status'))
        <x-ui.alert variant="success">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-ui.input name="email" type="email" label="Email" placeholder="nama@perusahaan.com" required autofocus autocomplete="username" />

        <x-ui.input name="password" type="password" label="Kata sandi" placeholder="••••••••" required autocomplete="current-password" />

        <div class="flex items-center justify-between gap-3">
            <x-ui.checkbox name="remember" label="Ingat saya" :checked="old('remember')" />

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <x-ui.button type="submit" block>Masuk</x-ui.button>

        @if (Route::has('register'))
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Daftar</a>
            </p>
        @endif
    </form>
</x-layouts.guest>
