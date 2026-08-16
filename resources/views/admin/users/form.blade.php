@php
    // Dipanggil lewat @include dari create/edit. $user null berarti form tambah.
    $user ??= null;
    $isEdit = $user !== null;
@endphp

<x-ui.card :padding="false">
    <div class="grid gap-5 p-[26px] [grid-template-columns:repeat(auto-fit,minmax(250px,1fr))]">
        <x-ui.input name="name" label="Nama lengkap" :value="$user?->name" required />

        <x-ui.input name="email" type="email" label="Email" :value="$user?->email" required />

        <x-ui.input name="password" type="password" label="Kata sandi"
                    :required="! $isEdit"
                    autocomplete="new-password"
                    :hint="$isEdit ? 'Kosongkan bila tidak ingin mengubah kata sandi.' : 'Minimal 8 karakter.'" />

        <x-ui.input name="password_confirmation" type="password" label="Ulangi kata sandi"
                    :required="! $isEdit" autocomplete="new-password" />
    </div>

    <div class="px-[26px] pb-[26px]">
        <h3 class="text-lg2 font-semibold text-ink">Status &amp; peran</h3>
        <p class="mt-0.5 text-base2 text-ink-secondary">Peran menentukan permission yang dimiliki pengguna.</p>

        <div class="mt-2 flex items-center justify-between gap-4 border-b border-line py-[13px]">
            <div>
                <div class="text-[14.5px] font-medium text-ink">Akun aktif</div>
                <div class="text-sm2 text-ink-muted">Akun nonaktif tidak bisa masuk dan sesinya langsung diakhiri.</div>
            </div>
            <x-ui.toggle name="is_active" :checked="old('is_active', $user?->is_active ?? true)" class="shrink-0" />
        </div>

        <div class="mt-3.5 flex flex-wrap gap-x-6 gap-y-3">
            @foreach ($roles as $role)
                <x-ui.checkbox name="roles[]"
                               :value="$role->name"
                               :label="$role->displayName()"
                               :hint="$role->description"
                               :id="'role_'.$role->id"
                               :checked="in_array($role->name, old('roles', $user?->roles->pluck('name')->all() ?? []), true)" />
            @endforeach
        </div>

        @error('roles')
            <p class="mt-2 text-sm2 text-danger">{{ $message }}</p>
        @enderror
    </div>
</x-ui.card>
