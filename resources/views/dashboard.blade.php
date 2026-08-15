<x-layouts.admin heading="Dashboard" description="Ringkasan singkat isi aplikasi.">
    @if ($unmappedCount > 0)
        <x-ui.alert variant="warning" title="Ada resource key yang belum dipetakan" class="mb-6">
            {{ $unmappedCount }} key belum menunjuk permission mana pun, jadi aksesnya tertutup untuk semua orang
            kecuali super admin.
            <a href="{{ route('admin.mappings.index', ['status' => 'unmapped']) }}" class="font-medium underline">
                Lihat daftarnya
            </a>
        </x-ui.alert>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <x-ui.card>
                <div class="flex items-center gap-4">
                    <span class="rounded-lg bg-blue-50 p-3 text-blue-700 dark:bg-gray-700 dark:text-blue-400">
                        <x-ui.icon :name="$stat['icon']" class="h-6 w-6" />
                    </span>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stat['value']) }}</p>
                    </div>
                </div>
            </x-ui.card>
        @endforeach
    </div>

    <x-ui.card title="Mulai dari mana" class="mt-6">
        <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
            <li class="flex items-start gap-3">
                <x-ui.icon name="document" class="mt-0.5 h-5 w-5 shrink-0 text-gray-400" />
                <span>
                    Buat <strong>Resource</strong> baru untuk tiap modul aplikasi. Sistem otomatis membuatkan
                    permission untuk tiap aksi yang dicentang.
                </span>
            </li>
            <li class="flex items-start gap-3">
                <x-ui.icon name="link" class="mt-0.5 h-5 w-5 shrink-0 text-gray-400" />
                <span>
                    Ubah permission di balik sebuah key lewat <strong>Pemetaan Key</strong> — tanpa menyentuh kode.
                </span>
            </li>
            <li class="flex items-start gap-3">
                <x-ui.icon name="shield" class="mt-0.5 h-5 w-5 shrink-0 text-gray-400" />
                <span>
                    Bagikan permission ke <strong>Role</strong>, lalu tugaskan role itu ke pengguna.
                </span>
            </li>
        </ul>
    </x-ui.card>
</x-layouts.admin>
