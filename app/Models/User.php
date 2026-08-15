<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'avatar_path', 'is_active'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(config('resources.super_admin_role'));
    }

    public function avatarUrl(): string
    {
        if ($this->avatar_path && Storage::disk('public')->exists($this->avatar_path)) {
            return Storage::disk('public')->url($this->avatar_path);
        }

        // Avatar cadangan berupa inisial, dirender sebagai SVG agar tidak
        // perlu memanggil layanan luar.
        return 'data:image/svg+xml;base64,'.base64_encode($this->initialsAvatar());
    }

    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->name ?? '?')) ?: ['?'];

        $initials = mb_strtoupper(mb_substr($words[0], 0, 1));

        if (count($words) > 1) {
            $initials .= mb_strtoupper(mb_substr($words[count($words) - 1], 0, 1));
        }

        return $initials;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term) {
            $query->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%");
        });
    }

    private function initialsAvatar(): string
    {
        $initials = htmlspecialchars($this->initials(), ENT_QUOTES);

        return <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64">
            <rect width="64" height="64" rx="32" fill="#1f2a37"/>
            <text x="50%" y="50%" dy=".35em" text-anchor="middle"
                  font-family="system-ui, sans-serif" font-size="24" fill="#e5e7eb">{$initials}</text>
        </svg>
        SVG;
    }
}
