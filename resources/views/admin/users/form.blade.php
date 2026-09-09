@php
    // Dipanggil lewat @include dari create/edit. $user null berarti form tambah.
    $user ??= null;
    $isEdit = $user !== null;
@endphp

{{--
    Form sebagai grup daftar, bukan grid field di dalam kartu.

    Field yang berdiri berdampingan dalam kolom-kolom memaksa mata berpindah
    arah dua kali per baris. Satu kolom dengan label di kiri dibaca lurus ke
    bawah, dan penjelasan tinggal sekali di kaki grup alih-alih berulang di
    bawah tiap kotak.
--}}

<div class="measure flex flex-col gap-7">
    <x-ui.list title="Identitas">
        <x-ui.form-row label="Nama lengkap" for="name" required>
            <x-ui.input name="name" id="name" required :value="$user?->name" aria-label="Nama lengkap" />
        </x-ui.form-row>

        <x-ui.form-row label="Email" for="email" required>
            <x-ui.input name="email" id="email" type="email" required :value="$user?->email" aria-label="Email" />
        </x-ui.form-row>
    </x-ui.list>

    <x-ui.list title="Kata sandi"
               :hint="$isEdit ? 'Kosongkan bila tidak ingin mengubah kata sandi.' : 'Minimal 8 karakter.'">
        <x-ui.form-row label="Kata sandi" for="password" :required="! $isEdit">
            <x-ui.input name="password" id="password" type="password" :required="! $isEdit"
                        autocomplete="new-password" aria-label="Kata sandi" />
        </x-ui.form-row>

        <x-ui.form-row label="Ulangi kata sandi" for="password_confirmation" :required="! $isEdit">
            <x-ui.input name="password_confirmation" id="password_confirmation" type="password"
                        :required="! $isEdit" autocomplete="new-password" aria-label="Ulangi kata sandi" />
        </x-ui.form-row>
    </x-ui.list>

    <x-ui.list title="Status">
        <x-ui.list-row label="Akun aktif"
                       sublabel="Akun nonaktif tidak bisa masuk dan sesinya langsung diakhiri.">
            <x-slot:trailing>
                <x-ui.toggle name="is_active" :checked="old('is_active', $user?->is_active ?? true)" />
            </x-slot:trailing>
        </x-ui.list-row>
    </x-ui.list>

    <x-ui.list title="Peran" hint="Peran menentukan permission yang dimiliki pengguna.">
        @foreach ($roles as $role)
            <x-ui.list-row :label="$role->displayName()" :sublabel="$role->description">
                <x-slot:trailing>
                    <input type="checkbox"
                           name="roles[]"
                           value="{{ $role->name }}"
                           id="role_{{ $role->id }}"
                           class="form-check"
                           aria-label="{{ $role->displayName() }}"
                           @checked(in_array($role->name, old('roles', $user?->roles->pluck('name')->all() ?? []), true))>
                </x-slot:trailing>
            </x-ui.list-row>
        @endforeach
    </x-ui.list>

    @error('roles')
        <p class="px-4 text-sm text-danger">{{ $message }}</p>
    @enderror
</div>
