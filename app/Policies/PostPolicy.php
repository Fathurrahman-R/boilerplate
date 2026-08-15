<?php

namespace App\Policies;

/**
 * Contoh policy berbasis resource key.
 *
 * Cukup sebut nama resource-nya; seluruh method policy standar (viewAny,
 * create, update, delete, …) sudah diterjemahkan ke key oleh induknya.
 * Tambahkan method sendiri di sini kalau ada aturan khusus, misalnya:
 *
 *     public function update(Authorizable $user, Post $post): bool
 *     {
 *         return parent::update($user, $post) && $post->user_id === $user->id;
 *     }
 */
class PostPolicy extends BaseResourcePolicy
{
    protected function resource(): string
    {
        return 'posts';
    }
}
