<x-layouts.admin heading="Profil saya"
                 description="Data akun, keamanan, dan foto profil."
                 :breadcrumb="['Profil' => null]">

    {{--
        Satu kolom sempit berisi grup daftar, bukan grid kartu tiga kolom.
        Halaman pengaturan dibaca dari atas ke bawah sekali jalan; membaginya
        jadi kolom memaksa mata bolak-balik untuk memastikan tidak ada yang
        terlewat.

        Label field berdiri di kiri kontrolnya, bukan di atasnya: satu grup
        berisi tiga field jadi separuh tingginya, dan kolom label bisa disapu
        mata untuk menemukan yang dicari tanpa membaca isinya.
    --}}
    <div class="measure flex flex-col gap-7 pb-10">

        <x-ui.list title="Foto profil" hint="JPG atau PNG, maksimal 2 MB.">
            <x-ui.list-row>
                <x-slot:leading>
                    <x-ui.avatar :user="$user" size="lg" />
                </x-slot:leading>

                <div class="flex flex-wrap items-center gap-2">
                    <form method="POST" action="{{ route('profile.avatar') }}"
                          enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                        @csrf
                        <x-ui.file-upload name="avatar" accept="image/*" class="w-[230px]" />
                        <x-ui.button type="submit" size="sm">Unggah</x-ui.button>
                    </form>

                    @if ($user->avatar_path)
                        <form method="POST" action="{{ route('profile.avatar.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <x-ui.button type="submit" variant="ghost" size="sm">Hapus foto</x-ui.button>
                        </form>
                    @endif
                </div>
            </x-ui.list-row>
        </x-ui.list>

        <form method="POST" action="{{ route('profile.update') }}" class="flex flex-col gap-3">
            @csrf
            @method('PATCH')

            <x-ui.list title="Data profil"
                       hint="Mengganti email akan meminta verifikasi ulang sebelum bisa dipakai masuk.">
                <x-ui.form-row label="Nama lengkap" for="name" required>
                    <x-ui.input name="name" id="name" required aria-label="Nama lengkap" :value="$user->name" />
                </x-ui.form-row>

                <x-ui.form-row label="Email" for="email" required>
                    <x-ui.input name="email" id="email" type="email" required aria-label="Email" :value="$user->email" />
                </x-ui.form-row>
            </x-ui.list>

            @if (! $user->hasVerifiedEmail())
                <x-ui.alert variant="warning">Email Anda belum diverifikasi.</x-ui.alert>
            @endif

            <div class="px-4">
                <x-ui.button type="submit">Simpan perubahan</x-ui.button>
            </div>
        </form>

        {{-- Route dan validasinya disediakan Fortify (Features::updatePasswords). --}}
        <form method="POST" action="{{ route('user-password.update') }}" class="flex flex-col gap-3">
            @csrf
            @method('PUT')

            <x-ui.list title="Kata sandi" hint="Minimal 8 karakter.">
                <x-ui.form-row label="Kata sandi saat ini" for="current_password" required>
                    <x-ui.input name="current_password" id="current_password" type="password" required
                                autocomplete="current-password" aria-label="Kata sandi saat ini" />
                </x-ui.form-row>

                <x-ui.form-row label="Kata sandi baru" for="password" required>
                    <x-ui.input name="password" id="password" type="password" required
                                autocomplete="new-password" aria-label="Kata sandi baru" />
                </x-ui.form-row>

                <x-ui.form-row label="Ulangi kata sandi baru" for="password_confirmation" required>
                    <x-ui.input name="password_confirmation" id="password_confirmation" type="password" required
                                autocomplete="new-password" aria-label="Ulangi kata sandi baru" />
                </x-ui.form-row>
            </x-ui.list>

            <div class="px-4">
                <x-ui.button type="submit">Perbarui kata sandi</x-ui.button>
            </div>
        </form>

        <x-ui.list title="Keamanan"
                   hint="Verifikasi dua langkah menambah kode sekali pakai dari aplikasi autentikator saat masuk.">
            <x-ui.list-row label="Verifikasi dua langkah"
                           :sublabel="$user->two_factor_secret ? 'Aktif untuk akun ini' : 'Belum diaktifkan'">
                <x-slot:trailing>
                    @if ($user->two_factor_secret)
                        <form method="POST" action="{{ route('two-factor.disable') }}">
                            @csrf
                            @method('DELETE')
                            <x-ui.button type="submit" variant="ghost" size="sm" class="text-danger">Matikan</x-ui.button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('two-factor.enable') }}">
                            @csrf
                            <x-ui.button type="submit" size="sm">Aktifkan</x-ui.button>
                        </form>
                    @endif
                </x-slot:trailing>
            </x-ui.list-row>

            @if ($user->two_factor_secret)
                <x-ui.list-row label="Kode QR" sublabel="Pindai dengan aplikasi autentikator Anda.">
                    <div class="mt-3 w-fit rounded-lg border-[0.5px] border-line bg-white p-3">
                        {!! $user->twoFactorQrCodeSvg() !!}
                    </div>
                </x-ui.list-row>

                <x-ui.list-row label="Kode pemulihan"
                               sublabel="Simpan di tempat aman. Dipakai kalau perangkat autentikator hilang.">
                    <details class="mt-2">
                        <summary class="cursor-pointer text-base font-medium text-link">Tampilkan kode</summary>
                        <ul class="num mt-2 flex flex-col gap-1 text-sm text-ink-secondary">
                            @foreach (json_decode(decrypt($user->two_factor_recovery_codes), true) as $code)
                                <li>{{ $code }}</li>
                            @endforeach
                        </ul>
                    </details>
                </x-ui.list-row>
            @endif
        </x-ui.list>

        <x-ui.list title="Role"
                   hint="Role menentukan halaman dan aksi mana yang terbuka untuk Anda. Hanya admin yang bisa mengubahnya.">
            <x-ui.list-row>
                <div class="flex flex-wrap gap-1.5">
                    @forelse ($user->roles as $role)
                        <x-ui.badge :variant="$role->isSuperAdmin() ? 'purple' : 'primary'">{{ $role->displayName() }}</x-ui.badge>
                    @empty
                        <span class="text-base text-ink-muted">Belum punya role.</span>
                    @endforelse
                </div>
            </x-ui.list-row>
        </x-ui.list>

        {{--
            Satu-satunya konfirmasi yang tersisa. Yang bisa dibatalkan cukup
            dibatalkan sesudahnya; yang benar-benar permanen — dan ini permanen
            — memang layak ditanya sekali, lengkap dengan kata sandi supaya
            tidak ada yang tersandung ke sini.
        --}}
        <x-ui.list title="Zona bahaya"
                   hint="Menghapus akun juga menghapus seluruh data yang terkait dengannya. Tidak bisa dibatalkan.">
            <x-ui.list-row label="Hapus akun saya" danger>
                <x-slot:trailing>
                    <x-ui.button type="button" variant="danger" size="sm"
                                 x-on:click="$dispatch('modal-open', 'hapus-akun')">
                        Hapus akun
                    </x-ui.button>
                </x-slot:trailing>
            </x-ui.list-row>
        </x-ui.list>

        <x-ui.modal id="hapus-akun" title="Hapus akun" size="sm">
            <p>Semua data yang terkait akun ini akan hilang. Masukkan kata sandi untuk mengonfirmasi.</p>

            <form method="POST" action="{{ route('profile.destroy') }}" id="form-hapus-akun">
                @csrf
                @method('DELETE')
                <x-ui.input name="password" type="password" label="Kata sandi" required />
            </form>

            <x-slot:footer>
                <x-ui.button variant="secondary" type="button" x-on:click="$dispatch('modal-close', 'hapus-akun')">Batal</x-ui.button>
                <x-ui.button variant="danger" type="submit" form="form-hapus-akun">Hapus akun</x-ui.button>
            </x-slot:footer>
        </x-ui.modal>
    </div>
</x-layouts.admin>
