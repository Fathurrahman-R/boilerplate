<x-layouts.guest heading="Konfirmasi kata sandi"
                 description="Bagian ini butuh konfirmasi identitas. Masukkan kembali kata sandi Anda.">
    <x-auth.errors />

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <x-ui.input name="password" type="password" label="Kata sandi" placeholder="••••••••" required autofocus autocomplete="current-password" />

        <x-ui.button type="submit" block>Konfirmasi</x-ui.button>
    </form>
</x-layouts.guest>
