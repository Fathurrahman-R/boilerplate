@php
    // Dipanggil lewat @include dari create/edit. $user null berarti form tambah.
    $user ??= null;
    $isEdit = $user !== null;
@endphp

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-ui.card title="Data akun">
            <div class="space-y-4">
                <x-ui.input name="name" label="Nama lengkap" :value="$user?->name" required />

                <x-ui.input name="email" type="email" label="Email" :value="$user?->email" required />

                <x-ui.input name="password" type="password" label="Kata sandi"
                            :required="! $isEdit"
                            autocomplete="new-password"
                            :hint="$isEdit ? 'Kosongkan bila tidak ingin mengubah kata sandi.' : 'Minimal 8 karakter.'" />

                <x-ui.input name="password_confirmation" type="password" label="Ulangi kata sandi"
                            :required="! $isEdit" autocomplete="new-password" />

                <x-ui.toggle name="is_active" label="Akun aktif"
                             :checked="old('is_active', $user?->is_active ?? true)"
                             hint="Akun nonaktif tidak bisa masuk dan sesinya langsung diakhiri." />
            </div>
        </x-ui.card>
    </div>

    <div>
        <x-ui.card title="Role" subtitle="Menentukan permission yang dimiliki pengguna.">
            <div class="space-y-3">
                @foreach ($roles as $role)
                    <x-ui.checkbox name="roles[]"
                                   :value="$role->name"
                                   :label="$role->displayName()"
                                   :hint="$role->description"
                                   :id="'role_'.$role->id"
                                   :checked="in_array($role->name, old('roles', $user?->roles->pluck('name')->all() ?? []), true)" />
                @endforeach

                @error('roles')
                    <p class="text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </x-ui.card>
    </div>
</div>
