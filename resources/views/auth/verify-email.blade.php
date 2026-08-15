<x-layouts.guest heading="Verifikasi email Anda"
                 description="Kami sudah mengirim tautan verifikasi ke email Anda. Klik tautan itu untuk melanjutkan.">
    @if (session('status') === 'verification-link-sent')
        <x-ui.alert variant="success">Tautan verifikasi baru sudah dikirim.</x-ui.alert>
    @endif

    <div class="flex flex-wrap items-center gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-ui.button type="submit">Kirim ulang tautan</x-ui.button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-ui.button type="submit" variant="secondary">Keluar</x-ui.button>
        </form>
    </div>
</x-layouts.guest>
