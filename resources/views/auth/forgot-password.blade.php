<x-layouts.guest heading="Lupa kata sandi"
                 description="Masukkan email Anda, kami kirimkan tautan untuk membuat kata sandi baru.">
    <x-auth.errors />

    @if (session('status'))
        <x-ui.alert variant="success">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <x-ui.input name="email" type="email" label="Email" placeholder="nama@perusahaan.com" required autofocus />

        <x-ui.button type="submit" block>Kirim tautan reset</x-ui.button>

        <p class="text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Kembali ke halaman masuk</a>
        </p>
    </form>
</x-layouts.guest>
