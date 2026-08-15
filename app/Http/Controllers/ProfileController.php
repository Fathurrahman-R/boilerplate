<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        // Ganti email berarti verifikasi harus diulang.
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('success', 'Profil diperbarui.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', File::image()->max(2 * 1024)],
        ], attributes: ['avatar' => 'Foto profil']);

        $user = $request->user();

        $this->deleteAvatarFile($user->avatar_path);

        $user->update([
            'avatar_path' => $request->file('avatar')->store('avatars', 'public'),
        ]);

        return back()->with('success', 'Foto profil diperbarui.');
    }

    public function destroyAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        $this->deleteAvatarFile($user->avatar_path);
        $user->update(['avatar_path' => null]);

        return back()->with('success', 'Foto profil dihapus.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);

        $user = $request->user();

        if ($user->isSuperAdmin() && User::role(config('resources.super_admin_role'))->count() <= 1) {
            return back()->with('error', 'Anda super admin terakhir. Tunjuk penggantinya sebelum menghapus akun.');
        }

        Auth::logout();

        $this->deleteAvatarFile($user->avatar_path);
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function deleteAvatarFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
