<x-layouts.guest heading="Buat kata sandi baru">
    <x-auth.errors />

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-ui.input name="email" type="email" label="Email" :value="$request->email" required />

        <x-ui.input name="password" type="password" label="Kata sandi baru" placeholder="••••••••" required autocomplete="new-password" autofocus />

        <x-ui.input name="password_confirmation" type="password" label="Ulangi kata sandi baru" placeholder="••••••••" required autocomplete="new-password" />

        <x-ui.button type="submit" block>Simpan kata sandi</x-ui.button>
    </form>
</x-layouts.guest>
